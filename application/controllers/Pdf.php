<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require  './vendor/autoload.php';

class PDF extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('valor_campo_model');
        $this->load->model('mecanico_model');
        $this->load->model('cliente_model');
        $this->load->model('categoria_model');
        $this->load->model('campo_model');
        $this->load->model('inspeccion_model');
        $this->load->model('solicitud_model');
        $this->load->model('fotos_auto_model');
    }

    public function pdf_get($idInspeccion) {
        $data = $this->getData($idInspeccion);
        if (!is_null($data)) {
            $fotos = $this->fotos_auto_model->getPDFInspeccion($idInspeccion);
            $html = $this->load->view('inspeccion_view', $data, true);
            $data2['fotos'] = $fotos;
            $htmlFotos = $this->load->view('inspeccionFotos_view', $data2, true);

            $ruta = 'informes/Inspeccion-' . $idInspeccion . '.pdf';

            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'Letter-L']);
            $mpdf->WriteHTML($html);
            $mpdf->AddPage();
            $mpdf->WriteHTML($htmlFotos);
            $mpdf->Output($ruta, 'F');
            $this->response(array('inspeccion' => 'Informe creado y almacenado'), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('error' => 'Inspeción Inválida'), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function pagina_get($pag, $limit) {
        echo $limit;

    }

    

    private function getData($idInspeccion) {
        $inspeccion = $this->inspeccion_model->get($idInspeccion);
        if (!is_null($inspeccion)) {
            $solicitud = $this->solicitud_model->get($inspeccion['solicitud_id']);
            $id_cliente = $solicitud['cliente_usuario_id'];
            $detalle = $this->valor_campo_model->getPdfInspeccion($idInspeccion);
            $cliente = $this->cliente_model->get($id_cliente);

            $mecanico = $this->mecanico_model->get($inspeccion['mecanico_usuario_id']);

            $data['detalle'] = $detalle;
            $data['cliente'] = $cliente;
            $data['mecanico'] = $mecanico;
            $data['inspeccion'] = $inspeccion;

            return $data;
        }
        return null;
    }

    public function vista_get($idInspeccion) {
        $data = $this->getData($idInspeccion);
        if (!is_null($data)) {

            $fotos = $this->fotos_auto_model->getPDFInspeccion($idInspeccion);

            $this->load->view('inspeccion_view', $data);

            $data2['fotos'] = $fotos;
            $this->load->view('inspeccionFotos_view', $data2);
        } else {
            $this->response(array('error' => 'Inspeción Inválida'), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function correo_get($idInspeccion) {

        $archivo = 'informes/Inspeccion-' . $idInspeccion . '.pdf';
        if (file_exists($archivo)) {

            $config = array();
                   //$config['useragent'] = "CodeIgniter";
              //$config['mailpath'] = "/usr/sbin/sendmail";  or "/usr/bin/sendmail"
              //$config['protocol'] = "sendmail";
              //$config['smtp_host'] = "localhost";
              //$config['smtp_port'] = "25";
              //$config['charset'] = 'utf-8';
              //$config['newline'] = "\r\n";
              //$config['wordwrap'] = TRUE;
              //$config['send_multipart'] = FALSE;
             


            $config['protocol'] = "smtp";
            $config['smtp_host'] = "ssl://smtp.gmail.com";
            $config['smtp_port'] = "465";
            $config['smtp_user'] = "esteban.contreras.shaw@gmail.com";
            $config['smtp_pass'] = "yosolito";
            $config['charset'] = "utf-8";
            $config['mailtype'] = "html";
            $config['newline'] = "\r\n";

            $this->load->library('email');
            $this->email->initialize($config);
            //$this->email->set_newline("\r\n");
            $this->email->from('esteban.contreras.shaw@gmail.com', 'Esteban');
            $this->email->to('esteban.contreras.shaw@gmail.com');
            $this->email->subject('Test Email (Attachment)');
            $this->email->message('Prueba Informe');
            $this->email->attach($archivo);
            if ($this->email->send(FALSE)) {
                echo "enviado<br/>";
                echo $this->email->print_debugger(array('headers'));
            } else {
                echo "fallo <br/>";
                echo "error: " . $this->email->print_debugger(array('headers'));
            }

            //var_dump($this->email->print_debugger());
        } else {
            $this->response(array('error' => 'Inspeción Inválida'), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function verInforme_get($idInspeccion) {
        $mi_pdf = 'informes/Inspeccion-' . $idInspeccion . '.pdf';
        if (file_exists($mi_pdf)) {
            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename="' . $mi_pdf . '"');
            readfile($mi_pdf);
        } else {
            $this->response(array('error' => 'Inspeción Inválida'), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function descargarInforme_get($idInspeccion) {
        $mi_pdf = 'informes/Inspeccion-' . $idInspeccion . '.pdf';
        if (file_exists($mi_pdf)) {
            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $mi_pdf . '"');
            readfile($mi_pdf);
        } else {
            $this->response(array('error' => 'Inspeción Inválida'), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function correoAzure_get($idInspeccion) {
        $nombre = 'Inspeccion-' . $idInspeccion . '.pdf';
        $archivo = 'informes/Inspeccion-' . $idInspeccion . '.pdf';
        if (file_exists($archivo)) {
            $text = $nombre."\n Este mail es generado de manera automática, por favor NO RESPONDER.\n";
            $html = "<html>
       <head></head>
       <body>
           <p>$nombre <br>
               Este mail es generado de manera automática, por favor NO RESPONDER.<br>
           </p>
       </body>
       </html>";
            // This is your From email address
            $from = array('server@localhost' => 'Inspector Mecanico');
            // Email recipients
            $to = array(
                'esteban.contreras.shaw@gmail.com' => 'Destinatario'
            );
            // Email subject
            $subject = 'PDF formulario';

            // Login credentials
            $username = 'azure_55380a2514b486c067ac21eb697cae3e@azure.com';
            $password = 'Econtreras1234';

            // Setup Swift mailer parameters
            $transport = (new Swift_SmtpTransport('smtp.sendgrid.net', 587));
            $transport->setUsername($username);
            $transport->setPassword($password);
            $swift = new Swift_Mailer($transport);

            // Create a message (subject)
            $message = new Swift_Message($subject);

            // attach the body of the email
            $message->setFrom($from);
            $message->setBody($html, 'text/html');
            $message->setTo($to);
            $message->addPart($text, 'text/plain');
            $message->attach(Swift_Attachment::fromPath($archivo)->setFileName($nombre));
            // send message
            if ($recipients = $swift->send($message, $failures)) {
                // This will let us know how many users received this message
                //echo 'Message sent out to ' . $recipients . ' users';
                $this->response(array('correo' => 'Mensaje enviado'), REST_Controller::HTTP_OK);
            }
            // something went wrong =(
            else {
                //echo "Something went wrong - ";
                //print_r($failures);
                $this->response(array('error' => print_r($failures)), REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

}
