<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitud_model extends CI_Model{
     public function __construct() {
         parent::__construct();
     }
     
     public function get($id = null)
    {
        if (!is_null($id)) {
            
            $query = $this->db->select('*')->from('solicitud')->where('id', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }
            return null;
        }
        $query = $this->db->select('*')->from('solicitud')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }
    
    public function save($solicitud)
    {
        $this->db->set($this->_setSolicitud($solicitud))->insert('solicitud');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    public function update($id,$solicitud)
    {
        $this->db->set($this->_setSolicitudUpdate($solicitud))->where('id', $id)->update('solicitud');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    public function delete($id)
    {
        $this->db->where('id', $id)->delete('solicitud');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    private function _setSolicitud($solicitud)
    {
        return array(
            'rut' => $solicitud['rut'],
            'dv' => $solicitud['dv'],
            'nombres' => $solicitud['nombres'],
            'apellidos' => $solicitud['apellidos'],
            'email' => $solicitud['email'],
            'celular' => $solicitud['celular'],
            'modelo' => $solicitud['modelo'],
            'año' => $solicitud['año'],
            'patente' => $solicitud['patente'],
            'kilometros' => $solicitud['kilometros'],
            'marca_id' => $solicitud['marca_id']
        );
    }
    private function _setSolicitudUpdate($solicitud)
    {
        return array(
            'rut' => $solicitud['rut'],
            'dv' => $solicitud['dv'],
            'nombres' => $solicitud['nombres'],
            'apellidos' => $solicitud['apellidos'],
            'email' => $solicitud['email'],
            'celular' => $solicitud['celular'],
            'modelo' => $solicitud['modelo'],
            'año' => $solicitud['año'],
            'patente' => $solicitud['patente'],
            'kilometros' => $solicitud['kilometros'],
            'estado' => $solicitud['estado'],
            'marca_id' => $solicitud['marca_id']
        );
    }

}
