<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require_once 'vendor/autoload.php';

class PDF extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('inspeccion_model');
        $this->load->model('solicitud_model');
        $this->load->model('admin_model');
        $this->load->model('mecanico_model');
        $this->load->model('categoria_model');
        $this->load->model('campo_model');
        $this->load->model('valor_campo_model');
        $this->load->model('fotos_auto_model');
        $this->load->model('empresa_model');
    }


    public function pdf_get($idInspeccion) {
        $data = $this->getData($idInspeccion);
        if (!is_null($data)) {
            $empresa = $data['empresa'];
            $fotos = $this->fotos_auto_model->getPDFInspeccion($idInspeccion);
            $html = $this->load->view('Inspeccion_view', $data, true);
            $data2['fotos'] = $fotos;
            $htmlFotos = $this->load->view('InspeccionFotos_view', $data2, true);

            //$urlnombre = explode(".", $_SERVER['HTTP_HOST']);
            //$subdominio = $data['mecanico']['empresa'];
            $ruta = 'anexos/'.$empresa.'/informes/Inspeccion-' . $idInspeccion . '.pdf';

            if (!file_exists('anexos/'.$empresa.'/informes')) {
                if(!is_dir('anexos/'.$empresa)){
                    mkdir('anexos/'.$empresa, 0777);
                }
                mkdir('anexos/'.$empresa.'/informes', 0777);
            }
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'Letter-L']);
            $mpdf->WriteHTML($html);
            $mpdf->AddPage();
            $mpdf->WriteHTML($htmlFotos);
            $mpdf->Output($ruta, 'F');
            return TRUE;
        } else {
            return null;
        }
    }

    private function getData($idInspeccion) {
        $inspeccion = $this->inspeccion_model->get($idInspeccion);
        if (!is_null($inspeccion)) {
            $empresa = $this->empresa_model->get($inspeccion['empresa_id']);
            $solicitud = $this->solicitud_model->get($inspeccion['solicitud_id']);
            $detalle = $this->valor_campo_model->getPdfInspeccion($idInspeccion);
            $mecanico = $this->mecanico_model->get($inspeccion['mecanico_usuario_id']);

            $data['empresa'] = $empresa['empresa'];
            $data['detalle'] = $detalle;
            $data['solicitud'] = $solicitud;
            $data['mecanico'] = $mecanico;
            $data['inspeccion'] = $inspeccion;

            return $data;
        }
        return null;
    }

    public function pdf($idInspeccion) {
        $data = $this->getData($idInspeccion);
        if (!is_null($data)) {
            $fotos = $this->fotos_auto_model->getPDFInspeccion($idInspeccion);
            $html = $this->load->view('Inspeccion_view', $data, true);
            $data2['fotos'] = $fotos;
            $htmlFotos = $this->load->view('InspeccionFotos_view', $data2, true);

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


    public function verInforme_get($idInspeccion) {
        $inspeccion = $this->inspeccion_model->get($idInspeccion);
        if (!is_null($inspeccion)) {
            $cliente= $this->solicitud_model->get($inspeccion['solicitud_id'])['email'];
            $empresa= $this->empresa_model->get($inspeccion['empresa_id'])['empresa'];

            $archivo = 'anexos/'.$empresa.'/informes/Inspeccion-' . $idInspeccion . '.pdf';
        
            if (file_exists($archivo)) {
                header('Content-type: application/pdf');
                header('Content-Disposition: inline; filename="' . $archivo . '"');
                readfile($mi_pdf);
            }else {
            $this->response(array('error' => 'Inspeción Inválida'), REST_Controller::HTTP_NOT_FOUND);
        }
        }else {
            $this->response(array('error' => 'Inspeción Inválida'), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function descargarInforme_get($idInspeccion) {
        $inspeccion = $this->inspeccion_model->get($idInspeccion);
        if (!is_null($inspeccion)) {
            $cliente= $this->solicitud_model->get($inspeccion['solicitud_id'])['email'];
            $empresa= $this->empresa_model->get($inspeccion['empresa_id'])['empresa'];

            $archivo = 'anexos/'.$empresa.'/informes/Inspeccion-' . $idInspeccion . '.pdf';
        
            if (file_exists($archivo)) {
                header('Content-type: application/pdf');
                header('Content-Disposition: attachment; filename="' . $archivo . '"');
                readfile($mi_pdf);
            }else {
            $this->response(array('error' => 'Inspeción Inválida'), REST_Controller::HTTP_NOT_FOUND);
        }
        }else {
            $this->response(array('error' => 'Inspeción Inválida'), REST_Controller::HTTP_NOT_FOUND);
        }
    }


}
