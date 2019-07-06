<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Comuna_model extends CI_Model{
     public function __construct() {
         parent::__construct();
     }
     
     public function get($id = null)
    {
        $sql="SELECT c.id,c.nombre,c.region_id,r.nombre region_nombre FROM comuna as c, region as r WHERE c.region_id=r.id";
        if (!is_null($id)) {
            $conId="  AND c.id=$id";
            $query = $this->db->query($sql.$conId);
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }
            return null;
        }
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }
    
    public function save($comuna)
    {
        $this->db->set($this->_setComuna($comuna))->insert('comuna');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    public function update($id,$comuna)
    {
        $this->db->set($this->_setComuna($comuna))->where('id', $id)->update('comuna');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    
    /*public function delete($id)
    {
        $this->db->where('id', $id)->delete('comuna');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }*/
    
    private function _setComuna($comuna)
    {
        return array(
            'nombre' => $comuna['nombre'],
            'region_id' => $comuna['region_id']
            
        );
    }
}
