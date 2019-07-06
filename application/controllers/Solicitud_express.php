<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Solicitud_express extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('solicitud_model');
        $this->load->model('solicitud_express_model');
        $this->load->model('cliente_model');
        $this->load->model('admin_model');
        $this->load->model('cancelacion_model');
        $this->load->model('vendedor_model');
        $this->load->library('Authorization_Token');
    }

    public function index_get() {
        $solicitud_express = $this->solicitud_express_model->get();
        if (!is_null($solicitud_express)) {
            $this->response(array('status'=>TRUE,'solicitud_express' => $solicitud_express), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status'=>FALSE,'error' => 'No hay solicitudes_express en la base de datos...'), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function find_get($id) {
        if (!$id) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $solicitud_express = $this->solicitud_express_model->get($id);
        $solicitud_express['cliente_usuario_id']=$this->cliente_model->get($solicitud_express['cliente_usuario_id']);
        $solicitud_express['vendedor_id']=$this->vendedor_model->get($solicitud_express['vendedor_id']);
        $solicitud_express['admin_usuario_id']=$this->admin_model->get($solicitud_express['admin_usuario_id']);
        if(!is_null($solicitud_express['cancelacion_id'])){
                $solicitud_express['cancelacion_id']=$this->cancelacion_model->get($solicitud_express['cancelacion_id']);
            }
        if (!is_null($solicitud_express)) {
            $this->response(array('status'=>TRUE,'solicitud_express' => $solicitud_express), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status'=>FALSE,'error' => 'solicitud_express no encontrado...'), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_post() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 5)))) {
            if (!$this->post('solicitud_express')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $solicitud = $this->post('solicitud_express');
            $id = $this->solicitud_model->save($solicitud);
            if (!is_null($id)) {
                $this->solicitud_express_model->save($id, $solicitud);
                $this->response(array('status'=>TRUE,'solicitud_express' => $id), REST_Controller::HTTP_OK);
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
            if (!$this->put('solicitud_express')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $update = $this->solicitud_express_model->update($id, $this->put('solicitud_express'));
            $padre = $this->solicitud_model->update($id, $this->put('solicitud_express'));
            if (!is_null($update) && !is_null($padre)) {
                $this->response(array('status'=>TRUE,'solicitud_express' => 'solicitud_express actualizado!'), REST_Controller::HTTP_OK);
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

    /*public function index_delete($id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 5)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $delete = $this->solicitud_express_model->delete($id);
            $padre = $this->solicitud_model->delete($id);
            if (!is_null($delete) && !is_null($padre)) {
                $this->response(array('status'=>TRUE,'solicitud_express' => 'solicitud_express eliminado!'), REST_Controller::HTTP_OK);
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