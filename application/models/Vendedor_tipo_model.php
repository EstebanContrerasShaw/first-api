<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Vendedor_tipo_model extends CI_Model{
     public function __construct() {
         parent::__construct();
     }
     
     public function get($id = null)
    {
        if (!is_null($id)) {
            
            $query = $this->db->select('*')->from('vendedor_tipo')->where('id', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }
            return null;
        }
        $query = $this->db->select('*')->from('vendedor_tipo')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }
    
    public function save($vendedor_tipo)
    {
        $this->db->set($this->_setVendedor_tipo($vendedor_tipo))->insert('vendedor_tipo');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    public function update($id,$vendedor_tipo)
    {
        $this->db->set($this->_setVendedor_tipoUpdate($vendedor_tipo))->where('id', $id)->update('vendedor_tipo');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    /*public function delete($id)
    {
        $this->db->where('id', $id)->delete('vendedor_tipo');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
     * 
     */
    private function _setVendedor_tipo($vendedor_tipo)
    {
        return array(
            'nombre' => $vendedor_tipo['nombre']
        );
    }
    private function _setVendedor_tipoUpdate($vendedor_tipo)
    {
        return array(
            'nombre' => $vendedor_tipo['nombre'],
            'estado' => $vendedor_tipo['estado']
        );
    }
}
