<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Comuna extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('comuna_model');
    }

    public function index_get() {
        $comuna = $this->comuna_model->get();
        if (!is_null($comuna)) {
            $this->response(array('status'=>TRUE,'comuna' => $comuna), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status'=>FALSE,'error' => 'No hay comunas en la base de datos...'), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function find_get($id) {
        if (!$id) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $comuna = $this->comuna_model->get($id);
        if (!is_null($comuna)) {
            $this->response(array('status'=>TRUE,'comuna' => $comuna), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status'=>FALSE,'error' => 'comuna no encontrado...'), REST_Controller::HTTP_NOT_FOUND);
        }
    }
    

    public function index_post() {
        if (!$this->post('comuna')) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $id = $this->comuna_model->save($this->post('comuna'));
        if (!is_null($id)) {
            $this->response(array('status'=>TRUE,'comuna' => $id), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status'=>FALSE,'error'=> 'Algo se ha roto en el servidor...'), REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function index_put($id) {
        if (!$this->put('comuna')) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $update = $this->comuna_model->update($id, $this->put('comuna'));
        if (!is_null($update)) {
            $this->response(array('status'=>TRUE,'comuna' => 'comuna actualizado!'), REST_Controller::HTTP_OK);
        } else {
            $this->response(array('status'=>FALSE,'error'=> 'Algo se ha roto en el servidor...'), REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function index_delete($id) {
            if (!$id) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $delete = $this->comuna_model->delete($id);
            if (!is_null($delete)) {
                $this->response(array('status'=>TRUE,'comuna' => 'comuna eliminado!'), REST_Controller::HTTP_OK);
            } else {
                $this->response(array('status'=>FALSE,'error'=> 'Algo se ha roto en el servidor...'), REST_Controller::HTTP_BAD_REQUEST);
            }
    }

}
