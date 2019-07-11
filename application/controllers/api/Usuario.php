<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Usuario extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('usuario_model');
        $this->load->model('admin_model');
        $this->load->model('cliente_model');
        $this->load->model('mecanico_model');
        $this->load->library('Authorization_Token');
    }

    public function index_get() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,3)))) {
            $usuario = $this->usuario_model->get();
        if (!is_null($usuario)) {
            $this->response(array('status'=>TRUE,'usuario' => $usuario), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status'=>FALSE,'error' => 'No hay usuarios en la base de datos...'), REST_Controller::HTTP_NOT_FOUND);
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,3)))) {
            if (!$id) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $usuario = $this->usuario_model->get($id);
        if (!is_null($usuario)) {
            $this->response(array('status'=>TRUE,'usuario' => $usuario), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status'=>FALSE,'error' => 'Usuario no encontrado...'), REST_Controller::HTTP_NOT_FOUND);
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
    public function index_post() {
        if (!$this->post('usuario')) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $usuario = $this->post('usuario');
        $id = $this->usuario_model->save($usuario);
        if (!is_null($id)) {//validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,5)))) {
            
            if ($usuario['tipo'] === 'admin') {
                $this->admin_model->save($id, $usuario);
            }
            if ($usuario['tipo'] === 'cliente') {
                $this->cliente_model->save($id, $usuario);
            }//validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,5)))) {
            
            if ($usuario['tipo'] === 'mecanico') {
                $this->mecanico_model->save($id, $usuario);
            }
            $this->response(array('status'=>TRUE,'usuario' => $id), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status'=>FALSE,'error'=> 'Algo se ha roto en el servidor...'), REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function index_put($id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,5)))) {
            if (!$this->put('usuario')) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $usuario = $this->usuario_model->get($id);
        if ($usuario['tipo'] === 'admin') {
            $foranea = $this->admin_model->update($id, $usuario);
        }
        if ($usuario['tipo'] === 'cliente') {
            $foranea = $this->cliente_model->update($id, $usuario);
        }
        if ($usuario['tipo'] === 'mecanico') {
            $foranea = $this->mecanico_model->update($id, $usuario);
        }
        $update = $this->usuario_model->update($id, $this->put('usuario'));
        if (!is_null($update) && !is_null($foranea)) {
            $this->response(array('status'=>TRUE,'usuario' => 'Usuario actualizado!'), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status'=>FALSE,'error'=> 'Algo se ha roto en el servidor...'), REST_Controller::HTTP_BAD_REQUEST);
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
        $usuario = $this->usuario_model->get($id);
        if ($usuario['tipo'] === 'admin') {
            $foranea = $this->admin_model->delete($id);
        }
        if ($usuario['tipo'] === 'cliente') {
            $foranea = $this->cliente_model->delete($id);
        }
        if ($usuario['tipo'] === 'mecanico') {
            $foranea = $this->mecanico_model->delete($id);
        }

        $delete = $this->usuario_model->delete($id);
        if (!is_null($delete) && !is_null($foranea)) {
            $this->response(array('status'=>TRUE,'usuario' => 'usuario eliminado!'), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status'=>FALSE,'error'=> 'Algo se ha roto en el servidor...'), REST_Controller::HTTP_BAD_REQUEST);
        }
    }*/

}
