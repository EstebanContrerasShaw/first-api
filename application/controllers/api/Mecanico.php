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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(3)))) {
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 3)))) {
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

    public function empresa_get($empresa_id) {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1,3)))) {
            $mecanico = $this->mecanico_model->getByEmpresa($empresa_id);
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

    public function index_post() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 3)))) {
            if (!$this->post('mecanico')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $mecanico = $this->post('mecanico');
            if( !$this->validate_rut($mecanico['rut'].'-'.$mecanico['dv'])){
                return $this->response(array('error'=> 'Rut inválido'), 
                    REST_Controller::HTTP_BAD_REQUEST);
            }
            if($this->rut_exist($mecanico['rut'].'-'.$mecanico['dv'])){
                return $this->response(array('error'=> 'El Rut ya existe '), 
                    REST_Controller::HTTP_BAD_REQUEST);
            }
            if($this->email_exist($mecanico['email'])){
                return $this->response(array('error'=> 'Email ya existe.'), 
                    REST_Controller::HTTP_BAD_REQUEST);
            }
            if($this->validate_password($mecanico['password'])){
                return $this->response(array('error'=> 'Contraseña inválida.'), 
                    REST_Controller::HTTP_BAD_REQUEST);
            }
            $id = $this->mecanico_model->save($mecanico);
            if (!is_null($id)) {
                $this->response(array('status'=>TRUE,'mecanico' => $id), REST_Controller::HTTP_OK);
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (((in_array($usuario_token->tipo, array(2))) && $usuario_token->id == $id) || (in_array($usuario_token->tipo, array(1, 3))) )) {
            if (!$this->put('mecanico')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $mecanico = $this->put('mecanico');
            if($this->email_exist_update($id, $mecanico['email'])){
                return $this->response(array('error'=> 'Email ya existe.'), 
                    REST_Controller::HTTP_BAD_REQUEST);
            }
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 3)))) {
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(3)))) {
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 3)))) {
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(1, 3)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }

            $padre = $this->mecanico_model->estadoOut($id);
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



    // funciones Auxiliares de validacion
    // 
    
    private function validate_rut($rut)
    {
        $rut = preg_replace('/[^k0-9]/i', '', $rut);
        $dv  = substr($rut, -1);
        $numero = substr($rut, 0, strlen($rut)-1);
        $i = 2;
        $suma = 0;
      
        foreach(array_reverse(str_split($numero)) as $v)
        {
             if($i==8)
                $i = 2;
            $suma += $v * $i;
            ++$i;
        }
        $dvr = 11 - ($suma % 11);

        if($dvr == 11)
            $dvr = 0;
        if($dvr == 10)
            $dvr = 'K';
        return $dvr == strtoupper($dv);
            
    }

    public function rut_exist($rut){
        return $this->usuario_model->rutExist($rut);        
        
    }
    public function email_exist($email){
        return $this->usuario_model->exist($email);
    }
    public function email_exist_update($id, $email){
        return $this->usuario_model->existUpdate($id, $email);
    }

    private function validate_password($password)
    {
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,32}$/', $password )) {
            return TRUE;
        }
        return FALSE ;
    }

}
