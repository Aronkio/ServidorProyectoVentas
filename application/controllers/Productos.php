<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once ( APPPATH.'/libraries/REST_controller.php');
use Restserver\libraries\REST_controller;

class Productos extends REST_Controller
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


        $query = $this->db->query ('SELECT * FROM `productos` a INNER JOIN proveedores b on a.proveedor = b.Id_Proveedor  limit '.$pagina.',10 ');
        $respuesta = array(
            'error'=>FALSE,
            'productos' => $query-> result_array()
        );
        $this->response( $respuesta);


    }

    public function  buscar_get($termino){

                {$query = $this->db->query ("SELECT * FROM `productos` a INNER JOIN proveedores b on a.proveedor = b.Id_Proveedor where a.producto like  '%".$termino."%'");
                $respuesta = array(
                    'error'=>FALSE,
                    'termino'=> $termino,
                    'productos' => $query-> result_array());
                $this->response( $respuesta);

            }




    }


    public function  por_tipo_get($termino){

        {$query = $this->db->query ("SELECT * FROM `productos` where categoria like '%".$termino."%'");
            $respuesta = array(
                'error'=>FALSE,
                'termino'=> $termino,
                'productos' => $query-> result_array());
            $this->response( $respuesta);

        }




    }

}