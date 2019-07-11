<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require_once 'vendor/autoload.php';

class Inspeccion extends REST_Controller {

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
        $this->load->library('Authorization_Token');
    }

    public function index_get() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 3)))) {
            $inspeccion = $this->inspeccion_model->get();
            if (!is_null($inspeccion)) {
                $this->response(array('status' => TRUE, 'inspeccion' => $inspeccion), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status' => FALSE, 'error' => 'No hay inspeccions en la base de datos...'), REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function find_get($id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 3)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }          
            $inspeccion = $this->inspeccion_model->get($id);
            $inspeccion['mecanico_usuario_id'] = $this->mecanico_model->get($inspeccion['mecanico_usuario_id']);
            $inspeccion['solicitud_id'] = $this->solicitud_model->get($inspeccion['solicitud_id']);
            if (!is_null($inspeccion)) {
                $this->response(array('status' => TRUE, 'inspeccion' => $inspeccion), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status' => FALSE, 'error' => 'inspeccion no encontrado...'), REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    /*
    Metodo GET findDetalle:
    recibe el id de una inspeccion y retorna el contenido asociado a los campos de un formulario
    si Cumple, No Cumple o No Aplica y sus observaciones generales y comentarios para cada 
    campo del formulario

     */
    public function findDetalle_get($id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 3)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $inspeccion = $this->inspeccion_model->get($id);
            if (!is_null($inspeccion)) {
                $inspeccion['detalle'] = $this->valor_campo_model->getPorInspeccion($id);
                $this->response(array('status' => TRUE, 'inspeccion' => $inspeccion), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status' => FALSE, 'error' => 'inspeccion no encontrado...'), REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }


    /*
    Metodo GET findFoto:
    recibe el id de una inspeccion junto con el id de una foto asociada al auto, de manera
    de retornar una imagen en formato string Base 64
     */
    public function findFoto_get($id, $fotoId) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 3)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $inspeccion = $this->inspeccion_model->get($id);
            if (!is_null($inspeccion)) {
                $foto = $this->fotos_auto_model->get($fotoId);
                $this->response(array('fotos_auto' => $foto), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status' => FALSE, 'error' => 'inspeccion no encontrado...'), REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    /*
    Metodo GET findAlbum:
    recibe el id de una inspeccion y retorna las imagenes asociadas en formato string Base 64
     */
    public function findAlbum_get($id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 3)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $inspeccion = $this->inspeccion_model->get($id);
            if (!is_null($inspeccion)) {
                $inspeccion['fotos'] = $this->fotos_auto_model->getPDFInspeccion($id);
                $this->response(array('status' => TRUE, 'inspeccion' => $inspeccion), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status' => FALSE, 'error' => 'inspeccion no encontrado...'), REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    /*
    Metodo GET findFull:
    recibe el id de una inspeccion y retorna toda la informacion asociada a la inspeccion junto con su detalle y sus fotografias en formato string Base 64
     */
    public function findFull_get($id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 3)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $inspeccion = $this->inspeccion_model->get($id);
            if (!is_null($inspeccion)) {
                $inspeccion['detalle'] = $this->valor_campo_model->getPorInspeccion($id);
                $inspeccion['fotos'] = $this->fotos_auto_model->getPDFInspeccion($id);
                $this->response(array('status' => TRUE, 'inspeccion' => $inspeccion), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status' => FALSE, 'error' => 'inspeccion no encontrado...'), REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    /*
    Metodo GET findLog :
    recibe el id de una inspeccion y retorna todos los estados de las etapas de la inspeccion
     */
    public function findEstado_get($id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(3)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $inspeccion = $this->inspeccion_model->get($id);
            if (!is_null($inspeccion)) {                
                $this->response(array('status' => TRUE, 'inspeccion' => $inspeccion['estado']), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status' => FALSE, 'error' => 'inspeccion no encontrado...'), REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    /*
    Metodo POST terminar :
    recibe el id de una inspeccion a traves del metodo post, en un objeto cuya clave es 
    inpseccion_id y su valor es el id correspondiente, comprueba que existan tanto fotos 
    como el formulario llenado y logs de etapas, cambia el estado de una inspeccion a 
    finalizado y por unica vez genera un informe en PDF y envia al correo 
    del cliente dicho informe.
     */

    public function terminar_post() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE ) {
            if (!$this->post('inspeccion_id') || !is_numeric($this->post('inspeccion_id'))) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $logFlag = null;
            $id = $this->post('inspeccion_id');
            $flagIns = $this->inspeccion_model->get($id);
            $flagVal = $this->valor_campo_model->getPorInspeccion($id);
            $flagFot = $this->fotos_auto_model->getInspeccion($id);
            if (!is_null($flagFot) && !is_null($flagIns) && !is_null($flagVal)) {
                if($flagIns['estado']==1){
                    $this->inspeccion_model->estado($id, 2);
                    $logFlag = true;
                }else{
                    $this->response(array('status' => TRUE, 'inspeccion ya finalizada' => $id), REST_Controller::HTTP_OK);
                }
            }
            if (!is_null($logFlag)) {
                $this->pdf($id);
                $this->correo($id);
                $this->response(array('status' => TRUE, 'inspeccion finalizada y enviada' => $id), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status' => FALSE, 'error' => 'Algo se ha roto en el servidor...'), REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    /*
    Metodo POST reenviar :
    recibe el id de una inspeccion a traves del metodo post, en un objeto cuya clave es 
    inpseccion_id y su valor es el id correspondiente, comprueba que existan tanto fotos 
    como el formulario llenado y logs de etapas, compreuba que el estado del informe sea 
    'finalizado' genera un informe en PDF y envia al correo del cliente dicho informe cada vex¿z que se llama al metodo. En particular solo el SuperAdmin puede llamar a este metodo
     */
    public function reenviar_post() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(3)))) {
            if (!$this->post('inspeccion_id') || !is_numeric($this->post('inspeccion_id'))) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $logFlag = null;
            $id = $this->post('inspeccion_id');
            $flagIns = $this->inspeccion_model->get($id);
            $flagVal = $this->valor_campo_model->getPorInspeccion($id);
            $flagFot = $this->fotos_auto_model->getInspeccion($id);
            if (!is_null($flagFot) && !is_null($flagIns) && !is_null($flagVal)) {
                if($flagIns['estado'] >= 2){
                    $logFlag = true;
                }
            }
            if (!is_null($logFlag)) {
                $this->pdf($id);
                $this->correo($id);
                $this->response(array('status' => TRUE, 'inspeccion reenviada' => $id), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status' => FALSE, 'error' => 'Algo se ha roto en el servidor...'), REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function index_post() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE ) {
            if (!$this->post('inspeccion')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $id = $this->inspeccion_model->save($this->post('inspeccion'));
            if (!is_null($id)) {
                if ($id == (-1)) {
                    $this->response(array('status' => FALSE, 'error' => 'El Número de orden ingresado ya existe'), REST_Controller::HTTP_BAD_REQUEST);
                } else {
                    $this->response(array('status' => TRUE, 'inspeccion' => $id), REST_Controller::HTTP_OK);
                }
            } else {
                $this->response(array('status' => FALSE, 'error' => 'Algo se ha roto en el servidor...'), REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    /*
    Metodo POST detalle:
    recibe un arreglo de valores asociados a los campos de un formulario junto con sus observaciones,
    y los registra en una inspeccion creada previamente
     */
    public function detalle_post() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE ) {
            if (!$this->post('valor_campo')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $valor_campo = $this->post('valor_campo');
            $flag = $this->inspeccion_model->get($valor_campo[0]['inspeccion_id']);
            if (!is_null($flag)) {
                $flagdetalle = $this->valor_campo_model->saveDetalle($valor_campo,$usuario_token->empresa_id);
            }
            if (!is_null($flagdetalle)) {
                $this->response(array('detalle' => 'Registrado'), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status' => FALSE, 'error' => 'Algo se ha roto en el servidor...'), REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    /*
    Metodo POST foto:
    recibe un objeto fotos_auto  asociado a una inspeccion junto con sus comentario,
    y los registra en una inspeccion creada previamente
     */
    public function foto_post() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE ) {
            if (!$this->post('fotos_auto')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $foto = $this->post('fotos_auto');
            $foto['empresa_id']  = $usuario_token->empresa_id;
            $flag = $this->inspeccion_model->get($foto['inspeccion_id']);
            if (!is_null($flag)) {
                $flagfotos = $this->fotos_auto_model->save($foto);
            }
            if (!is_null($flagfotos)) {
                $this->response(array('fotos_auto' => 'Registrado'), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status' => FALSE, 'error' => 'Algo se ha roto en el servidor...'), REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    /*
    Metodo POST album:
    recibe un arreglo de fotografias, string Base 64, asociadas a una inspeccion junto con sus observaciones, y los registra en una inspeccion creada previamente
     */
    public function album_post() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE ) {
            if (!$this->post('fotos_auto')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $fotos = $this->post('fotos_auto');
            $flag = $this->inspeccion_model->get($fotos[0]['inspeccion_id']);
            if (!is_null($flag)) {
                $flagfotos = $this->fotos_auto_model->saveDetalle($fotos);
            }
            if (!is_null($flagfotos)) {
                $this->response(array('fotos_auto' => 'Registrado'), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status' => FALSE, 'error' => 'Algo se ha roto en el servidor...'), REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function index_put($id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE ) {
            if (!$this->put('inspeccion')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $update = $this->inspeccion_model->update($id, $this->put('inspeccion'));
            if (!is_null($update)) {
                $this->response(array('status' => TRUE, 'inspeccion' => 'inspeccion actualizado!'), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status' => FALSE, 'error' => 'Algo se ha roto en el servidor...'), REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    /*
      public function index_delete($id) {
      //validar token
      header("Access-Control-Allow-Origin: *");
      $is_valid_token = $this->authorization_token->validateToken();
      $usuario_token = $this->authorization_token->userData();
      if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,3,4,5)))) {
      if (!$id) {
      $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
      }
      $delete = $this->inspeccion_model->delete($id);
      if (!is_null($delete)) {
      $this->response(array('status'=>TRUE,'inspeccion' => 'inspeccion eliminado!'), REST_Controller::HTTP_OK);
      } else {
      $this->response(array('status'=>FALSE,'error'=> 'Algo se ha roto en el servidor...'), REST_Controller::HTTP_BAD_REQUEST);
      }
      } else {
      if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
      $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
      } else {
      $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
      }
      }
      }
     * 
     */

    private function pdf($idInspeccion) {
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

    private function correo($idInspeccion) {
        $inspeccion = $this->inspeccion_model->get($idInspeccion);
        if (!is_null($inspeccion)) {
            $cliente= $this->solicitud_model->get($inspeccion['solicitud_id'])['email'];
            $empresa= $this->empresa_model->get($inspeccion['empresa_id'])['empresa'];

            $archivo = 'anexos/'.$empresa.'/informes/Inspeccion-' . $idInspeccion . '.pdf';
        
            if (file_exists($archivo)) {

                
                $config = array();
                $config['smtp_host'] = 'smtp.gmail.com';
                $config['smtp_port'] = '587';
                $config['smtp_user'] = 'optimuscarcorreo@gmail.com';
                $config['_smtp_auth'] = TRUE;
                $config['smtp_pass'] = 'ko8L1fR45i';
                $config['smtp_crypto'] = 'tls';
                $config['protocol'] = 'smtp';
                $config['mailpath'] = "/usr/bin/sendmail";
                $config['mailtype'] = 'html';
                $config['send_multipart'] = FALSE;
                $config['charset'] = 'utf-8';
                $config['wordwrap'] = TRUE;

                $mensaje="<html>
                        <head></head>
                        <body>
                            <p>
                                Este mail es generado de manera automática, por favor NO RESPONDER.<br>
                            </p>
                            <p>Atentamente. Equipo de Optimuscar</p>
                        </body>
                        </html>";
                
                $this->load->library('email');
                $this->email->initialize($config);
                $this->email->set_newline("\r\n");
                $this->email->from('optimuscarcorreo@gmail.com', 'OptimusCar');
                $this->email->to($cliente);
                $this->email->subject('PDF formulario');
                $this->email->message($mensaje);
                $this->email->attach($archivo);
                $this->email->send();

                //var_dump($this->email->print_debugger());
            } else {
                $this->response(array('error' => 'Inspeción Inválida'), REST_Controller::HTTP_NOT_FOUND);
            }
        }else{
            $this->response(array('error' => 'Inspeción Inválida'), REST_Controller::HTTP_NOT_FOUND);
        }
        
    }

}
