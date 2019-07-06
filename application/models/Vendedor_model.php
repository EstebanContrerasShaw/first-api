<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Vendedor_model extends CI_Model{
     public function __construct() {
         parent::__construct();
     }
     
     public function get($id = null)
    {
        $sql="SELECT v.id,v.nombre,v.direccion,v.comuna_id,(SELECT nombre FROM comuna WHERE id=v.comuna_id) as comuna,v.fono,v.vendedor_tipo_id,t.nombre tipo from vendedor as v, vendedor_tipo as t where v.vendedor_tipo_id=t.id";
        if (!is_null($id)) {
            $conId="  AND v.id=$id";
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
    
    public function save($vendedor)
    {
        $this->db->set($this->_setVendedor($vendedor))->insert('vendedor');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    public function update($id,$vendedor)
    {
        $this->db->set($this->_setVendedorUpdate($vendedor))->where('id', $id)->update('vendedor');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    public function delete($id)
    {
        $this->db->where('id', $id)->delete('vendedor');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    private function _setVendedor($vendedor)
    {
        return array(
            'nombre' => $vendedor['nombre'],
            'fono' => $vendedor['fono'],
            'direccion' => $vendedor['direccion'],
            'comuna_id' => $vendedor['comuna_id'],
            'vendedor_tipo_id' => $vendedor['vendedor_tipo_id']
        );
    }
    private function _setVendedorUpdate($vendedor)
    {
        return array(
            'nombre' => $vendedor['nombre'],
            'fono' => $vendedor['fono'],
            'direccion' => $vendedor['direccion'],
            'comuna_id' => $vendedor['comuna_id'],
            'vendedor_tipo_id' => $vendedor['vendedor_tipo_id']
        );
    }
}
