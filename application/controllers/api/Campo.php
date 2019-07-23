<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Campo extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('campo_model');
        $this->load->model('categoria_model');
        $this->load->library('Authorization_Token');
    }

    public function index_get($empresa_id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE) {
            $campo = $this->campo_model->get($empresa_id);
            if (!is_null($campo)) {
                $this->response(array('status'=>TRUE,'campo' => $campo), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status'=>FALSE,'error' => 'No hay campos en la base de datos...'), REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

   /* public function find_get($id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,3,4,5)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $campo = $this->campo_model->get($id);
            if (!is_null($campo)) {
                $this->response(array('status'=>TRUE,'campo' => $campo), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status'=>FALSE,'error' => 'campo no encontrado...'), REST_Controller::HTTP_NOT_FOUND);
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
    Metodo GET findCategoria() :
    recibe el id de una categoria y responde con lo campos asociados a dicha categoria
     */
    /*public function findCategoria_get($id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,3,4,5)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $campo = $this->campo_model->getPorCategoria($id);
            if (!is_null($campo)) {
                $this->response(array('status'=>TRUE,'campo' => $campo), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status'=>FALSE,'error' => 'campo no encontrado...'), REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            if (empty($is_valid_token) || $is_valid_token['status'] === FALSE) {
                $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $this->response(['status' => FALSE, 'message' => 'Invalid user'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }*/
    

    /*public function index_post() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,5)))) {
            if (!$this->post('campo')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $id = $this->campo_model->save($this->post('campo'));
            if (!is_null($id)) {
                $this->response(array('status'=>TRUE,'campo' => $id), REST_Controller::HTTP_OK);
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
    

    /*

    --NO Usado--
    Metodo POST categoria :

    recibe un objeto cuya clave es 'campo' y su valor es un arreglo de 
    campos (nombre,orden,importancia,categoria_id) correspondientes a una categoria, 
    la cual existe previamente. En caso de exito retorna el numero de campos ingresados

     */
    /*public function categoria_post() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,5)))) {
            if (!$this->post('campo')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $cant = $this->campo_model->saveCategoria($this->post('campo'));
            if (!is_null($cant)) {
                $this->response(array('status'=>TRUE,'campo' => $cant.' campos ingresados'), REST_Controller::HTTP_OK);
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
    

    /*
    Metodo POST formulario :

    recibe un objeto cuya clave es 'formulario' y su valor es un arreglo de campos 
    (nombre,orden,importancia,categoria_id) cambia el estado de los campos y categorias previos 
    a inactivos, y crea nuevas categorias y campos. En caso de exito retorn el numero de campos ingresados

     */
    /*public function full_post() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,5)))) {
            if (!$this->post('formulario')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $this->campo_model->deletefull($usuario_token->empresa_id);
            $cant = $this->campo_model->savefull($this->post('formulario'), $usuario_token->empresa_id);
            if (!is_null($cant)) {
                $this->response(array('status'=>TRUE,'campo' => $cant.' campos ingresados'), REST_Controller::HTTP_OK);
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

    /*public function index_put($id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,5)))) {
            if (!$this->put('campo')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $update = $this->campo_model->update($id,$this->put('campo'));
            if (!is_null($update)) {
                $this->response(array('status'=>TRUE,'campo' => 'campo actualizado!'), REST_Controller::HTTP_OK);
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
            $delete = $this->campo_model->delete($id);
            if (!is_null($delete)) {
                $this->response(array('status'=>TRUE,'campo' => 'campo eliminado!'), REST_Controller::HTTP_OK);
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
