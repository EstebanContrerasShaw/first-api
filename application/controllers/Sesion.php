<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Sesion extends REST_Controller{

    public function __construct() {
        parent::__construct();
        $this->load->model('sesion_model');
    }
    
    public function login_post(){
        header("Access-Control-Allow-Origin: *");
        if (!$this->post('login')) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        else
        {
            // Load Login Function
            $output = $this->sesion_model->login($this->post('login'));
            if (!empty($output) AND $output != FALSE)
            {
                // Load Authorization Token Library
                $this->load->library('Authorization_Token');
                // Generate Token
                $token_data['id'] = $output['id'];
                $token_data['email'] = $output['email'];
                $token_data['tipo'] = $output['usuario_tipo_id'];
                $token_data['empresa_id'] = $output['empresa_id'];
                $token_data['time'] = time();
                $user_token = $this->authorization_token->generateToken($token_data);
                $return_data = $output;
                $return_data['token'] = $user_token;
                /*
                $return_data = [
                    'id' => $output['id'],
                    'nombres' => $output['nombres'],
                    'apellidos' => $output['apellidos'],
                    'email' => $output['email'],
                    'token' => $user_token,
                ];
                 */
                // Login Success
                $message = [
                    'status' => true,
                    'data' => $return_data,
                    'message' => "Login exitoso"
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else
            {
                // Login Error
                $message = [
                    'status' => FALSE,
                    'message' => "Usuario y/o contraseña invalida"
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    
    }
    
}