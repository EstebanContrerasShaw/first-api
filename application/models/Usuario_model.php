<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get($id = null) {
        if (!is_null($id)) {

            //otra forma de ejecutar queries:
            /*
             * $sentencia='select * from usuario';
             * $this->db->query($sentencia);
             */
            $query = $this->db->select('*')->from('usuario')->where('id', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }
            return null;
        }
        $query = $this->db->select('*')->from('usuario')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }

    public function save($usuario) {
        $queryRut = $this->db->select('id')->from('usuario')->where('rut', $usuario['rut'])->get();
        if ($queryRut->num_rows() === 1) {
            $idviejo=$queryRut->row_array()['id'];
            $existe=$this->update($idviejo,$usuario);
            if(!is_null($existe)){
                return $idviejo;    
            }
        }
        $queryMail = $this->db->select('*')->from('usuario')->where('email', $usuario['email'])->get();
        if ($queryMail->num_rows() === 1) {
            return (-2);
        }
        $this->db->set($this->_setUsuario($usuario))->insert('usuario');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }

    public function update($id,$usuario)
    {
        $this->db->set($this->_setUsuarioUpdate($usuario))->where('id', $id)->update('usuario');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }

/*    public function update($id,$usuario)
    {
        $query = $this->db->select('*')->from('usuario')->where('id', $id)->get();
        $rows = $this->db->update_batch('usuario', array($this->_setUsuarioUpdate($id,$usuario)), 'id');
        if ($rows === 1) {
            return true;
        }
        return null;
    }*/


    public function delete($id) {
        $this->db->where('id', $id)->delete('usuario');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }



    public function exist($email) {
        $result   = $this->db->get_where('usuario', array('email' => $email));
        return ($result->num_rows() > 0)? true: false;
    }
    public function existUpdate($id, $email) {
        $usuario = $this->get($id);
        if(strcmp( $email , $usuario['email']) == 0){
            return false;
        }else{
            $result   = $this->db->get_where('usuario', array('email' => $email));
            return ($result->num_rows() > 0)? true: false;
        }
    }
    public function rutExist($rut) {
        $rut = preg_replace('/[^k0-9]/i', '', $rut);
        $rut = substr($rut, 0, strlen($rut)-1);
        $result   = $this->db->get_where('usuario', array('rut' => $rut));
        return ($result->num_rows() > 0)?  true: false;
    }


    private function _setUsuario($usuario) {
        return array(
            'rut' => $usuario['rut'],
            'dv' => $usuario['dv'],
            'nombres' => $usuario['nombres'],
            'apellidos' => $usuario['apellidos'],
            'email' => $usuario['email'],
            'celular' => $usuario['celular'],
            'usuario_tipo_id' => $usuario['usuario_tipo_id']
        );
    }

    private function _setUsuarioUpdate($usuario) {
        /* 'rut' => $usuario['rut'],
          'dv' => $usuario['dv'], */
        return array(
            'nombres' => $usuario['nombres'],
            'apellidos' => $usuario['apellidos'],
            'email' => $usuario['email'],
            'celular' => $usuario['celular'],
            'estado' => $usuario['estado'],
            'usuario_tipo_id' => $usuario['usuario_tipo_id']
        );
    }

}
