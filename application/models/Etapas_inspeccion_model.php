<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Etapas_inspeccion_model extends CI_Model{
     public function __construct() {
         parent::__construct();
     }
     
     public function get($id = null)
    {
        if (!is_null($id)) {
            
            $query = $this->db->select('*')->from('etapas_inspeccion')->where('id', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }
            return null;
        }
        $query = $this->db->select('*')->from('etapas_inspeccion')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }
    
    public function save($etapas_inspeccion)
    {
        $this->db->set($this->_setEtapasInspeccion($etapas_inspeccion))->insert('etapas_inspeccion');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    public function update($id,$etapas_inspeccion)
    {
        $this->db->set($this->_setEtapasInspeccion($etapas_inspeccion))->where('id', $id)->update('etapas_inspeccion');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    /*public function delete($id)
    {
        $this->db->where('id', $id)->delete('etapas_inspeccion');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }*/
    private function _setEtapasInspeccion($etapas_inspeccion)
    {
        return array(
            'nombre' => $etapas_inspeccion['nombre']
        );
    }
}
