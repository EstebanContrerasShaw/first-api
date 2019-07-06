<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitud_vip_model extends CI_Model{
     public function __construct() {
         parent::__construct();
     }
     
     public function get($id = null)
    {
        $sentencia="SELECT s.id,s.modelo,s.año,s.patente,s.estado,s.kilometros,s.registro_fecha_hora,s.marca_id,(SELECT nombre FROM marca where id=s.marca_id) as marca,s.cliente_usuario_id,s.solicitud_tipo_id,(SELECT nombre FROM solicitud_tipo WHERE id=s.solicitud_tipo_id) as tipo,v.mecanico_usuario_id FROM solicitud as s, solicitud_vip as v where s.id=v.solicitud_id";
        if (!is_null($id)) {
            $conid="  AND s.id=$id";
            $query = $this->db->query($sentencia.$conid);
            //$query = $this->db->select('*')->from('solicitud_vip')->where('solicitud_id', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }
            return null;
        }
        $query = $this->db->query($sentencia);
        //$query = $this->db->select('*')->from('solicitud_vip')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }
    
    public function save($id,$solicitud_vip)
    {
        $this->db->set($this->_setSolicitud_vip($id,$solicitud_vip))->insert('solicitud_vip');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    public function update($id,$solicitud_vip)
    {
        $this->db->set($this->_setSolicitud_vip($id,$solicitud_vip))->where('solicitud_id', $id)->update('solicitud_vip');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    /*public function delete($id)
    {
        $this->db->where('solicitud_id', $id)->delete('solicitud_vip');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
     * 
     */
    private function _setSolicitud_vip($id,$solicitud_vip)
    {
        return array(
            'solicitud_id' => $id,
            'mecanico_usuario_id' => $solicitud_vip['mecanico_usuario_id'],
        );
    }
}
