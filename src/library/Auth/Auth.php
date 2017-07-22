<?php
namespace App\library\Auth;

use Phalcon\Mvc\User\Component;

use App\Models\Users;
use App\Models\Personas;

use App\Models\RememberTokens;
use App\Models\SuccessLogins;
use App\Models\FailedLogins;
use App\Business\ConfigurationBSN;

/**
 * Libreria de Autenticación
 *
 * En esta libreria podemos encontrar los metodos que permiten al sistema
 * la autenticacion e ingreso de usuarios.
 *
 * @subpackage   Library
 * @category     Auth
 */
class Auth extends Component
{
    // Una hora por defecto, en caso de que no este configurado desde base de datos
    private $session_life_cycle = 3600;

    // Paciente rol
    private $PATIENT_ROLE = 4;
    /**
     * Checks the user credentials
     *
     * @param array $credentials
     * @return boolean
     * @throws Exception
     */

    public function check($credentials)
    {

        // Check if the user exist
        $user = Users::findFirst("email = '{$credentials['email']}' and role_id <> '{$this->PATIENT_ROLE}'");

        if ($user == false) {
            //$this->registerUserThrottling(0);
            throw new Exception('Combinación email/password Erronea o usted no está permitido de autenticarse','email');
        }

        // Check the password
        if (!$this->security->checkHash($credentials['password'], $user->password)) {
            //$this->registerUserThrottling($user->id);
            throw new Exception('Combinación email/password Erronea','email');
        }

        // Check if the user was flagged
        $this->checkUserFlags($user);

        // Register the successful login
        //$this->saveSuccessLogin($user);

        // Check if the remember me was selected
        if (isset($credentials['remember'])) {
            $this->createRememberEnvironment($user);
        }

        /**
         * obtenemos todas las sucursales del usuario
         */
        $sucursales = array();

        foreach ($user->UsersSpecialtiesBranchoffices as $usb) {

            array_push($sucursales, $usb->branch_office_id);
        }

        if( count($sucursales) > 0 ) {

            $sucursales = array_unique($sucursales);
            $sucursales = array_values($sucursales);
        }

        /**
         * Seteamos las variables de sesion
         * consiste en un array llamado auth-identity
         */
        if (
                !isset($user->id) or
                !isset($credentials['email']) or
                !isset($user->role_id) or
                !isset($user->Roles->name) or
                !isset($user->sucursal) or
                !isset($sucursales) or
                !isset($user->sucursal) or
                !isset($user->BranchOffices->name) or
                !isset($user->UserDetails->firstname)
            ) {

            throw new Exception('Usuario con perfil incompleto, favor indique problema y reintente más tarde.');

        }

        $this->session->set('auth-identity', array(
            'id'        => $user->id,
            'username'  => $credentials['email'],
			'email'     => $credentials['email'],
            'rol'       => $user->role_id,
            'rol_name'  => $user->Roles->name,
            'sucursal'  => $user->sucursal,
            'sucursales'=> $sucursales,
            'branchoffice_name'  => $user->BranchOffices->name,
            'nombre'    => mb_convert_case(mb_strtolower($user->UserDetails->getFullName()),MB_CASE_TITLE, "UTF-8")
        ));

        $this->session->set('temp-sucursales', array(
            'sucursal-id' => $user->sucursal,
            'branchoffice_name'  => $user->BranchOffices->name
        ));

        $this->createLifeCycleSession($user->email);

    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param \Gabs\Models\Users $user
     * @throws Exception
     */
    public function saveSuccessLogin($user)
    {
        $successLogin = new SuccessLogins();
        $successLogin->usersId = $user->id;
        $successLogin->ipAddress = $this->request->getClientAddress();
        $successLogin->userAgent = $this->request->getUserAgent();
        if (!$successLogin->save()) {
            $messages = $successLogin->getMessages();
            throw new Exception($messages[0]);
        }
    }

    /**
     * Implements login throttling
     * Reduces the effectiveness of brute force attacks
     *
     * @param int $userId
     */
    public function registerUserThrottling($userId)
    {
        $failedLogin = new FailedLogins();
        $failedLogin->usersId = $userId;
        $failedLogin->ipAddress = $this->request->getClientAddress();
        $failedLogin->attempted = time();
        $failedLogin->save();

        $attempts = FailedLogins::count(array(
            'ipAddress = ?0 AND attempted >= ?1',
            'bind' => array(
                $this->request->getClientAddress(),
                time() - 3600 * 6
            )
        ));

        switch ($attempts) {
            case 1:
            case 2:
                // no delay
                break;
            case 3:
            case 4:
                sleep(2);
                break;
            default:
                sleep(4);
                break;
        }
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param \Gabs\Models\Users $user
     */
    public function createRememberEnvironment(Users $user)
    {
        $userAgent = $this->request->getUserAgent();
        $token = md5($user->email . $user->password . $userAgent);

        $remember = new RememberTokens();
        $remember->usersId = $user->id;
        $remember->token = $token;
        $remember->userAgent = $userAgent;

        if ($remember->save() != false) {
            $expire = time() + $this->session_life_cycle;
            $this->cookies->set('RMU', $user->id, $expire);
            $this->cookies->set('RMT', $token, $expire);
        }
    }

    /**
     * Check if the session has a remember me cookie
     *
     * @return boolean
     */
    public function hasRememberMe()
    {
        return $this->cookies->has('RMU');
    }

    /**
     * Logs on using the information in the cookies
     *
     * @return \Phalcon\Http\Response
     */
    public function loginWithRememberMe()
    {
        $userId = $this->cookies->get('RMU')->getValue();
        $cookieToken = $this->cookies->get('RMT')->getValue();

        $user = Users::findFirstById($userId);
        if ($user) {

            $userAgent = $this->request->getUserAgent();
            $token = md5($user->email . $user->password . $userAgent);

            if ($cookieToken == $token) {

                $remember = RememberTokens::findFirst(array(
                    'usersId = ?0 AND token = ?1',
                    'bind' => array(
                        $user->id,
                        $token
                    )
                ));
                if ($remember) {

                    // Check if the cookie has not expired
                    if ((time() - (86400 * 8)) < $remember->createdAt) {

                        // Check if the user was flagged
                        $this->checkUserFlags($user);

                        // Register identity
                        $this->session->set('auth-identity', array(
                            'id' => $user->id,
                            'name' => $user->name,
                            'profile' => $user->profile->name,
							'correo' => $user->profile->correo
                        ));

                        // Register the successful login
                        $this->saveSuccessLogin($user);

                        return $this->response->redirect('users');
                    }
                }
            }
        }

        $this->cookies->get('RMU')->delete();
        $this->cookies->get('RMT')->delete();

        return $this->response->redirect('session/login');
    }

    /**
     * Checks if the user is banned/inactive/suspended
     *
     * @param \Gabs\Models\Users $user
     * @throws Exception
     */
    public function checkUserFlags(Users $user)
    {
        if ($user->active != 'Y') {
            throw new Exception('The user is inactive');
        }

        if ($user->banned != 'N') {
            throw new Exception('The user is banned');
        }

        if ($user->suspended != 'N') {
            throw new Exception('The user is suspended');
        }
    }

    /**
     * Returns the current identity
     *
     * @return array
     */
    public function getIdentity()
    {

        return $this->session->get('auth-identity');
    }

    /**
     * Returns the current identity
     *
     * @return string
     */
    public function getName()
    {
        $identity = $this->session->get('auth-identity');
        return $identity['name'];
    }

    /**
     * Removes the user identity information from session
     */
    public function remove()
    {
        if ($this->cookies->has('RMU')) {
            $this->cookies->get('RMU')->delete();
        }
        if ($this->cookies->has('RMT')) {
            $this->cookies->get('RMT')->delete();
        }

        $this->session->remove('auth-identity');
        $this->session->remove('temp-sucursales');

    }

    /**
     * Auths the user by his/her id
     *
     * @param int $id
     * @throws Exception
     */
    public function authUserById($id)
    {
        $user = Users::findFirstById($id);
        if ($user == false) {
            throw new Exception('The user does not exist');
        }

        $this->checkUserFlags($user);

        $this->session->set('auth-identity', array(
            'id' => $user->id,
            'name' => $user->name,
            'profile' => $user->profile->name,
			'correo' => $user->profile->correo,
        ));
    }

    /**
     * Get the entity related to user in the active identity
     *
     * @return \Gabs\Models\Users
     * @throws Exception
     */
    public function getUser()
    {
        $identity = $this->session->get('auth-identity');
        if (isset($identity['id'])) {

            $user = Users::findFirstById($identity['id']);
            if ($user == false) {
                throw new Exception('The user does not exist');
            }

            return $user;
        }

        return false;
    }

     /**
     * Actualiza el valor de un determinado campo en la session
     *
     * @return \Gabs\Models\Users
     * @throws Boolean
     */

    public function updateSessionField($var, $field, $value){

        $sessionTmp = $this->session->get($var);

        if (isset($sessionTmp[$field])) {

            $sessionTmp[$field] = $value;
            $this->session->set($var, $sessionTmp);

            return true;
        }else{

            return false;
        }

    }

    /**
     * Creates the cycle life of session with temp token.
     *
     * @param \Gabs\Models\Users $user->email
     */
    public function createLifeCycleSession($email)
    {
        // set session life time
        $life = $this->config->get("switchUtils")["session_life_cycle"];

        if($life){

            $session_life_cycle = $life['value'];
        } else {
            $session_life_cycle = $this->session_life_cycle;
        }

        $token = md5($email);

        $expire = time() + (int) $session_life_cycle;

        //var_dump($this->session_life_cycle);exit;
        $this->cookies->set('LCTO', $token, $expire);

    }

}
