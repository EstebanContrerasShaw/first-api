<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Fotos_auto extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('fotos_auto_model');
        $this->load->library('Authorization_Token');
    }

    public function index_get() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,5)))) {
            $fotos_auto = $this->fotos_auto_model->get();
            if (!is_null($fotos_auto)) {
                $this->response(array('status'=>TRUE,'fotos_auto' => $fotos_auto), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status'=>FALSE,'error' => 'No hay fotos_autos en la base de datos...'), REST_Controller::HTTP_NOT_FOUND);
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,5)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $fotos_auto = $this->fotos_auto_model->get($id);
            if (!is_null($fotos_auto)) {
                $this->response(array('status'=>TRUE,'fotos_auto' => $fotos_auto), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status'=>FALSE,'error' => 'fotos_auto no encontrado...'), REST_Controller::HTTP_NOT_FOUND);
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
    Metodo GET inpseccion :
    recibe el id de una inspeccion y retorna las fotografias del auto asociadas a dicha inspeccion 
    en formato string Base 64
     */
    public function inspeccion_get($id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,5)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $fotos_auto = $this->fotos_auto_model->getInspeccion($id);
            if (!is_null($fotos_auto)) {
                $this->response(array('status'=>TRUE,'fotos_auto' => $fotos_auto), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status'=>FALSE,'error' => 'Inspeccion sin registros fotograficos...'), REST_Controller::HTTP_NOT_FOUND);
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(3,4,5)))) {
            if (!$this->post('fotos_auto')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $foto_auto = $this->post('fotos_auto');
            $id = $this->fotos_auto_model->save($foto_auto);
            if (!is_null($id)) {
                $this->response(array('status'=>TRUE,'fotos_auto' => $id), REST_Controller::HTTP_OK);
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

    /*
      public function index_put($id) {
      //validar token
      header("Access-Control-Allow-Origin: *");
      $is_valid_token = $this->authorization_token->validateToken();
      $usuario_token = $this->authorization_token->userData();
      if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,3,4)))) {
      if (!$this->put('fotos_auto')) {
      $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
      }
      $fotos_auto = $this->put('fotos_auto');
      $update = $this->fotos_auto_model->update($id, $fotos_auto);
      if (!is_null($update)) {
      $this->response(array('status'=>TRUE,'fotos_auto' => 'fotos_auto actualizado!'), REST_Controller::HTTP_OK);
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

      public function index_delete($id) {
      //validar token
      header("Access-Control-Allow-Origin: *");
      $is_valid_token = $this->authorization_token->validateToken();
      $usuario_token = $this->authorization_token->userData();
      if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,5)))) {
      if (!$id) {
      $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
      }
      $delete = $this->fotos_auto_model->delete($id);
      if (!is_null($delete)) {
      $this->response(array('status'=>TRUE,'fotos_auto' => 'fotos_auto eliminado!'), REST_Controller::HTTP_OK);
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
     * 
     */
}
