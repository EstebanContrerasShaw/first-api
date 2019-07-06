<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Fotos_auto_model extends CI_Model{
     public function __construct() {
         parent::__construct();
     }
     
     public function get($id = null)
    {
        if (!is_null($id)) {
            
            $query = $this->db->select('*')->from('fotos_auto')->where('id', $id)->get();
            if ($query->num_rows() === 1) {
                $arreglo = $query->row_array();
                $arreglo['imagen'] = $this->agregarImagen($arreglo['ruta']);
                return $arreglo;
            }
            return null;
        }
        $query = $this->db->select('*')->from('fotos_auto')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }
    
    public function getInspeccion($id){
        $query = $this->db->select('*')->from('fotos_auto')->where('inspeccion_id', $id)->get();
            if ($query->num_rows() > 0) {
                $arreglo = $query->result_array();
                foreach ($arreglo as &$arr){
                    $arr['imagen'] = $this->agregarImagen($arr['ruta']);
                }
                return $arreglo;
            }
            return null;
    }
    public function getPDFInspeccion($id){
        $query = $this->db->select('*')->from('fotos_auto')->where('inspeccion_id', $id)->get();
            if ($query->num_rows() > 0) {
                $arreglo = $query->result_array();
                return $arreglo;
            }
            return null;
    }
    
    public function save($fotos_auto)
    {
        $this->db->set($this->_setFotos_auto($fotos_auto))->insert('fotos_auto');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    
    public function saveDetalle($fotos)
    {
        $contador=0;
        if(count($fotos)<=8){
            foreach ($fotos as $pic) {
                $this->db->set($this->_setFotos_auto($pic))->insert('fotos_auto');
                if ($this->db->affected_rows() === 1) {
                    $contador++;
                }
            }
            if ($contador == count($fotos)) {
                return true;
            }
            return null;
            }
        return null;
    }
    /*public function update($id,$fotos_auto)
    {
        $query = $this->db->select('*')->from('fotos_auto')->where('id', $id)->get();
        $arreglo = $query->row_array();
        $antigua = $arreglo['ruta'];
        $nueva = $this->fotoTemp($fotos_auto['imagen']);
        $tamanno=$this->compararTamanno($antigua, $nueva);
        if (!is_null($tamanno)) {
            $this->reemplazarFoto($fotos_auto['imagen'], $antigua);
            $this->db->set($this->_setFotos_autoUpdate($fotos_auto))->where('id', $id)->update('fotos_auto');
            unlink($nueva);
            if ($this->db->affected_rows() === 1 || $tamanno) {
            return true;
        }
        }
        return null;
    }
    public function delete($id)
    {
        $query = $this->db->select('*')->from('fotos_auto')->where('id', $id)->get();
        $arreglo = $query->row_array();
        unlink($arreglo['ruta']);
        $this->db->where('id', $id)->delete('fotos_auto');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
     * 
     */
    private function _setFotos_auto($fotos_auto)
    {
        return array(
            'ruta' => $this->subirFoto($fotos_auto['imagen'], $fotos_auto['inspeccion_id']),
            'comentario' => $fotos_auto['comentario'],
            'inspeccion_id' => $fotos_auto['inspeccion_id']
        );
    }
   
    private function _setFotos_autoUpdate($fotos_auto)
    {
        return array(
            'comentario' => $fotos_auto['comentario'],
            'inspeccion_id' => $fotos_auto['inspeccion_id']
        );
    }
    
    
    private function compararTamanno($antigua, $nueva) {
        if (md5_file($antigua) != md5_file($nueva)) {
            return true;
        }
        return null;
    }

    private function agregarImagen($ruta) {
        $imgreal = file_get_contents($ruta);
        $imgstr = base64_encode($imgreal);
        return $imgstr;
    }

    private function reemplazarFoto($imagen, $ruta) {
        $data = base64_decode($imagen);
        file_put_contents($ruta, $data);
        return $ruta;
    }

    private function subirFoto($imagen, $nombre) {
        //$urlnombre = explode(".", $_SERVER['HTTP_HOST']);
        //$subdominio = $urlnombre[0];
        $subdominio = $this->mecanico_model->getEmpresa($idInspeccion);
        $image_path = 'anexos/'.$subdominio.'/fotosauto/';
        if (!file_exists($image_path)) {
            if(!is_dir('anexos/'.$subdominio)){
                mkdir('anexos/'.$subdominio, 0777);
            }
            mkdir($image_path, 0777);
        }
        $baseImagen = $imagen;
        $data = base64_decode($baseImagen);
        $ruta = $image_path . 'Insp-'.$nombre .'-'. time() . rand(). '.jpg';
        file_put_contents($ruta, $data);

        return $ruta;
    }
    
    private function fotoTemp($imagen) {
        //$urlnombre = explode(".", $_SERVER['HTTP_HOST']);
        //$subdominio = $urlnombre[0];
        $subdominio = $this->mecanico_model->getEmpresa($idInspeccion);
        $image_path = 'anexos/'.$subdominio.'/fotosauto/';
        if (!file_exists($image_path)) {
            if(!is_dir('anexos/'.$subdominio)){
                mkdir('anexos/'.$subdominio, 0777);
            }
            mkdir($image_path, 0777);
        }
        $baseImagen = $imagen;
        $data = base64_decode($baseImagen);
        $ruta = $image_path . 'temp.jpg';
        file_put_contents($ruta, $data);

        return $ruta;
    }

    
    
}
