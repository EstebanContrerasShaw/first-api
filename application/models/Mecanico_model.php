<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mecanico_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get($id = null) {
        if (!is_null($id)) {
            $conid = "SELECT u.id,u.rut,u.dv,u.nombres,u.apellidos,u.email,u.celular,u.estado,u.registro_fecha_hora,u.usuario_tipo_id,(SELECT nombre from usuario_tipo WHERE id=u.usuario_tipo_id) as tipo,m.foto_perfil_ruta,m.direccion,m.comuna_id,(SELECT nombre FROM comuna WHERE id=m.comuna_id) as comuna, m.password, u.empresa_id, (SELECT empresa FROM empresa WHERE id=u.empresa_id) as empresa FROM usuario as u, mecanico as m WHERE m.usuario_id=u.id  AND u.id=$id";
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

         $sentencia = "SELECT u.id,u.rut,u.dv,u.nombres,u.apellidos,u.email, u.estado, u.empresa_id, u.celular, (SELECT COUNT(inspeccion.id) FROM inspeccion WHERE inspeccion.mecanico_usuario_id=u.id and inspeccion.estado<3) as pendientes  FROM usuario as u, mecanico as m WHERE m.usuario_id=u.id";
        $query = $this->db->query($sentencia);
        //$query = $this->db->select('*')->from('mecanico')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }

    public function getByEmpresa($empresa_id,$id = null) {
        if (!is_null($id)) {
            $conid = "SELECT u.id,u.rut,u.dv,u.nombres,u.apellidos,u.email,u.celular,u.estado,u.registro_fecha_hora,u.usuario_tipo_id,(SELECT nombre from usuario_tipo WHERE id=u.usuario_tipo_id) as tipo,m.foto_perfil_ruta,m.direccion,m.comuna_id,(SELECT nombre FROM comuna WHERE id=m.comuna_id) as comuna, m.password, u.empresa_id, (SELECT empresa FROM empresa WHERE id=u.empresa_id) as empresa FROM usuario as u, mecanico as m WHERE m.usuario_id=u.id AND u.empresa_id=$empresa_id  AND u.id=$id";
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

         $sentencia = "SELECT u.id,u.rut,u.dv,u.nombres,u.apellidos,u.email, u.estado, u.empresa_id, u.celular, (SELECT COUNT(inspeccion.id) FROM inspeccion WHERE inspeccion.mecanico_usuario_id=u.id and inspeccion.estado<3) as pendientes  FROM usuario as u, mecanico as m WHERE m.usuario_id=u.id AND u.empresa_id=$empresa_id ";
        $query = $this->db->query($sentencia);
        //$query = $this->db->select('*')->from('mecanico')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }

    /*public function save($id, $mecanico) {
        $this->db->set($this->_setMecanico($id, $mecanico))->insert('mecanico');
        if ($this->db->affected_rows() === 1) {
            
            return $this->db->insert_id();
        }
        return null;
    }

    public function update($id, $mecanico) {
        $sentencia = "SELECT rut FROM usuario WHERE id=$id";
        $query = $this->db->query($sentencia)->row_array();
        $this->subirFoto($mecanico['imagen'], $query['rut'], $mecanico['empresa_id']);
        $this->db->set($this->_setMecanicoUpdate($mecanico))->where('usuario_id', $id)->update('mecanico');
        if ($this->db->affected_rows() === 1 ) {
            return true;
        }

        return null;
    }*/

    public function save($mecanico) {
        $queryRut = $this->db->select('id')->from('usuario')->where('rut', $mecanico['rut'])->get();
        if ($queryRut->num_rows() === 1) {
            return (-1);    
            
        }
        $queryMail = $this->db->select('*')->from('usuario')->where('email', $mecanico['email'])->get();
        if ($queryMail->num_rows() === 1) {
            return (-2);
        }
        $this->db->set($this->_setUsuario($mecanico))->insert('usuario');
        if ($this->db->affected_rows() === 1) { 
            $id=$this->db->insert_id();
            $this->db->set($this->_setMecanico($id,$mecanico))->insert('mecanico');
            return $id;
        }
        return null;
    }


    public function update($id,$mecanico)
    {
        $contar = 0;
        $this->db->set($this->_setMecanicoUpdate($mecanico))->where('usuario_id', $id)->update('mecanico');
        $contar += $this->db->affected_rows();
        $this->db->set($this->_setUsuarioUpdate($mecanico))->where('id', $id)->update('usuario');
        $contar += $this->db->affected_rows();
        if ($contar > 0) {
            return true;
        }
        if(isset($mecanico['imagen'])){
            return true;
        }
        return null;
    }

    public function actualizarEstados($mecanicos){
        $cont = 0;
        $flag=true;
        if(!empty($mecanicos['desactivar'])){
            foreach ($mecanicos['desactivar'] as $des) {
                if($this->getEstado($des) == 1){
                   $aux = $this->estadoOff($des);
                    if(!is_null($aux)){
                        $cont++;
                    } 
                }else{
                    $cont++;
                }
            }
        }
        if(!empty($mecanicos['activar'])){
            foreach ($mecanicos['activar'] as $act) {
                if($this->getEstado($act) == 0){
                    $aux = $this->estadoOn($act);
                    if(!is_null($aux)){
                        $cont++;
                    }
                }else{
                    $cont++;
                }
            }
        }
        
        if($cont != (count($mecanicos['desactivar'])) + count($mecanicos['activar'])){
            $flag = null;
        }
        return $flag;
    }

    public function resetPagados($mecanicos){
        $cont=0;
        $flag = true;
        if(!empty($mecanicos)){
            foreach ($mecanicos as $mec) {
                $aux = $this->setPagado($mec);
                if(!is_null($aux)){
                    $cont++;
                }
            }
        }
        
        if($cont != (count($mecanicos))){
            $flag = false;
        }
        return $flag;
    }

    public function getEstado($id) {
        $query = $this->db->query("SELECT estado FROM usuario WHERE usuario_tipo_id=2  AND id=$id ");
        if ($this->db->affected_rows() === 1) {
            return $query->row_array()['estado'];
        }
        return null;
    }

    public function estadoOff($id) {
        $this->db->query("UPDATE usuario set estado=0 where usuario_tipo_id=2 AND id=$id ");
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }

    public function estadoOut($id) {
        $this->db->query("UPDATE usuario set estado=2 where usuario_tipo_id=2 AND id=$id ");
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }

    public function estadoOn($id) {
        $this->db->query("UPDATE usuario set estado=1 where usuario_tipo_id=2 AND id=$id ");
        if ($this->db->affected_rows() === 1) {
            return true;
        }
        return null;
    }

    public function getEmpresa($id)
    {
        $query=$this->db->query("SELECT empresa from empresa WHERE id=(SELECT empresa_id FROM mecanico WHERE usuario_id=(SELECT mecanico_usuario_id from inspeccion where id=$id))");
        if ($this->db->affected_rows() === 1) {
            $empresa=$query->row_array();
            return $empresa['empresa'];
        }
        return null;
    }

    public function getEmpresaPorId($id)
    {
        $query=$this->db->query("SELECT empresa from empresa WHERE id=$id");
        if ($this->db->affected_rows() === 1) {
            $empresa=$query->row_array();
            return $empresa['empresa'];
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
            'comuna_id' => $mecanico['comuna_id']
        );
    }

    private function _setMecanicoUpdate($mecanico) {
        if(isset($mecanico['imagen']) && isset($mecanico['rut'])){
            $this->subirFoto($mecanico['imagen'], $mecanico['rut']);
        }
        if(isset($mecanico['password']) && ($mecanico['password'] != '')){
            $clave = password_hash($mecanico["password"], PASSWORD_DEFAULT);
            return array(
            'password' => $clave,
            'direccion' => $mecanico['direccion'],
            'comuna_id' => $mecanico['comuna_id']
        );
        }else{
            return array(
            'direccion' => $mecanico['direccion'],
            'comuna_id' => $mecanico['comuna_id']
        );
        }
        
        
    }

    private function _setUsuario($usuario) {
        return array(
            'rut' => $usuario['rut'],
            'dv' => $usuario['dv'],
            'nombres' => $usuario['nombres'],
            'apellidos' => $usuario['apellidos'],
            'email' => $usuario['email'],
            'celular' => $usuario['celular'],
            'usuario_tipo_id' => 2,
            'empresa_id' => $usuario['empresa_id']
        );
    }

    private function _setUsuarioUpdate($usuario) {
        /* 'rut' => $usuario['rut'],
          'dv' => $usuario['dv'], */
        return array(
            'nombres' => $usuario['nombres'],
            'apellidos' => $usuario['apellidos'],
            'email' => $usuario['email'],
            'celular' => $usuario['celular'],
            'estado' => 1,
            'empresa_id' => $usuario['empresa_id']
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

     private function agregarImagen($ruta) {
        $imgreal = file_get_contents($ruta);
        $imgstr = base64_encode($imgreal);
        return $imgstr;
    }

    private function subirFoto($imagen, $nombre) {
        //$urlnombre = explode(".", $_SERVER['HTTP_HOST']);
        //$subdominio = $urlnombre[0];
        $image_path = 'anexos/fotosmecanico/';
        if (!file_exists($image_path)) {
            mkdir($image_path, 0777);
        }
        $baseImagen = $imagen;
        $data = base64_decode($baseImagen);
        $ruta = $image_path . $nombre . '.jpg';
        file_put_contents($ruta, $data);

        return $ruta;
    }


}
