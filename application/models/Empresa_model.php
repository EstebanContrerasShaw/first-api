<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Empresa_model extends CI_Model{
     public function __construct() {
         parent::__construct();
     }
     
     public function get($id = null)
    {
        if (!is_null($id)) {
            $query = $this->db->select('*')->from('empresa')->where('id', $id)->get();
            if ($query->num_rows() === 1) {
                $arreglo = $query->row_array();
                $arreglo['imagen'] = $this->agregarImagen($arreglo['logo']);
                return $arreglo;
            }
            return null;
        }
        $query = $this->db->select('*')->from('empresa')->get();
        if ($query->num_rows() > 0) {
            //return $query->result_array();
            $todas = $query->result_array();
            foreach ($todas as &$emp) {
                $emp['imagen'] = $this->agregarImagen($emp['logo']);
            }
            return $todas;
        }
        return null;
    }
    
    public function save($empresa)
    {
        $this->db->set($this->_setEmpresa($empresa))->insert('empresa');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    public function update($id,$empresa)
    {
        $contar = 0;
        if(isset($empresa['imagen'])){
            $this->db->set($this->_setEmpresaUpdate($empresa))->where('id', $id)->update('empresa');
            $contar += $this->db->affected_rows();
        }else{
            $this->db->set($this->_setEmpresa($empresa))->where('id', $id)->update('empresa');
            $contar += 1;
        }
        
        if ($contar > 0) {
            return true;
        }
        return null;
    }

    /*public function updateLogo($id, $empresa) {
        $query = $this->db->select('*')->from('empresa')->where('id', $id)->get();
        $arreglo = $query->row_array();
        $antigua = $arreglo['logo'];
        $nueva = $this->fotoTemp($empresa['logo']);
        $tamanno = $this->compararTamanno($antigua, $nueva);
        if (!is_null($tamanno)) {
            $this->reemplazarFoto($empresa['logo'], $antigua);
        }
        unlink($nueva);
        $this->db->set($this->_setEmpresaUpdate($empresa))->where('id', $id)->update('empresa');
        if ($this->db->affected_rows() === 1 || $tamanno) {
            return true;
        }

        return null;
    }*/

    public function estado($id, $estado)
    {
        $this->db->query("UPDATE empresa SET estado=$estado WHERE id=$id");
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }

    /*public function delete($id)
    {
        $this->db->where('id', $id)->delete('empresa');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
     * 
     */
    private function _setEmpresa($empresa)
    {
        return array(
            'empresa' => $empresa['empresa'],
            'logo' => $this->subirFoto($empresa['imagen'], $empresa['empresa']),
        );
    }

    private function _setEmpresaUpdate($empresa)
    {
        return array(
            'empresa' => $empresa['empresa']
        );
    }



    /*private function compararTamanno($antigua, $nueva) {
        if (md5_file($antigua) != md5_file($nueva)) {
            return true;
        }
        return null;
    }

    private function reemplazarFoto($imagen, $ruta) {
        $data = base64_decode($imagen);
        file_put_contents($ruta, $data);
        return $ruta;
    }*/

    private function subirFoto($imagen, $nombre) {
        //$urlnombre = explode(".", $_SERVER['HTTP_HOST']);
        //$subdominio = $urlnombre[0];
        $subdominio = $nombre;
        $image_path = 'anexos/'.$subdominio.'/';
        if (!file_exists($image_path)) {
            mkdir($image_path, 0777);
        }
        $baseImagen = $imagen;
        $data = base64_decode($baseImagen);
        $ruta = $image_path . 'logo.png';
        file_put_contents($ruta, $data);

        return $ruta;
    }

    private function agregarImagen($ruta) {
        $imgreal = file_get_contents($ruta);
        $imgstr = base64_encode($imgreal);
        return $imgstr;
    }

    /*private function fotoTemp($imagen) {
        //$urlnombre = explode(".", $_SERVER['HTTP_HOST']);
        //$subdominio = $urlnombre[0];
        $subdominio = 
        $image_path = 'anexos/'.$subdominio.'/';
        if (!file_exists($image_path)) {
            if(!is_dir('anexos/'.$subdominio)){
                mkdir('anexos/'.$subdominio, 0777);
            }
            mkdir($image_path, 0777);
        }
        $baseImagen = $imagen;
        $data = base64_decode($baseImagen);
        $ruta = $image_path . 'temp.png';
        file_put_contents($ruta, $data);

        return $ruta;
    }*/
}
