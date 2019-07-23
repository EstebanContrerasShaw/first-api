<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Admin extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('usuario_model');
        $this->load->model('admin_model');
        $this->load->library('Authorization_Token');
    }

    public function index_get() {
        //validar token
        header("Access-Control-Allow-Origin: *");
        $is_valid_token = $this->authorization_token->validateToken();
        $usuario_token = $this->authorization_token->userData();
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(3)))) {
            $admin = $this->admin_model->get();
            if (!is_null($admin)) {
                $this->response(array('status'=>TRUE,'admin' => $admin), Restserver\Libraries\REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status'=>FALSE,'error' => 'No hay admins en la base de datos...'), REST_Controller::HTTP_NOT_FOUND);
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(3)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $admin = $this->admin_model->get($id);
            if (!is_null($admin)) {
                $this->response(array('status'=>TRUE,'admin' => $admin), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status'=>FALSE,'error' => 'admin no encontrado...'), REST_Controller::HTTP_NOT_FOUND);
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(3)))) {
            if (!$this->post('admin')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $admin = $this->post('admin');
            if( !$this->validate_rut($admin['rut'].'-'.$admin['dv'])){
                return $this->response(array('error'=> 'Rut inválido'), 
                    REST_Controller::HTTP_BAD_REQUEST);
            }
            if($this->rut_exist($admin['rut'].'-'.$admin['dv'])){
                return $this->response(array('error'=> 'El Rut ya existe '), 
                    REST_Controller::HTTP_BAD_REQUEST);
            }
            if($this->email_exist($admin['email'])){
                return $this->response(array('error'=> 'Email ya existe.'), 
                    REST_Controller::HTTP_BAD_REQUEST);
            }
            if($this->validate_password($admin['password'])){
                return $this->response(array('error'=> 'Contraseña inválida.'), 
                    REST_Controller::HTTP_BAD_REQUEST);
            }
            $id = $this->admin_model->save($admin);
            if (!is_null($id)) {
                $this->response(array('status'=>TRUE,'admin' => $id), REST_Controller::HTTP_OK);
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && ((in_array($usuario_token->tipo, array(1)) && $usuario_token->id == $id) || (in_array($usuario_token->tipo, array(3))))) {
            if (!$this->put('admin')) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }

            $admin = $this->put('admin');
            if($this->email_exist_update($id, $admin['email'])){
                return $this->response(array('error'=> 'Email ya existe.'), 
                    REST_Controller::HTTP_BAD_REQUEST);
            }

            $update = null;
            if($usuario_token->tipo == 3){
                $update = $this->admin_model->updateAsSuper($id, $this->put('admin'));
            }
            if($usuario_token->tipo == 1){
                $update = $this->admin_model->update($id, $this->put('admin'));
            }
            if (!is_null($update)) {
                $this->response(array('status'=>TRUE,'admin' => 'admin actualizado!'), REST_Controller::HTTP_OK);
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
        if (!empty($is_valid_token) && $is_valid_token['status'] === TRUE && (in_array($usuario_token->tipo, array(3)))) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $padre = $this->admin_model->estado($id,0);
            if (!is_null($padre)) {
                $this->response(array('status'=>TRUE,'admin' => 'admin eliminado!'), REST_Controller::HTTP_OK);
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
