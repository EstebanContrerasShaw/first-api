<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inspeccion_model extends CI_Model{
     public function __construct() {
         parent::__construct();
     }
     
     public function get($id = null)
    {
        if (!is_null($id)) {
            
            $query = $this->db->select('*')->from('inspeccion')->where('id', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }
            return null;
        }
        $query = $this->db->select('*')->from('inspeccion')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }
    
    public function save($inspeccion)
    {
        $sgte=$this->db->query("SELECT `AUTO_INCREMENT` as sgte
                        FROM  INFORMATION_SCHEMA.TABLES
                        WHERE TABLE_SCHEMA = 'mecanico'
                        AND   TABLE_NAME   = 'inspeccion'")->row_array();
        $query = $this->db->select('*')->from('inspeccion')->where('numero_de_orden', $sgte['sgte'])->get();
            if ($query->num_rows() === 1) {
                return (-1);
            }
        $this->db->set($this->_setInspeccion($inspeccion))->insert('inspeccion');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    public function update($id,$inspeccion)
    {
        $this->db->set($this->_setInspeccionUpdate($inspeccion))->where('id', $id)->update('inspeccion');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    public function estado($id,$estado)
    {
        $this->db->query("update inspeccion set estado=$estado where id=$id");
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    
    
    /*public function delete($id)
    {
        $this->db->where('id', $id)->delete('inspeccion');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
     * 
     */
    private function _setInspeccion($inspeccion)
    {
        $dbname=$this->db->database;
        $sgte=$this->db->query("SELECT `AUTO_INCREMENT` as sgte
                        FROM  INFORMATION_SCHEMA.TABLES
                        WHERE TABLE_SCHEMA = '$dbname'
                        AND   TABLE_NAME   = 'inspeccion'")->row_array();
        
        return array(
            'numero_de_orden' => $sgte['sgte'],
            'mecanico_usuario_id' => $inspeccion['mecanico_usuario_id'],
            'solicitud_id' => $inspeccion['solicitud_id'],
            'observaciones' => $inspeccion['observaciones'],
            'empresa_id' => $inspeccion['empresa_id']
        );
    }
    private function _setInspeccionUpdate($inspeccion)
    {
        
        return array(
            'mecanico_usuario_id' => $inspeccion['mecanico_usuario_id'],
            'admin_usuario_id' => $inspeccion['admin_usuario_id'],
            'estado' => $inspeccion['estado'],
            'puntaje' => $inspeccion['puntaje'],
            'observaciones' => $inspeccion['observaciones'],
            'solicitud_id' => $inspeccion['solicitud_id']
        );
    }
}
