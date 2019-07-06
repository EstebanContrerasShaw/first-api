<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_tipo_model extends CI_Model{
     public function __construct() {
         parent::__construct();
     }
     
     public function get($id = null)
    {
        if (!is_null($id)) {
            
            $query = $this->db->select('*')->from('usuario_tipo')->where('id', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }
            return null;
        }
        $query = $this->db->select('*')->from('usuario_tipo')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }
    
    public function save($usuario_tipo)
    {
        $this->db->set($this->_setUsuario_tipo($usuario_tipo))->insert('usuario_tipo');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    public function update($id,$usuario_tipo)
    {
        $this->db->set($this->_setUsuario_tipoUpdate($usuario_tipo))->where('id', $id)->update('usuario_tipo');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    /*public function delete($id)
    {
        $this->db->where('id', $id)->delete('usuario_tipo');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
     * 
     */
    private function _setUsuario_tipo($usuario_tipo)
    {
        return array(
            'nombre' => $usuario_tipo['nombre'],
            'descripcion' => $usuario_tipo['descripcion']
        );
    }
    private function _setUsuario_tipoUpdate($usuario_tipo)
    {
        return array(
            'nombre' => $usuario_tipo['nombre'],
            'estado' => $usuario_tipo['estado'],
            'descripcion' => $usuario_tipo['descripcion']
        );
    }
}
