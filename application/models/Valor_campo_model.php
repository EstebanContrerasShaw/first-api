<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Valor_campo_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get($id = null) {
        if (!is_null($id)) {

            $query = $this->db->select('*')->from('valor_campo')->where('id', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }
            return null;
        }
        $query = $this->db->select('*')->from('valor_campo')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }

    public function getPorInspeccion($id) {
        $query = $this->db->query("select c.id as campo_id,c.nombre,c.orden,c.importancia,c.estado,c.categoria_id,v.id as valor_campo_id,v.valor,v.observacion,v.registro_fecha_hora from valor_campo as v, campo as c where c.id=v.campo_id and  inspeccion_id=$id ");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }
    public function getPDFCategoriaInspeccion($idInspeccion,$idCatego) {
        $query = $this->db->query("SELECT c.orden,c.nombre,c.importancia,v.valor,v.observacion FROM valor_campo AS v, campo AS c WHERE c.id=v.campo_id AND v.inspeccion_id=$idInspeccion AND c.categoria_id=$idCatego");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }

    public function getPDFInspeccion($idInspeccion) {
        $arreglofinal=array();
        $queryIdCat = $this->db->query("SELECT categoria_id FROM valor_campo AS v, campo AS c WHERE c.id=v.campo_id AND v.inspeccion_id=$idInspeccion GROUP BY c.categoria_id");
        if ($queryIdCat->num_rows() > 0){
            $catIds=$queryIdCat->result_array();
            foreach($catIds as $iddeCat){
                $id=$iddeCat['categoria_id'];
                $queryCat = $this->db->query("SELECT * FROM categoria WHERE id=$id");
                $catego=$queryCat->row_array();
                $catego['campos'] = $this->getPDFCategoriaInspeccion($idInspeccion,$id);
                array_push($arreglofinal, $catego);
            }
            return $arreglofinal;
        }
        return null;
    }

    public function save($valor_campo) {
        $valorPosible=array('Cumple','No Cumple','No Aplica','1','2','3',1,2,3);
        if(!in_array($valor_campo['valor'], $valorPosible)){
            return NULL;
        }
        $this->db->set($this->_setValor_campo($valor_campo))->insert('valor_campo');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }

    public function saveDetalle($detalle) {
        $query = 'select count(*) as total from campo where estado=1';
        $contador = 0;
        $arrId = array();
        $total = $this->db->query($query)->row_array();
        if ($total['total'] == count($detalle)) {
            foreach ($detalle as $det) {
                $idr=$this->save($det);
                if (!is_null($idr)) {
                    $contador++;
                    array_push($arrId, $idr);
                }
            }
            if ($contador == count($detalle)) {
                return true;
            }
            } else {
            foreach ($arrId as $id) {
                $this->db->where('id', $id)->delete('campo');
            }
            return null;
        }
        
        return null;
    }


    /*public function update($id, $valor_campo) {
        $this->db->set($this->_setValor_campoUpdate($valor_campo))->where('id', $id)->update('valor_campo');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }

    public function delete($id) {
        $this->db->where('id', $id)->delete('valor_campo');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
     * 
     */

    private function _setValor_campo($valor_campo) {
        $valorPosible=array('Cumple','No Cumple','No Aplica','1','2','3',1,2,3);
        if(!in_array($valor_campo['valor'], $valorPosible)){
            return NULL;
        }
        return array(
            'valor' => $valor_campo['valor'],
            'observacion' => $valor_campo['observacion'],
            'inspeccion_id' => $valor_campo['inspeccion_id'],
            'campo_id' => $valor_campo['campo_id']
        );
    }

}
