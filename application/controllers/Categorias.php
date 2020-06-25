<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once ( APPPATH.'/libraries/REST_controller.php');
use Restserver\libraries\REST_controller;

class Categorias extends REST_Controller
{


    public function __construct()

    {
        header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        header("Access-Control-Allow-Origin: *");

        parent::__construct();
        $this->load->database();
    }

    public function index_get ($pagina = 0){

        $pagina = $pagina *10;


        $query = $this->db->query ('SELECT * FROM `categorias_productos` limit '.$pagina.',10 ');
        $respuesta = array(
            'error'=>FALSE,
            'categorias' => $query-> result_array()
        );
        $this->response( $respuesta);


    }
}
