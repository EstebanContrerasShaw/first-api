<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitud_tipo_model extends CI_Model{
     public function __construct() {
         parent::__construct();
     }
     
     public function get($id = null)
    {
        if (!is_null($id)) {
            
            $query = $this->db->select('*')->from('solicitud_tipo')->where('id', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }
            return null;
        }
        $query = $this->db->select('*')->from('solicitud_tipo')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }
    
    public function save($solicitud_tipo)
    {
        $this->db->set($this->_setSolicitud_tipo($solicitud_tipo))->insert('solicitud_tipo');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    public function update($id,$solicitud_tipo)
    {
        $this->db->set($this->_setSolicitud_tipoUpdate($solicitud_tipo))->where('id', $id)->update('solicitud_tipo');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    /*public function delete($id)
    {
        $this->db->where('id', $id)->delete('solicitud_tipo');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
     * 
     */
    private function _setSolicitud_tipo($solicitud_tipo)
    {
        return array(
            'nombre' => $solicitud_tipo['nombre'],
            'descripcion' => $solicitud_tipo['descripcion']
        );
    }
    private function _setSolicitud_tipoUpdate($solicitud_tipo)
    {
        return array(
            'nombre' => $solicitud_tipo['nombre'],
            'estado' => $solicitud_tipo['estado'],
            'descripcion' => $solicitud_tipo['descripcion']
        );
    }
}
