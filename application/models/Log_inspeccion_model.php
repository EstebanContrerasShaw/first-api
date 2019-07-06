<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Log_inspeccion_model extends CI_Model{
     public function __construct() {
         parent::__construct();
     }
     
     public function get($id = null)
    {
        if (!is_null($id)) {
            
            $query = $this->db->query("SELECT l.etapas_id,(SELECT nombre from etapas_inspeccion where id=l.etapas_id) as etapa_nombre, l.inspeccion_id, l.registro_fecha_hora from log_inspeccion as l where l.inspeccion_id=$id");
            if ($query->num_rows() > 0) {
                return $query->result_array();
            }
            return null;
        }
        $query = $this->db->select('*')->from('log_inspeccion')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }
    
    public function save($inspeccion_id)
    {
        $this->db->set($this->_setLogInspeccion($inspeccion_id,1))->insert('log_inspeccion');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    
    public function finish($inspeccion_id)
    {
        $this->db->set($this->_setLogInspeccion($inspeccion_id,2))->insert('log_inspeccion');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    /*public function update($id,$log_inspeccion)
    {
        $this->db->set($this->_setLogInspeccionUpdate($log_inspeccion))->where('inspeccion_id', $id)->update('log_inspeccion');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    
    public function delete($id)
    {
        $this->db->where('inspeccion_id', $id)->delete('log_inspeccion');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
     * 
     */
    private function _setLogInspeccion($inspeccion,$etapa)
    {
        
        return array(
            'etapas_id' => $etapa,
            'inspeccion_id' => $inspeccion
        );
    }
    private function _setLogInspeccionUpdate($log_inspeccion)
    {
        
        return array(
            'etapas_id' => $log_inspeccion['etapas_id'],
            'inspeccion_id' => $log_inspeccion['inspeccion_id']
        );
    }
}
