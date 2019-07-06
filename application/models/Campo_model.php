<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Campo_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get($empresa_id,$id = null) {
        if (!is_null($id)) {


            $query = $this->db->query("SELECT c.id,c.nombre,c.estado,c.orden,c.categoria_id,(SELECT nombre from categoria where id=c.categoria_id) as categoria_nombre,c.importancia FROM campo as c where c.id=$id");
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }
            return null;
        }
        $query = $this->db->query("SELECT c.id,c.nombre,c.estado,c.orden,c.categoria_id,(SELECT nombre from categoria where id=c.categoria_id) as categoria_nombre,c.importancia FROM campo as c where estado=1 and empresa_id=$empresa_id ORDER BY c.categoria_id,c.orden ASC");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }

    public function getPorCategoria($id) {
        $query = $this->db->query("SELECT c.id,c.nombre,c.estado,c.orden,c.categoria_id,(SELECT nombre from categoria where id=c.categoria_id) as categoria_nombre,c.importancia FROM campo as c where estado=1 and c.categoria_id=$id ORDER BY c.orden ASC");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }

    public function save($campo) {
        $ultimo = $this->db->query("SELECT orden from campo where estado=1 and categoria_id=" . $campo['categoria_id'] . " ORDER BY orden desc limit 1")->row_array();
        $query = $this->db->query("select * from campo where estado=1 and orden=" . $campo['orden'] . " and categoria_id=" . $campo['categoria_id']);
        if ($query->num_rows() === 0) {
            $campo['orden'] = $ultimo['orden'] + 1;
            $this->db->set($this->_setCampo($campo))->insert('campo');
            if ($this->db->affected_rows() === 1) {
                return $this->db->insert_id();
            }
            return null;
        } else {
            $modificar = $this->db->query("select * from campo where estado=1 and orden>=" . $campo['orden'] . " and categoria_id=" . $campo['categoria_id']);
            $arrnuevo = $modificar->result_array();
            $this->db->set($this->_setCampo($campo))->insert('campo');
            $id = $this->db->insert_id();
            foreach ($arrnuevo as $val) {
                $this->db->query("update campo set estado=0 where id=" . $val['id']);
                $val['orden'] ++;
                $this->db->set($this->_setCampo($val))->insert('campo');
            }
            return $id;
        }
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
                    'categoria_id' => $idCat,
                    'empresa_id' => $empresa_id      
                );
                $this->db->set($datos)->insert('campo');
                if ($this->db->affected_rows() === 1) {
                    $contador++;
                }
            }
        }
        return $contador;
    }

    public function saveCategoria($campo) {
        $contador = 0;
        $arrId = array();
        foreach ($campo as $cam) {
            $idr = $this->save($cam);
            if (!is_null($idr)) {
                $contador++;
                array_push($arrId, $idr);
            }
        }
        if ($contador == count($campo)) {
            return $contador;
        } else {
            foreach ($arrId as $id) {
                $this->db->where('id', $id)->delete('campo');
            }
            return null;
        }
    }

    public function estado($id) {
        $this->db->query("update campo set estado=0 where id=$id");
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }

    public function update($id, $campo) {
        $query = $this->db->query("select * from campo where estado=1 and id=$id");
        if ($query->num_rows() === 1) {
            $viejo = $query->row_array();
            if ($viejo['categoria_id'] == $campo['categoria_id']) {
                if ($viejo['orden'] == $campo['orden']) {
                    $this->db->query("update campo set estado=0 where id=$id");
                    $this->db->set($this->_setCampo($campo))->insert('campo');
                    return TRUE;
                } else {
                    $modificar = $this->db->query("select * from campo where estado=1 and orden =" . $campo['orden'] . " and categoria_id=" . $campo['categoria_id']);
                    $this->db->query("update campo set estado=0 where id=$id");
                    if ($modificar->num_rows() == 1) {
                        $arrnuevo = $modificar->row_array();
                        $this->db->query("update campo set estado=0 where id=" . $arrnuevo['id']);
                        $arrnuevo['orden'] = $viejo['orden'];
                        $this->db->set($this->_setCampo($arrnuevo))->insert('campo');
                        $this->db->set($this->_setCampo($campo))->insert('campo');
                        return TRUE;
                    } else {
                        return null;
                    }
                }
            } else {
                $this->save($campo);
                $this->delete($id);
                return TRUE;
            }
        } else {
            return null;
        }
    }

    public function updateCategoria($idcat, $campo) {
        $this->db->query("update campo set estado=0 where categoria_id=$idcat");
        $contador = 0;
        $arrId = array();
        foreach ($campo as $cam) {
            $idr = $this->save($cam);
            if (!is_null($idr)) {
                $contador++;
                array_push($arrId, $idr);
            }
        }
        if ($contador == count($campo)) {
            return $contador;
        } else {
            foreach ($arrId as $id) {
                $this->db->where('id', $id)->delete('campo');
            }
            return null;
        }
    }

    public function updatefull($campo) {
        $this->db->query("update campo set estado=0 ");
        $contador = 0;
        $arrId = array();
        foreach ($campo as $cam) {
            $idr = $this->save($cam);
            if (!is_null($idr)) {
                $contador++;
                array_push($arrId, $idr);
            }
        }
        if ($contador == count($campo)) {
            return $contador;
        } else {
            foreach ($arrId as $id) {
                $this->db->where('id', $id)->delete('campo');
            }
            return null;
        }
    }

    public function delete($id) {
        $query = $this->db->query("select * from campo where id=$id");
        if ($query->num_rows() === 1) {
            $this->db->query("update campo set estado=0 where id=$id");
            $campo = $query->row_array();
            $modificar = $this->db->query("select * from campo where estado=1 and orden>" . $campo['orden'] . " and categoria_id=" . $campo['categoria_id']);
            if ($modificar->num_rows() > 0) {
                $arrnuevo = $modificar->result_array();
                foreach ($arrnuevo as $val) {
                    $this->db->query("update campo set estado=0 where id=" . $val['id']);
                    $val['orden'] --;
                    $this->db->set($this->_setCampo($val))->insert('campo');
                }
            }
            return TRUE;
        } else {
            return null;
        }
    }

    public function deleteCategoria($id) {
        $query = $this->db->query("select * from categoria where id=$id");
        if ($query->num_rows() === 1) {
            $this->db->query("update campo set estado=0 where categoria_id=$id");
            return TRUE;
        } else {
            return null;
        }
    }

    public function deletefull($empresa_id) {

        $this->db->query("update campo set estado=0 where empresa_id=$empresa_id");
        $this->db->query("update categoria set estado=0 where empresa_id=$empresa_id");
        return TRUE;
    }

    /*
      public function update($id, $campo) {
      $this->db->set($this->_setCampoUpdate($campo))->where('id', $id)->update('campo');
      if ($this->db->affected_rows() === 1) {
      return true;
      }
      return null;
      }

      public function delete($id) {
      $this->db->where('id', $id)->delete('campo');
      if ($this->db->affected_rows() === 1) {
      return true;
      }
      return null;
      }
     * 
     */

    private function _setCampo($campo) {
        return array(
            'nombre' => $campo['nombre'],
            'orden' => $campo['orden'],
            'importancia' => $campo['importancia'],
            'categoria_id' => $campo['categoria_id']
        );
    }

    /*
      private function _setCampoUpdate($campo) {
      return array(
      'nombre' => $campo['nombre'],
      'estado' => $campo['estado'],
      'orden' => $campo['orden'],
      'importancia' => $campo['importancia'],
      'categoria_id' => $campo['categoria_id']
      );
      }
     * 
     */
}
