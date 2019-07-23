<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Categoria extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('categoria_model');
        $this->load->library('Authorization_Token');
    }

    public function index_get($empresa_id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE ) {
            $categoria = $this->categoria_model->get($empresa_id);
            if (!is_null($categoria)) {
                $this->response(array('status'=>TRUE,'categoria' => $categoria), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status'=>FALSE,'error' => 'No hay categorias en la base de datos...'), REST_Controller::HTTP_NOT_FOUND);
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE ) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $categoria = $this->categoria_model->get($id);
            if (!is_null($categoria)) {
                $this->response(array('status'=>TRUE,'categoria' => $categoria), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status'=>FALSE,'error' => 'categoria no encontrado...'), REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function formulario_get($empresa_id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE ) {
            $categoria = $this->categoria_model->getFormulario($empresa_id);
            if (!is_null($categoria)) {
                $this->response(array('status'=>TRUE,'categoria' => $categoria), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status'=>FALSE,'error' => 'categoria no encontrado...'), REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function full_post($empresa_id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,3)))) {
            if (!$this->post('formulario')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $this->categoria_model->deletefull($empresa_id);
            $cant = $this->categoria_model->savefull($this->post('formulario'), $empresa_id);
            if (!is_null($cant)) {
                $this->response(array('status'=>TRUE,'formulario' => 'Formulario registrado con  '.$cant.'  campos'), REST_Controller::HTTP_OK);
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


    /*public function index_post() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,5)))) {
            if (!$this->post('categoria')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $id = $this->categoria_model->save($this->post('categoria'));
            if (!is_null($id)) {
                $this->response(array('status'=>TRUE,'categoria' => $id), REST_Controller::HTTP_OK);
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

    public function index_put($id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,5)))) {
            if (!$this->put('categoria')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $update = $this->categoria_model->update($id,$this->put('categoria'));
            if (!is_null($update)) {
                $this->response(array('status'=>TRUE,'categoria' => 'categoria actualizado!'), REST_Controller::HTTP_OK);
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
            $delete = $this->categoria_model->delete($id);
            if (!is_null($delete)) {
                $this->response(array('status'=>TRUE,'categoria' => 'categoria eliminado!'), REST_Controller::HTTP_OK);
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
    }*/

}
