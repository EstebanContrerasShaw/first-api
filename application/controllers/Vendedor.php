<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Vendedor extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('vendedor_model');
        $this->load->library('Authorization_Token');
    }

    public function index_get() {
        $vendedor = $this->vendedor_model->get();
        if (!is_null($vendedor)) {
            $this->response(array('status'=>TRUE,'vendedor' => $vendedor), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status'=>FALSE,'error' => 'No hay vendedors en la base de datos...'), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function find_get($id) {
        if (!$id && !is_numeric($id)) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $vendedor = $this->vendedor_model->get($id);
        if (!is_null($vendedor)) {
            $this->response(array('status'=>TRUE,'vendedor' => $vendedor), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status'=>FALSE,'error' => 'vendedor no encontrado...'), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /*public function index_options(){
        $this->response(array(), REST_Controller::HTTP_OK);
    }*/

    public function index_post() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 3, 4, 5)))) {
            if (!$this->post('vendedor')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $id = $this->vendedor_model->save($this->post('vendedor'));
            if (!is_null($id)) {
                $this->response(array('status'=>TRUE,'vendedor' => $id), REST_Controller::HTTP_OK);
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 5)))) {
            if (!$this->put('vendedor')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $update = $this->vendedor_model->update($id, $this->put('vendedor'));
            if (!is_null($update)) {
                $this->response(array('status'=>TRUE,'vendedor' => 'vendedor actualizado!'), REST_Controller::HTTP_OK);
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 5)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $delete = $this->vendedor_model->delete($id);
            if (!is_null($delete)) {
                $this->response(array('status'=>TRUE,'vendedor' => 'vendedor eliminado!'), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status'=>FALSE,'error'=> 'Algo se ha roto en el servidor...'), REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
