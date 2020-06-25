<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once ( APPPATH.'/libraries/REST_controller.php');
use Restserver\libraries\REST_controller;

class Usuarios extends REST_Controller
{


    public function __construct()

    {
        header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        header("Access-Control-Allow-Origin: *");

        parent::__construct();
        $this->load->database();
    }
    public function todos_get ($pagina = 0){

        $pagina = $pagina *10;


        $query = $this->db->query ('SELECT Id_Usuario,CONCAT(PrimerNombre_Usuario,\' \',SegundoNombre_Usuario,\' \',PrimerNombre_Usuario,\' \',SegundoNombre_Usuario)as NombreCompleto, Correo_Usuario,Telefono,Direccion FROM `usuarios`  limit '.$pagina.',10 ');
        $respuesta = array(
            'error'=>FALSE,
            'usuarios' => $query-> result_array()
        );
        $this->response( $respuesta);

    }
    public function  buscar_get($termino = 0){

        $query = $this->db->query ("SELECT * FROM `usuarios` where PrimerNombre_Usuario like '%".$termino."%'");
        $respuesta = array(
            'error'=>FALSE,
            'termino'=> $termino,
            'productos' => $query-> result_array());
        $this->response( $respuesta);


    }




}