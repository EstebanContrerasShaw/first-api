<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Valor_campo extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('valor_campo_model');
        $this->load->library('Authorization_Token');
    }

    public function index_get() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,3,4,5)))) {
            $valor_campo = $this->valor_campo_model->get();
            if (!is_null($valor_campo)) {
                $this->response(array('status'=>TRUE,'valor_campo' => $valor_campo), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status'=>FALSE,'error' => 'No hay valor_campos en la base de datos...'), REST_Controller::HTTP_NOT_FOUND);
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,3,4,5)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $valor_campo = $this->valor_campo_model->get($id);
            if (!is_null($valor_campo)) {
                $this->response(array('status'=>TRUE,'valor_campo' => $valor_campo), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status'=>FALSE,'error' => 'valor_campo no encontrado...'), REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }
    
    public function inspeccion_get($id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,3,4,5)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $valor_campo = $this->valor_campo_model->getPorInspeccion($id);
            if (!is_null($valor_campo)) {
                $this->response(array('status'=>TRUE,'valor_campo' => $valor_campo), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status'=>FALSE,'error' => 'valores no encontrados...'), REST_Controller::HTTP_NOT_FOUND);
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,3,4,5)))) {
            if (!$this->post('valor_campo')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $id = $this->valor_campo_model->save($this->post('valor_campo'));
            if (!is_null($id)) {
                $this->response(array('status'=>TRUE,'valor_campo' => $id), REST_Controller::HTTP_OK);
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
    
    public function detalle_post() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,3,4,5)))) {
            if (!$this->post('valor_campo')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $flag = $this->valor_campo_model->saveDetalle($this->post('valor_campo'));
            if (!is_null($flag)) {
                $this->response(array('status'=>TRUE,'valor_campo' =>'Registrado'), REST_Controller::HTTP_OK);
            } else {
                print_r('sin flag');
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,3,4,5)))) {
            if (!$this->put('valor_campo')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $update = $this->valor_campo_model->update($id, $this->put('valor_campo'));
            if (!is_null($update)) {
                $this->response(array('status'=>TRUE,'valor_campo' => 'valor_campo actualizado!'), REST_Controller::HTTP_OK);
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,3,4,5)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $delete = $this->valor_campo_model->delete($id);
            if (!is_null($delete)) {
                $this->response(array('status'=>TRUE,'valor_campo' => 'valor_campo eliminado!'), REST_Controller::HTTP_OK);
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

}
