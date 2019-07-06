<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Categoria_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get($empresa_id,$id = null) {
        if (!is_null($id)) {

            $query = $this->db->select('*')->from('categoria')->where('id', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }
            return null;
        }
        $query = $this->db->query("select * from categoria where estado=1 and empresa_id=$empresa_id ORDER BY orden ASC");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }

    public function save($categoria) {
        $ultimo = $this->db->query("SELECT orden from categoria where estado=1 ORDER BY orden desc limit 1")->row_array();
        $query = $this->db->query("select * from categoria where estado=1 and orden=" . $categoria['orden']);
        if ($query->num_rows() === 0) {
            $categoria['orden'] = $ultimo['orden'] + 1;
            $this->db->set($this->_setCategoria($categoria))->insert('categoria');
            if ($this->db->affected_rows() === 1) {
                return $this->db->insert_id();
            }
            return null;
        } else {
            $modificar = $this->db->query("select * from categoria where estado=1 and orden>=" . $categoria['orden']);
            $arrnuevo = $modificar->result_array();
            $this->db->set($this->_setCategoria($categoria))->insert('categoria');
            $id = $this->db->insert_id();
            foreach ($arrnuevo as $val) {
                $this->db->query("update categoria set estado=0 where id=" . $val['id']);
                $val['orden'] ++;
                $this->db->set($this->_setCategoria($val))->insert('categoria');
            }
            return $id;
        }
    }

    public function estado($id) {
        $this->db->query("update categoria set estado=0 where id=$id");
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }

    public function update($id, $categoria) {
        $query = $this->db->query("select * from categoria where estado=1 and id=$id");
        if ($query->num_rows() === 1) {
            $viejo = $query->row_array();
            if ($viejo['orden'] == $categoria['orden']) {
                $this->db->query("update categoria set estado=0 where id=$id");
                $this->db->set($this->_setCategoria($categoria))->insert('categoria');
                return TRUE;
            } else {
                $modificar = $this->db->query("select * from categoria where estado=1 and orden =" . $categoria['orden']);
                $this->db->query("update categoria set estado=0 where id=$id");
                if ($modificar->num_rows() == 1) {
                    $arrnuevo = $modificar->row_array();
                    $this->db->query("update categoria set estado=0 where id=" . $arrnuevo['id']);
                    $arrnuevo['orden'] = $viejo['orden'];
                    $this->db->set($this->_setCategoria($arrnuevo))->insert('categoria');
                    $this->db->set($this->_setCategoria($categoria))->insert('categoria');
                    return TRUE;
                } else {
                    return null;
                }
            }
        } else {
            return null;
        }

    }

    public function delete($id) {

        $query = $this->db->query("select * from categoria where id=$id");
        if ($query->num_rows() === 1) {
            $this->db->query("update categoria set estado=0 where id=$id");
            $categoria = $query->row_array();
            $modificar = $this->db->query("select * from categoria where estado=1 and orden>" . $categoria['orden']);
            if ($modificar->num_rows() > 0) {
                $arrnuevo = $modificar->result_array();
                foreach ($arrnuevo as $val) {
                    $this->db->query("update categoria set estado=0 where id=" . $val['id']);
                    $val['orden'] --;
                    $this->db->set($this->_setCategoria($val))->insert('categoria');
                }
            }
            return TRUE;
        } else {
            return null;
        }
    }

    /*
      public function update($id,$categoria)
      {
      $this->db->set($this->_setCategoriaUpdate($categoria))->where('id', $id)->update('categoria');
      if ($this->db->affected_rows() === 1) {
      return true;
      }
      return null;
      }
      public function delete($id)
      {
      $this->db->where('id', $id)->delete('categoria');
      if ($this->db->affected_rows() === 1) {
      return true;
      }
      return null;
      }
     * 
     */

    private function _setCategoria($categoria) {

        return array(
            'nombre' => $categoria['nombre'],
            'orden' => $categoria['orden'],
            'empresa_id' => $categoria['empresa_id']
        );
    }

    private function _setCategoriaUpdate($categoria) {

        return array(
            'nombre' => $categoria['nombre'],
            'orden' => $categoria['orden'],
            'estado' => $categoria['estado']
        );
    }

}
