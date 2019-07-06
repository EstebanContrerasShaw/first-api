<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mensaje_model extends CI_Model{
     public function __construct() {
         parent::__construct();
     }
     
     public function get($id = null)
    {
        if (!is_null($id)) {
            
            $query = $this->db->select('*')->from('mensaje')->where('id', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }
            return null;
        }
        $query = $this->db->select('*')->from('mensaje')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }
    
    public function save($mensaje)
    {
        $this->db->set($this->_setMensaje($mensaje))->insert('mensaje');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    public function update($id,$mensaje)
    {
        $this->db->set($this->_setMensajeUpdate($mensaje))->where('id', $id)->update('mensaje');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    public function delete($id)
    {
        $this->db->where('id', $id)->delete('mensaje');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    private function _setMensaje($mensaje)
    {
        return array(
            'usuario_id' => $mensaje['usuario_id'],
            'texto' => $mensaje['texto'],
            'fecha_hora_registro' => date('Y-m-d H:i:s')
        );
    }
    private function _setMensajeUpdate($mensaje)
    {
        return array(
            'usuario_id' => $mensaje['usuario_id'],
            'texto' => $mensaje['texto']
        );
    }
}
