<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class marca_model extends CI_Model{
     public function __construct() {
         parent::__construct();
     }
     
     public function get($id = null)
    {
        if (!is_null($id)) {
            
            $query = $this->db->select('*')->from('marca')->where('id', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }
            return null;
        }
        $query = $this->db->select('*')->from('marca')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }
    
    public function save($marca)
    {
        $this->db->set($this->_setMarca($marca))->insert('marca');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    public function update($id,$marca)
    {
        $this->db->set($this->_setMarcaUpdate($marca))->where('id', $id)->update('marca');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    
    /*public function delete($id)
    {
        $this->db->where('id', $id)->delete('marca');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
     * 
     */
    private function _setMarca($marca)
    {
        return array(
            'nombre' => $marca['nombre']
        );
    }
    private function _setMarcaUpdate($marca)
    {
        return array(
            'nombre' => $marca['nombre'],
            'estado' => $marca['estado']
        );
    }
}
