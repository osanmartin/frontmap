<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>{{ config.appTitle }}</title>

    <meta name="description"    content={{ '"' ~ config.appName  ~ '"' }} >
    <meta name="author"         content={{ '"' ~ config.appAutor  ~ '"' }}>
    <meta name="theme-color" content="#1e96ee">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    

    <link rel="shortcut icon" href="{{ url("img/favicon-32x32.png") }}">

    {# CSS GENERICAS #}
    {{ stylesheet_link('css/plugins/bootstrap/bootstrap.min.css') }}
    {{ stylesheet_link('css/plugins/bootstrap/bootstrap-theme.min.css') }}
    {{ stylesheet_link('css/main/jquery-ui.min.css') }}
    {{ stylesheet_link('css/main/font-awesome.min.css') }}
    {{ stylesheet_link('css/main/icomoon.css') }}
    {{ stylesheet_link('css/plugins/datepicker-custom.css') }}
    {{ stylesheet_link('css/plugins/nprogress.css') }}
    {{ stylesheet_link('css/plugins/animate.css') }}
    {{ stylesheet_link('css/plugins/sweetalert2.min.css') }}
    {{ stylesheet_link('css/plugins/bootstrap-timepicker.css') }}
    {{ stylesheet_link('css/plugins/chosen.min.css') }}
    {{ stylesheet_link('css/plugins/alertify.core.css') }}
    {{ stylesheet_link('css/plugins/alertify.default.css')}}
    {{ stylesheet_link('css/plugins/lightbox.css') }}
    {{ stylesheet_link('css/plugins/chartist.css') }}
    {{ stylesheet_link('css/plugins/jquery-ui-timepicker-addon.css') }}
    {{ stylesheet_link('css/plugins/burger-menu.css') }}
    {{ stylesheet_link('css/plugins/bootstrap-rating.css') }}
    {{ stylesheet_link('css/plugins/liquid.css') }}

    {# DataTable #}
    {{ stylesheet_link('css/plugins/jquery.dataTables.css')}}
    {{ stylesheet_link('css/plugins/dataTables.bootstrap.css')}}

    {# CSS CUSTOM #}
    {{ stylesheet_link('css/main/app.css') }}
    {{ stylesheet_link('css/main/login.css') }}
    {{ stylesheet_link('css/plugins/sticky-footer.css') }}
    

    {{ assets.outputCss() }}

</head>



<body>

    <div id="fb-root"></div>
    <script>

          window.fbAsyncInit = function() {
              FB.init({appId: 'XXX', status: true, cookie: true, xfbml: true});

          };
          (function() {
            var e = document.createElement('script'); e.async = true;
            e.src = document.location.protocol +
              '//connect.facebook.net/en_US/all.js';
            document.getElementById('fb-root').appendChild(e);
          }());

        function fetchUserDetail()
        {
            FB.api('/me', function(response) {
                    alert("Name: "+ response.name + "\nFirst name: "+ response.first_name + "ID: "+response.id);
                });
        }

        function checkFacebookLogin() 
        {
            FB.getLoginStatus(function(response) {
              if (response.status === 'connected') {
                fetchUserDetail();
              } 
              else 
              {
                initiateFBLogin();
              }
             });
        }

        function initiateFBLogin()
        {
            FB.login(function(response) {
               fetchUserDetail();
             });
        }
        </script>

    <input id="CSRF-TOKEN" type="hidden" name="{{ session.get('csrf-token')['key'] }}" value="{{ session.get('csrf-token')['token'] }}">


    {% block content %}




    {% endblock %}



<div id="alertify-flash" class="hidden">
    {# Mensajes Flash: se dejan hidden ya que se manejan con alertify (app.js) #}
    <?php if($this->flashSession->output() !== null): ?>
        <?php echo $this->flashSession->output(); ?>
    <?php endif; ?>

    {% if flashSessionMsg is defined %}

        {% for class, msg in flashSessionMsg %}

            <p class="{{class}}"> {{msg}} </p>

        {% endfor %}

    {% endif %}

</div>



{# JS GENERICAS #}
{{ javascript_include('js/main/jquery-2.2.0.min.js') }}
{{ javascript_include('js/plugins/jquery-ui.min.js') }}
{{ javascript_include('js/plugins/bootstrap.min.js') }}
{{ javascript_include('js/plugins/jquery.others-plugins.js') }}
{{ javascript_include('js/plugins/sweetalert2.min.js') }}
{{ javascript_include('js/plugins/bootstrap-timepicker.js') }}
{{ javascript_include('js/plugins/alertify.js') }}
{{ javascript_include('js/plugins/lightbox.js') }}
{{ javascript_include('js/plugins/chartist.js') }}
{{ javascript_include('js/plugins/jquery-ui-timepicker-addon.js') }}

{{ javascript_include('js/plugins/bootstrap-rating.js') }}



{# Chosen #}
{{ javascript_include('js/plugins/chosen.jquery.min.js') }}

{# Mask #}
{{ javascript_include('js/plugins/jquery.mask.min.js') }}

{# DataTable #}
{{ javascript_include('js/plugins/jquery.dataTables.js') }}
{{ javascript_include('js/plugins/dataTables.select.min.js') }}
{{ javascript_include('js/plugins/dataTables.bootstrap.js') }}
{{ javascript_include('js/plugins/burger-menu.js') }}


{# CSS CUSTOM #}

{{ javascript_include('js/plugins/customize-map.js') }}
{{ javascript_include('js/plugins/mifaces.js') }}
{{ javascript_include('js/plugins/jquery.utilidades.js') }}
{{ javascript_include('js/main/app.js') }}
{{ javascript_include('js/main/login.js') }}



{% block addJs %}{% endblock %}

<div id="modal-master">
</div>

</body>
</html>
