<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cancelacion_model extends CI_Model{
     public function __construct() {
         parent::__construct();
     }
     
     public function get($id = null)
    {
        if (!is_null($id)) {
            
            $query = $this->db->select('*')->from('cancelacion')->where('id', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }
            return null;
        }
        $query = $this->db->select('*')->from('cancelacion')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }
    
    public function save($cancelacion)
    {
        $this->db->set($this->_setCancelacion($cancelacion))->insert('cancelacion');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    /*public function update($id,$cancelacion)
    {
        $this->db->set($this->_setCancelacion($cancelacion))->where('id', $id)->update('cancelacion');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    public function delete($id)
    {
        $this->db->where('id', $id)->delete('cancelacion');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }*/
    
    private function _setCancelacion($cancelacion)
    {
        return array(
            'motivo' => $cancelacion['motivo'],
            'usuario_id' => $cancelacion['usuario_id']
        );
    }
}
