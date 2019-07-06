<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Etapas_solicitud_model extends CI_Model{
     public function __construct() {
         parent::__construct();
     }
     
     public function get($id = null)
    {
        if (!is_null($id)) {
            
            $query = $this->db->select('*')->from('etapas_solicitud')->where('id', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }
            return null;
        }
        $query = $this->db->select('*')->from('etapas_solicitud')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }
    
    public function save($etapas_solicitud)
    {
        $this->db->set($this->_setEtapasSolicitud($etapas_solicitud))->insert('etapas_solicitud');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    public function update($id,$etapas_solicitud)
    {
        $this->db->set($this->_setEtapasSolicitud($etapas_solicitud))->where('id', $id)->update('etapas_solicitud');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    
    /*public function delete($id)
    {
        $this->db->where('id', $id)->delete('etapas_solicitud');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
     * 
     */
    private function _setEtapasSolicitud($etapas_solicitud)
    {
        return array(
            'nombre' => $etapas_solicitud['nombre']
        );
    }
}
