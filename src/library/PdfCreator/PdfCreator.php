<?php

namespace App\library\PdfCreator;

use Phalcon\Mvc\User\Component;
use App\library\Auth\Exception;


/**
 * Generador de Pdf
 *
 * En esta clase podemos encontrar la implementación de una librería que nos permite
 * crear documentos Pdf en base a código Html. La librería utilizada en la implementeción
 * es mpdf.
 *
 *
 * @subpackage   Library
 * @category     PdfCreator
 */
class PdfCreator extends Component
{

    /**
    *Crea Documento Horizontal
    *
    *Genera un archivo pdf horizontal mediante un string html
    *         Tambien inserta el numero de pagina en el Footer
    *
    * @param string  $html: Contiene el codigo html a renderizar en pdf
    *                       por la libreria
    *        string $pdfname: Captura el nombre del archivo pdf a crear
    *
    */
    public function mpdfCreatorHorizontal($html, $pdfname, $htmlFooter = null){

        $mpdf = new \mPDF('utf-8', 'A4-L');
        $mpdf->setFooter('{PAGENO}');
        $mpdf->WriteHTML($html);

        if(isset($htmlFooter)){
            $mpdf->SetHTMLFooter($htmlFooter.'<hr style="color: black; color: black;"> <div style="text-align: right;"> {PAGENO} </div>');
        }else{
            $mpdf->setFooter('{PAGENO}');

        }
        if (!preg_match('/(\.pdf)$/i', $pdfname)) {

            $pdfname = $pdfname . '.pdf';
        }

        $mpdf->Output($pdfname,'I');

    }

    /**
    *Crea Documento Vertical
    *
    *Genera un archivo pdf vertical mediante un string html
    *         Tambien inserta el numero de pagina en el Footer
    *
    * @param string  $html: Contiene el codigo html a renderizar en pdf
    *                       por la libreria
    *        string $pdfname: Captura el nombre del archivo pdf a crear
    *
    */
    public function mpdfCreatorVertical($html, $pdfname, $htmlFooter = null, $pageno = true){


        $mpdf = new \mPDF('utf-8');
        $mpdf->WriteHTML($html);

        if(isset($htmlFooter)) {

            if($pageno) {
                $mpdf->SetHTMLFooter($htmlFooter.'<hr style="color: black; color: black;"> <div style="text-align: right;"> {PAGENO} </div>');
            } else {
                $mpdf->SetHTMLFooter($htmlFooter);
            }


        }else{

            if($pageno) {
                $mpdf->setFooter('{PAGENO}');
            } 
        }

        $mpdf->Output($pdfname,'I');

        unset($mpdf);

    }

    /**
     *Crea Documento Vertical con CSS
     *
     *Genera un archivo pdf vertical mediante un string html
     *         Tambien inserta el numero de pagina en el Footer
     *
     * @param string  $html: Contiene el codigo html a renderizar en pdf
     *                       por la libreria
     *        string $pdfname: Captura el nombre del archivo pdf a crear
     *
     *        array $$cssDocs: lista de documentos css que se quieren agregar
     *
     */
    public function mpdfCreatorVerticalCss($html, $pdfname, $cssDocs){

        $mpdf = new \mPDF('utf-8');

        $count = 1;
        foreach ($cssDocs as $css) {
            $stylesheet = file_get_contents($css); // external css
            $mpdf->WriteHTML($stylesheet,$count);
            $count = $count + 1;
        }

        $mpdf->WriteHTML($html, $count);
        $mpdf->setFooter('{PAGENO}');


        $mpdf->Output($pdfname,'I');

    }

}
