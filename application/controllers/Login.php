<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once ( APPPATH.'/libraries/REST_controller.php');
use Restserver\libraries\REST_controller;

class Login extends REST_Controller
{


    public function __construct()

    {
        header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        header("Access-Control-Allow-Origin: *");

        parent::__construct();
        $this->load->database();
    }

    public function index_post (){

        $data = $this->post();

        if ( !isset ( $data ['correo'] ) OR !isset ( $data ['contrasena'] )) {
            $respuesta = array('error'=> TRUE, 'mensaje'=>'Informacion Incorrecta');
            $this->response ($respuesta, REST_controller::HTTP_BAD_REQUEST);
            return;

        }
        $condiciones = array('correo_personal' => $data['correo'],'contrasena_personal'=>$data['contrasena']);

        $query = $this->db->get_where('Personal', $condiciones);
        $usuario = $query->row();

        if (!isset( $usuario)){
            $respuesta = array('error'=> TRUE, 'mensaje'=>'usuario/contrasenia invalidos');
            $this->response ($respuesta);
            return;
        }

        $token = bin2hex( openssl_random_pseudo_bytes(20) );
        $token = hash('ripemd160', $data ['correo']);

        $this->db->reset_query();

        $actualizar_token = array ('Token_Personal' => $token);
        $this->db->where ('Id_Personal', $usuario->Id_Personal);

        $hecho = $this->db->update ('Personal', $actualizar_token);

        $respuesta = array('error'=>FALSE,'Token_Personal'=> $token, 'Id_Usuario'=> $usuario->Id_Personal);



        $this->response($respuesta);

    }
}