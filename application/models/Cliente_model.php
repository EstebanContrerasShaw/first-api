<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente_model extends CI_Model{
     public function __construct() {
         parent::__construct();
     }
     
     public function get($id = null)
    {
        if (!is_null($id)) {
            $conid="SELECT u.id,u.rut,u.dv,u.nombres,u.apellidos,u.email,u.celular,u.estado,u.registro_fecha_hora,u.usuario_tipo_id FROM usuario as u, cliente as c WHERE c.usuario_id=u.id AND u.id=$id";
            $query = $this->db->query($conid);
            //$query = $this->db->select('*')->from('cliente')->where('usuario_id', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }
            return null;
        }
        $sentencia="SELECT  u.id,u.rut,u.dv,u.nombres,u.apellidos,u.email FROM usuario as u, cliente as c WHERE c.usuario_id=u.id";
        $query = $this->db->query($sentencia);
        //$query = $this->db->select('*')->from('cliente')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }
    
    public function save($id,$cliente)
    {
        $existe=$this->db->select('usuario_id')->from('cliente')->where('usuario_id',$id)->get();
        if(!is_null($existe)){
            return $id;
        }
        $this->db->set($this->_setCliente($id,$cliente))->insert('cliente');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    public function update($id,$cliente)
    {
        $this->db->set($this->_setCliente($id,$cliente))->where('usuario_id', $id)->update('cliente');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }

    public function getMailCliente($id)
    {
        $query=$this->db->query("SELECT email from usuario WHERE id=(SELECT cliente_usuario_id FROM `solicitud` WHERE id=(SELECT solicitud_id from inspeccion where id=$id))");
        if ($this->db->affected_rows() === 1) {
            $email=$query->row_array();
            return $email['email'];
        }
        return null;
    }

    


    
    /*public function delete($id)
    {
        $this->db->where('usuario_id', $id)->delete('cliente');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }*/
    
    private function _setCliente($id,$cliente)
    {
        
        return array(
            'usuario_id' => $id
        );
    }
}
