<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Vendedor_tipo extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('solicitud_tipo_model');
        $this->load->library('Authorization_Token');
    }

    public function index_get() {
        $solicitud_tipo = $this->solicitud_tipo_model->get();
        if (!is_null($solicitud_tipo)) {
            $this->response(array('status'=>TRUE,'solicitud_tipo' => $solicitud_tipo), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status'=>FALSE,'error' => 'No hay solicitud_tipos en la base de datos...'), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function find_get($id) {
        if (!$id) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $solicitud_tipo = $this->solicitud_tipo_model->get($id);
        if (!is_null($solicitud_tipo)) {
            $this->response(array('status'=>TRUE,'solicitud_tipo' => $solicitud_tipo), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status'=>FALSE,'error' => 'solicitud_tipo no encontrado...'), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_post() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,5)))) {
            if (!$this->post('solicitud_tipo')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $id = $this->solicitud_tipo_model->save($this->post('solicitud_tipo'));
            if (!is_null($id)) {
                $this->response(array('status'=>TRUE,'solicitud_tipo' => $id), REST_Controller::HTTP_OK);
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
            if (!$this->put('solicitud_tipo')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $update = $this->solicitud_tipo_model->update($id, $this->put('solicitud_tipo'));
            if (!is_null($update)) {
                $this->response(array('status'=>TRUE,'solicitud_tipo' => 'solicitud_tipo actualizado!'), REST_Controller::HTTP_OK);
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,5)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $delete = $this->solicitud_tipo_model->delete($id);
            if (!is_null($delete)) {
                $this->response(array('status'=>TRUE,'solicitud_tipo' => 'solicitud_tipo eliminado!'), REST_Controller::HTTP_OK);
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
