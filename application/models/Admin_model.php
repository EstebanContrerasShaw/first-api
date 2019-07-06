<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model{
     public function __construct() {
         parent::__construct();
     }
     
     public function get($id = null)
    {
        $sentencia="SELECT u.id,u.rut,u.dv,u.nombres,u.apellidos,u.email,u.celular,u.estado,u.registro_fecha_hora,a.comuna_id,(SELECT nombre FROM comuna WHERE id=a.comuna_id) as comuna,u.usuario_tipo_id,(SELECT nombre from usuario_tipo WHERE id=u.usuario_tipo_id) as tipo,a.password,a.empresa_id, (SELECT empresa from empresa WHERE id=a.empresa_id) as empresa  FROM usuario as u, admin as a WHERE a.usuario_id=u.id";
        if (!is_null($id)) {
            $conid="  AND u.id=$id";
            $query = $this->db->query($sentencia.$conid);
            //$query = $this->db->select('*')->from('admin')->where('usuario_id', $id)->get();
            if ($query->num_rows() === 1) {
                
                $arreglo=$query->row_array();
                return $arreglo;
            }
            return null;
        }
        $query = $this->db->query($sentencia);
        //$query = $this->db->select('*')->from('admin')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }
    
    
    public function getMail()
    {
        $query = $this->db->query("SELECT email FROM usuario where estado=1 and usuario_tipo_id=1 ORDER BY id limit 1");
        if ($this->db->affected_rows() === 1) {
            return $query->row_array()['email'];
        }
        return null;
    }
    
    public function save($id,$admin)
    {
        $this->db->set($this->_setAdmin($id,$admin))->insert('admin');
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return null;
    }
    public function update($id,$admin)
    {
        $this->db->set($this->_setAdminUpdate($admin))->where('usuario_id', $id)->update('admin');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    
    public function estado($id)
    {
        $this->db->query("update usuario set estado=0 where id=$id and usuario_tipo_id!=5");
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }
    /*public function delete($id)
    {
        $this->db->where('usuario_id', $id)->delete('admin');
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }*/
    private function _setAdmin($id,$admin)
    {
        //$this->load->library('encryption');
        //$clave=$this->encryption->encrypt($admin['password']);
        $clave=password_hash($admin["password"],PASSWORD_DEFAULT);
        return array(
            'password' => $clave,
            'usuario_id' => $id,
            'comuna_id' => $admin['comuna_id'],
            'empresa_id' => $admin['empresa_id']
        );
    }
    
    private function _setAdminUpdate($admin)
    {
        $clave=password_hash($admin["password"],PASSWORD_DEFAULT);
        return array(
            'password' => $clave,
            'comuna_id' => $admin['comuna_id'],
            'empresa_id' => $admin['empresa_id']
        );
    }
    
    
    private function compararTamanno($antigua, $nueva) {
        if (md5_file($antigua) != md5_file($nueva)) {
            return true;
        }
        return null;
    }

    private function reemplazarFoto($imagen, $ruta) {
        $data = base64_decode($imagen);
        file_put_contents($ruta, $data);
        return $ruta;
    }
    
    private function fotoTemp($imagen) {
        $urlnombre = explode(".", $_SERVER['HTTP_HOST']);
        $subdominio = $urlnombre[0];
        $image_path = 'anexos/'.$subdominio.'/fotosmecanico/';
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
    }
}
