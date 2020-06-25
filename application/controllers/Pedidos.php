<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once ( APPPATH.'/libraries/REST_controller.php');
use Restserver\libraries\REST_controller;

class Pedidos extends REST_Controller
{


    public function __construct()

    {
        header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        header("Access-Control-Allow-Origin: *");

        parent::__construct();
        $this->load->database();
    }

    public function realizar_orden_post($token=0, $id_usuario =0, $usuariocompra=0){

        $data =$this->post();

        if ($token=="0" || $id_usuario =="0"){

            $respuesta = array('error'=>TRUE,"Token Invalido / Usuario invalido");
            $this->response ($respuesta, REST_controller::HTTP_BAD_REQUEST );
            return;
        }

        if ( !isset( $data["items"])|| strlen( $data['items'] )== 0){
            $respuesta = array('error'=>TRUE,"Falta Informacion para POST");
            $this->response ($respuesta, REST_controller::HTTP_BAD_REQUEST );
            return;
        }

        $condiciones = array('Id_Personal'=> $id_usuario, 'Token_Personal'=> $token);
        $this->db->where ($condiciones);
        $query = $this->db->get('Personal');

        $existe =$query->row();

        if ( !$existe){
            $respuesta = array('error'=>TRUE,"Usuario y/o Incorrectos");
            $this->response ($respuesta, REST_controller::HTTP_UNAUTHORIZED );
            return;

        }

        $this->db->reset_query();

        $insertar = array( 'Personal_id' => $id_usuario, 'Usuario_id' =>$usuariocompra);
        $this->db->insert('ordenes', $insertar);
        $orden_id =$this->db->insert_id();

        $this->db->reset_query();

        $items = explode( ',', $data ['items']);

        foreach ( $items as &$producto_id){
            $data_insertar = array('Producto_id' => $producto_id, 'orden_id' =>$orden_id);
            $this->db->insert('ordenes_detalle', $data_insertar);

        }
        $respuesta = array('error'=>FALSE,'orden_id' =>$orden_id);



        $this->response($respuesta);



    }

    public function obtener_pedidos_get($token ="0", $id_usuario="0"){
        if ($token=="0" || $id_usuario =="0"){

            $respuesta = array('error'=>TRUE,"Token Invalido / Usuario invalido");
            $this->response ($respuesta, REST_controller::HTTP_BAD_REQUEST );
            return;
        }
        $condiciones = array('Id_Personal'=> $id_usuario, 'Token_Personal'=> $token);
        $this->db->where ($condiciones);
        $query = $this->db->get('Personal');

        $existe =$query->row();

        if ( !$existe){
            $respuesta = array('error'=>TRUE,"Usuario y/o Incorrectos");
            $this->response ($respuesta, REST_controller::HTTP_UNAUTHORIZED );
            return;

        }

        $query = $this->db->query('SELECT * FROM `ordenes` where personal_id =' .$id_usuario );

        $ordenes =array();

        foreach ($query->result() as $row ){

            $query_detalle =$this->db->query('SELECT b.codigo,a.Id_orden_detalle,a.orden_id,a.producto_id,b.producto,c.Nombre_Proveedor,c.Id_Proveedor,b.descripcion,b.precio_compra,b.Disponible FROM `ordenes_detalle` a inner join productos b on a.producto_id = b.codigo inner join proveedores c on b.proveedor = c.Id_Proveedor where orden_id ='.$row->Id_Orden);
            $orden = array('id'=> $row->Id_Orden,'creado_en'=>$row->creado_en,'detalle'=>$query_detalle->result());

            array_push($ordenes,$orden);
        }
        $respuesta = array('error' => FALSE, 'ordenes'=> $ordenes);

        $this->response($respuesta);

    }
    public function borrar_pedido_delete($token ="0", $id_usuario ="0",$orden_id = "0"){

        if ($token=="0" || $id_usuario =="0"|| $orden_id=="0"){

            $respuesta = array('error'=>TRUE,"Token Invalido / Usuario invalido");
            $this->response ($respuesta, REST_controller::HTTP_BAD_REQUEST );
            return;
        }
        $condiciones = array('Id_Personal'=> $id_usuario, 'Token_Personal'=> $token);
        $this->db->where ($condiciones);
        $query = $this->db->get('Personal');

        $existe =$query->row();

        if ( !$existe){
            $respuesta = array('error'=>TRUE,"Usuario y/o Incorrectos");
            $this->response ($respuesta, REST_controller::HTTP_UNAUTHORIZED );
            return;

        }
        $this->db->reset_query();
        $condiciones = array('id_orden' => $orden_id, 'Personal_id'=>$id_usuario );
        $this->db->where($condiciones);
        $query =$this->db->get ('ordenes');

        $existe = $query->row();

        if (!$existe){
            $respuesta = array('error'=>TRUE,"Imposible de Eliminar");
            return;

        }

        $condiciones = array ('id_orden' => $orden_id);
        $this->db->delete ('ordenes',$condiciones);

        $condiciones = array ('orden_id' => $orden_id);
        $this->db->delete('ordenes_detalle', $condiciones);

        $respuesta = array ('error'=>FALSE, 'mensaje'=>'Orden Eliminada');

        $this->response($respuesta);




    }


}