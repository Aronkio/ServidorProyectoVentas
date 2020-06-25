<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once ( APPPATH.'/libraries/REST_controller.php');
use Restserver\libraries\REST_controller;

class Personal extends REST_Controller
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


        $query = $this->db->query ('SELECT * FROM `personal` limit '.$pagina.',10 ');
        $respuesta = array(
            'error'=>FALSE,
            'productos' => $query-> result_array()
        );
        $this->response( $respuesta);

    }
    public function  buscar_get($termino = 0){

        $query = $this->db->query ("SELECT * FROM `personal` where CodigoEmpleado_Personal like '%".$termino."%'");
        $respuesta = array(
            'error'=>FALSE,
            'termino'=> $termino,
            'productos' => $query-> result_array());
        $this->response( $respuesta);


    }




}