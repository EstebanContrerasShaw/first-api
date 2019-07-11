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
                $catego = $query->row_array();
                $catego['campos'] = $this->getCamposPorCat($catego['id']);
                return $catego;
            }
            return null;
        }
        $query = $this->db->query("select * from categoria where estado=1 and empresa_id=$empresa_id ORDER BY orden ASC");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }

    /*public function save($categoria) {
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
    }*/

    public function estado($id) {
        $this->db->query("update categoria set estado=0 where id=$id");
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }

    /*public function update($id, $categoria) {
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
    }*/

    public function getFormulario($empresa_id){
        $query = $this->db->select('*')->from('categoria')->where('empresa_id', $empresa_id)->where('estado', 1)->get();
        if ($query->num_rows() > 0) {
            $arrCat = $query->result_array();
            foreach ($arrCat as &$cat) {                
                $cat['campos'] = $this->getCamposPorCat($cat['id']);
            }
            return $arrCat;
        }
    }

    private function getCamposPorCat($catId){
        $query = $this->db->select('*')->from('campo')->where('categoria_id', $catId)->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }

    public function savefull($formulario,$empresa_id) {
        $contador = 0;
        $idCat=0;
        $arrId = array();
        foreach ($formulario as $catego) {
            $data=array(
                'nombre' => $catego['nombre'],
                'orden' => $catego['orden'],
                'empresa_id' => $empresa_id
            );
            $this->db->set($data)->insert('categoria');
            if ($this->db->affected_rows() === 1) {
                $idCat=$this->db->insert_id();
            }
            foreach ($catego['campo'] as $campo) {
                $datos=array(
                    'nombre' => $campo['nombre'],
                    'orden' => $campo['orden'],
                    'importancia' => $campo['importancia'],
                    'categoria_id' => $idCat   
                );
                $this->db->set($datos)->insert('campo');
                if ($this->db->affected_rows() === 1) {
                    $contador++;
                }
            }
        }
        return $contador;
    }

    public function deletefull($empresa_id) {

        $arrCat = $this->db->query("SELECT id FROM categoria WHERE empresa_id=$empresa_id")->result_array();
        foreach ($arrCat as $cat) {
            $idCat=$cat['id'];
            $this->db->query("UPDATE campo SET estado=0 WHERE categoria_id=$idCat");    
        }
        $this->db->query("UPDATE categoria SET estado=0 WHERE empresa_id=$empresa_id");
        return TRUE;
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

    private function _setCampo($campo) {
        return array(
            'nombre' => $campo['nombre'],
            'orden' => $campo['orden'],
            'importancia' => $campo['importancia'],
            'categoria_id' => $campo['categoria_id']
        );
    }
}
