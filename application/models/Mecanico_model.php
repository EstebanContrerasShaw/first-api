<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mecanico_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get($id = null) {
        if (!is_null($id)) {
            $conid = "SELECT u.id,u.rut,u.dv,u.nombres,u.apellidos,u.email,u.celular,u.estado,u.registro_fecha_hora,u.usuario_tipo_id,(SELECT nombre from usuario_tipo WHERE id=u.usuario_tipo_id) as tipo,m.foto_perfil_ruta,m.direccion,m.comuna_id,(SELECT nombre FROM comuna WHERE id=m.comuna_id) as comuna, m.password, m.empresa_id, (SELECT empresa FROM empresa WHERE id=m.empresa_id) as empresa FROM usuario as u, mecanico as m WHERE m.usuario_id=u.id  AND u.id=$id";
            $query = $this->db->query($conid);
            //$query = $this->db->select('*')->from('mecanico')->where('usuario_id', $id)->get();
            if ($query->num_rows() === 1) {
                $arreglo = $query->row_array();

                // esto de Aqui es para probar la desencriptacion, no deberia estar aqui o quizas si - averiguar
                //$clave=$this->encryption->decrypt($arreglo['password']);
                //$arreglo['password']=$clave;
                $arreglo['imagen'] = $this->agregarImagen($arreglo['foto_perfil_ruta']);
                return $arreglo;
            }
            return null;
        }

         $sentencia = "SELECT u.id,u.rut,u.dv,u.nombres,u.apellidos,u.email, u.estado, m.empresa_id, u.celular, (SELECT COUNT(inspeccion.id) FROM inspeccion WHERE inspeccion.mecanico_usuario_id=u.id and inspeccion.estado<3) as pendientes  FROM usuario as u, mecanico as m WHERE m.usuario_id=u.id";
        $query = $this->db->query($sentencia);
        //$query = $this->db->select('*')->from('mecanico')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }

    public function save($id, $mecanico) {
        $this->db->set($this->_setMecanico($id, $mecanico))->insert('mecanico');
        if ($this->db->affected_rows() === 1) {
            
            return $this->db->insert_id();
        }
        return null;
    }

    public function update($id, $mecanico) {
        $query = $this->db->select('*')->from('mecanico')->where('usuario_id', $id)->get();
        $arreglo = $query->row_array();
        $antigua = $arreglo['foto_perfil_ruta'];
        $nueva = $this->fotoTemp($mecanico['imagen']);
        $tamanno = $this->compararTamanno($antigua, $nueva);
        if (!is_null($tamanno)) {
            $this->reemplazarFoto($mecanico['imagen'], $antigua);
        }
        unlink($nueva);
        $this->db->set($this->_setMecanicoUpdate($mecanico))->where('usuario_id', $id)->update('mecanico');
        if ($this->db->affected_rows() === 1 || $tamanno) {
            return true;
        }

        return null;
    }

    public function getEstado($id) {
        $this->db->query("SELECT estado FROM usuario WHERE id=$id ");
        if ($this->db->affected_rows() === 1) {
            return $query->row_array()['estado'];;
        }
        return null;
    }

    public function estadoOff($id) {
        $this->db->query("update usuario set estado=0 where id=$id ");
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }

    public function estadoOn($id) {
        $this->db->query("update usuario set estado=1 where id=$id ");
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }

    public function getEmpresa($id)
    {
        $query=$this->db->query("SELECT empresa from empresa WHERE id=(SELECT empresa_id FROM mecanico WHERE usuario_id=(SELECT mecanico_usuario_id from inspeccion where id=$id))");
        if ($this->db->affected_rows() === 1) {
            $email=$query->row_array();
            return $email['empresa'];
        }
        return null;
    }

    public function setPagado($id) {
        $this->db->query("UPDATE inspeccion SET estado=3 WHERE mecanico_usuario_id=$id ");
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }

    /* public function delete($id)
      {
      $query = $this->db->select('*')->from('mecanico')->where('usuario_id', $id)->get();
      $arreglo = $query->row_array();
      unlink($arreglo['ruta']);
      $this->db->where('usuario_id', $id)->delete('mecanico');
      if ($this->db->affected_rows() === 1) {
      return true;
      }
      return null;
      } */

    private function _setMecanico($id, $mecanico) {
        //$this->load->library('encryption');
        //$clave=$this->encryption->encrypt($mecanico['password']);
        $clave = password_hash($mecanico["password"], PASSWORD_DEFAULT);
        return array(
            'usuario_id' => $id,
            'foto_perfil_ruta' => $this->subirFoto($mecanico['imagen'], $mecanico['rut']),
            'password' => $clave,
            'direccion' => $mecanico['direccion'],
            'comuna_id' => $mecanico['comuna_id'],
            'empresa_id' => $mecanico['empresa_id']
        );
    }

    private function _setMecanicoUpdate($mecanico) {

        $clave = password_hash($mecanico["password"], PASSWORD_DEFAULT);
        return array(
            'password' => $clave,
            'direccion' => $mecanico['direccion'],
            'comuna_id' => $mecanico['comuna_id'],
            'empresa_id' => $mecanico['empresa_id']
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
        $subdominio = $this->mecanico_model->getEmpresa($idInspeccion);;
        $image_path = 'anexos/'.$subdominio.'/fotosmecanico/';
        if (!file_exists($image_path)) {
            if(!is_dir('anexos/'.$subdominio)){
                mkdir('anexos/'.$subdominio, 0777);
            }
            mkdir($image_path, 0777);
        }
        $baseImagen = $imagen;
        $data = base64_decode($baseImagen);
        $ruta = $image_path . $nombre . '.jpg';
        file_put_contents($ruta, $data);

        return $ruta;
    }

    private function fotoTemp($imagen) {
        //$urlnombre = explode(".", $_SERVER['HTTP_HOST']);
        //$subdominio = $urlnombre[0];
        $subdominio = $this->mecanico_model->getEmpresa($idInspeccion);
        $image_path = 'anexos/'.$subdominio.'/fotosmecanico/';
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
