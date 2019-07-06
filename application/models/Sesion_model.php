<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sesion_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin_model');
        $this->load->model('mecanico_model');
    }

    public function login($user) {
        $email = $user['email'];
        $password = $user['password'];
        $sentencia = "SELECT * FROM usuario WHERE estado=1 AND email='" . $email . "'";
        $query = $this->db->query($sentencia);
        if ($this->db->affected_rows() == 1) {
            $arreglo = $query->row_array();
            return $this->loginHijo($arreglo, $password);
        }
        return FALSE;
    }

    private function loginHijo($arreglo, $password) {
        //$this->load->library('encryption');
        if ($arreglo['usuario_tipo_id'] == 1 || $arreglo['usuario_tipo_id'] == 5) {
            $id = $arreglo['id'];
            $hijoarray = $this->admin_model->get($id);
            $clave = $hijoarray['password'];
            if (password_verify($password, $clave) && $arreglo['estado'] == 1) {
                return array_merge($arreglo, $hijoarray);
            } else {
                return FALSE;
            }
        }
        if ($arreglo['usuario_tipo_id'] == 3 || $arreglo['usuario_tipo_id'] == 4) {
            $id = $arreglo['id'];
            $hijoarray = $this->mecanico_model->get($id);
            $clave = $hijoarray['password'];
            if (password_verify($password, $clave) && $arreglo['estado'] == 1) {
                return array_merge($arreglo, $hijoarray);
            }else{
                return FALSE;
            }
        }
    }

}
