<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Log_solicitud_model extends CI_Model{
     public function __construct() {
         parent::__construct();
     }
     
     public function get($id = null)
    {
        if (!is_null($id)) {
            
            $query = $this->db->query("SELECT l.etapas_id,(SELECT nombre from etapas_solicitud where id=l.etapas_id) as etapa_nombre, l.solicitud_id, l.registro_fecha_hora from log_solicitud as l where l.solicitud_id=$id");
            if ($query->num_rows() > 0) {
                return $query->result_array();
            }
            return null;
        }
        $query = $this->db->select('*')->from('log_solicitud')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }
    
    public function save($log_solicitud)
    {
        $this->db->set($this->_setLogSolicitud($log_solicitud))->insert('log_solicitud');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    
    public function cancel($solicitud_id)
    {
        $this->db->set($this->_setLogSolicitud($solicitud_id,2))->insert('log_solicitud');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    
    public function finish($solicitud_id)
    {
        $this->db->set($this->_setLogSolicitud($solicitud_id,3))->insert('log_solicitud');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    /*public function update($id,$log_solicitud)
    {
        $this->db->set($this->_setLogSolicitudUpdate($log_solicitud))->where('solicitud_id', $id)->update('log_solicitud');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    public function delete($id)
    {
        $this->db->where('solicitud_id', $id)->delete('log_solicitud');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
     * 
     */
    private function _setLogSolicitud($log_solicitud)
    {
        
        return array(
            'etapas_id' => $log_solicitud['etapas_id'],
            'solicitud_id' => $log_solicitud['solicitud_id']
        );
    }
    private function _setLogSolicitudUpdate($log_solicitud)
    {
        
        return array(
            'etapas_id' => $log_solicitud['etapas_id'],
            'solicitud_id' => $log_solicitud['solicitud_id']
        );
    }
}
