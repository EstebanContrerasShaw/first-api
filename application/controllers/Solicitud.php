<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Solicitud extends REST_Controller{

public function __construct() {
        parent::__construct();
        $this->load->model('solicitud_model');
        $this->load->model('solicitud_vip_model');
        $this->load->model('solicitud_programada_model');
        $this->load->model('solicitud_express_model');
        $this->load->library('Authorization_Token');
    }

    public function index_get(){
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,5)))) {
            $solicitud = $this->solicitud_model->get();
        if (!is_null($solicitud)) {
            $this->response(array('status'=>TRUE,'solicitud' => $solicitud), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status'=>FALSE,'error' => 'No hay solicituds en la base de datos...'), REST_Controller::HTTP_NOT_FOUND);
        }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }
    public function find_get($id){
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,5)))) {
            if (!$id) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $solicitud = $this->solicitud_model->get($id);
        if (!is_null($solicitud)) {
            $this->response(array('status'=>TRUE,'solicitud' => $solicitud), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status'=>FALSE,'error' => 'solicitud no encontrado...'), REST_Controller::HTTP_NOT_FOUND);
        }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }
    
    
    /*public function index_post(){
        if (!$this->post('solicitud')) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $solicitud = $this->post('solicitud');
        $id = $this->solicitud_model->save($solicitud);
        if (!is_null($id)) {
            if ($solicitud['tipo'] === 'programada') {
                $this->solicitud_programada_model->save($id, $solicitud);
            }
            if ($solicitud['tipo'] === 'express') {
                $this->solicitud_express_model->save($id, $solicitud);
            }
            if ($solicitud['tipo'] === 'vip') {
                $this->solicitud_vip_model->save($id, $solicitud);
            }
            $this->response(array('status'=>TRUE,'solicitud' => $id), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status'=>FALSE,'error'=> 'Algo se ha roto en el servidor...'), REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function index_put($id){
        if (!$this->put('solicitud')) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $solicitud = $this->solicitud_model->get($id);
        if ($solicitud['tipo'] === 'programada') {
            $foranea = $this->solicitud_programada_model->update($id, $solicitud);
        }
        if ($solicitud['tipo'] === 'express') {
            $foranea = $this->solicitud_express_model->update($id, $solicitud);
        }
        if ($solicitud['tipo'] === 'vip') {
            $foranea = $this->solicitud_vip_model->update($id, $solicitud);
        }
        $update = $this->solicitud_model->update($id,$this->put('solicitud'));
        if (!is_null($update) && !is_null($foranea)) {
            $this->response(array('status'=>TRUE,'solicitud' => 'solicitud actualizado!'), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status'=>FALSE,'error'=> 'Algo se ha roto en el servidor...'), REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function index_delete($id){
        if (!$id) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $solicitud = $this->solicitud_model->get($id);
        if ($solicitud['tipo'] === 'programada') {
            $foranea = $this->solicitud_programada_model->delete($id, $solicitud);
        }
        if ($solicitud['tipo'] === 'express') {
            $foranea = $this->solicitud_express_model->delete($id, $solicitud);
        }
        if ($solicitud['tipo'] === 'vip') {
            $foranea = $this->solicitud_vip_model->delete($id, $solicitud);
        }
        $delete = $this->solicitud_model->delete($id,$this->put('solicitud'));
        if (!is_null($delete) && !is_null($foranea)) {
            $this->response(array('status'=>TRUE,'solicitud' => 'solicitud eliminado!'), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status'=>FALSE,'error'=> 'Algo se ha roto en el servidor...'), REST_Controller::HTTP_BAD_REQUEST);
        }
    }*/
    
}