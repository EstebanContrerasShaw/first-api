<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Region_model extends CI_Model{
     public function __construct() {
         parent::__construct();
     }
     
     public function get($id = null)
    {
        if (!is_null($id)) {
            
            $query = $this->db->select('*')->from('region')->where('id', $id)->get();
            if ($query->num_rows() === 1) {
                $region = $query->row_array();
                $region['comunas'] = $this->db->select('*')->from('comuna')
                                    ->where('region_id', $region['id'])->get()->result_array();
                return $region;
            }
            return null;
        }
        $query = $this->db->select('*')->from('region')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }
    
    public function save($region)
    {
        $this->db->set($this->_setRegion($region))->insert('region');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    public function update($id,$region)
    {
        $this->db->set($this->_setRegion($region))->where('id', $id)->update('region');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    /*public function delete($id)
    {
        $this->db->where('id', $id)->delete('region');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
     * 
     */
    private function _setRegion($region)
    {
        return array(
            'nombre' => $region['nombre']
        );
    }
}
