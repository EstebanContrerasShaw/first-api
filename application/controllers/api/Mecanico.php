<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Mecanico extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('usuario_model');
        $this->load->model('mecanico_model');
        $this->load->library('Authorization_Token');
    }

    public function index_get() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 5)))) {
            $mecanico = $this->mecanico_model->get();
            if (!is_null($mecanico)) {
                $this->response(array('status' => TRUE, 'mecanico' => $mecanico), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status' => FALSE, 'error' => 'No hay mecanicos en la base de datos...'), REST_Controller::HTTP_NOT_FOUND);
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 5)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $mecanico = $this->mecanico_model->get($id);
            if (!is_null($mecanico)) {
                $this->response(array('status' => TRUE, 'mecanico' => $mecanico), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status' => FALSE, 'error' => 'mecanico no encontrado...'), REST_Controller::HTTP_NOT_FOUND);
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 5)))) {
            if (!$this->post('mecanico')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $mecanico = $this->post('mecanico');

            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,32}$/', $mecanico['password'])) {
                $this->response(array('status' => FALSE, 'error' => 'Contraseña no valida'), REST_Controller::HTTP_BAD_REQUEST);
            }
            $id = $this->mecanico_model->save($mecanico);
            if (!is_null($id)) {
                if ($id == (-1)) {
                    $this->response(array('status' => FALSE, 'error' => 'El Rut ingresado ya existe'), REST_Controller::HTTP_BAD_REQUEST);
                } else if ($id == (-2)) {
                    $this->response(array('status' => FALSE, 'error' => 'El Correo/Email ingresado ya existe'), REST_Controller::HTTP_BAD_REQUEST);
                } else {
                    $this->response(array('status' => TRUE, 'mecanico' => $id), REST_Controller::HTTP_OK);
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

    public function index_put($id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (((in_array($usuario_token->tipo, array(3, 4))) && $usuario_token->id == $id) || (in_array($usuario_token->tipo, array(1, 5))) )) {
            if (!$this->put('mecanico')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $mecanico = $this->put('mecanico');
            $update = $this->mecanico_model->update($id, $mecanico);
            if (!is_null($update)) {
                $this->response(array('status' => TRUE, 'mecanico' => 'mecanico actualizado!'), REST_Controller::HTTP_OK);
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

    public function estado_post() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 5)))) {
            if (!$this->post('mecanico')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }

            $padre = $this->mecanico_model->actualizarEstados($this->post('mecanico'));
            if (!is_null($padre)) {
                $this->response(array('status' => TRUE, 'mecanico' => 'mecanicos Actualizados!'), REST_Controller::HTTP_OK);
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
    public function pagado_post() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(5)))) {
            if (!$this->post('mecanico')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }

            $flag = $this->mecanico_model->resetPagados($this->post('mecanico'));
            if (!is_null($flag)) {
                $this->response(array('status' => TRUE, 'mecanico' => 'Mecanicos Actualizados!'), REST_Controller::HTTP_OK);
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

    public function activar_put($id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 5)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }

            $padre = $this->mecanico_model->estadoOn($id);
            if (!is_null($padre)) {
                $this->response(array('status' => TRUE, 'mecanico' => 'mecanico eliminado!'), REST_Controller::HTTP_OK);
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

    public function index_delete($id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 5)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }

            $padre = $this->mecanico_model->estadoOff($id);
            if (!is_null($padre)) {
                $this->response(array('status' => TRUE, 'mecanico' => 'mecanico eliminado!'), REST_Controller::HTTP_OK);
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

}
