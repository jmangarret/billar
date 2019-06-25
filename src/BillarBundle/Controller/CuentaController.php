<?php

namespace BillarBundle\Controller;

use BillarBundle\Entity\Cuenta;
use BillarBundle\Entity\CuentaDetalle;
use BillarBundle\Form\CuentaDetalleType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use Symfony\Component\Validator\Constraints\DateTime;
//use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

/**
 * Cuenta controller.
 *
 */
class CuentaController extends Controller
{
    /**
     * Lists all Cuenta entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $cuentas = $em->getRepository('BillarBundle:Cuenta')->findAll();

        return $this->render('BillarBundle:cuenta:index.html.twig', array(
            'cuentas' => $cuentas,
        ));
    }

    /**
     * Creates a new Cuenta entity.
     *
     */
    public function newAction(Request $request)
    {
        $Cuenta = new Cuenta();
        $form = $this->createForm('BillarBundle\Form\CuentaType', $Cuenta);
        $form->handleRequest($request);
        
        $idUsuario = 1;
        $fecha_hoy = date('Y-m-d H:i:s');
        $fecha_hoy = new \DateTime("now");

        $em         = $this->getDoctrine()->getManager();
        $usuario    = $em->getRepository('BillarBundle:Usuario')->find($idUsuario);

        $Cuenta->setUsuario($usuario); 
        $Cuenta->setFechaActualizacion($fecha_hoy); 
        $Cuenta->setFechaCreacion($fecha_hoy); 
        $Cuenta->setStatus(1); 

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($Cuenta);
            $em->flush();
            //Actualizamos mesa a status ocupada
            $idmesa     = $Cuenta->getMesa()->getId();
            $em_mesa    = $this->getDoctrine()->getManager();
            $mesa       = $em_mesa->getRepository('BillarBundle:Mesa')->find($idmesa);
            $mesa       ->setStatus(1);
            $em_mesa    ->flush();    
           //Si la cuenta fue creada desde el front via modal ajax agregamos detalle producto tiempo
            if($request->isXmlHttpRequest())
            {
                //Buscamos producto Horas de Billar - Id 1
                $idproducto         = $this->getProductoTiempo();
                $Producto           = $em->getRepository('BillarBundle:Producto')->find($idproducto);

                $data['idmesa']     = $idmesa   = $Cuenta->getMesa()->getId();
                $data['idcuenta']   = $idcuenta = $Cuenta->getId();
                $data['idproducto'] = $idproducto= $Producto->getId();
                $data['precio']     = $precio   = $Producto->getValorVenta();
                $data['cantidad']   = $cantidad = 1;
                if (!$this->existeProductoTiempo($idmesa)){
                    //Inicializamos tiempo de la mesa
                    $now        = date('Y-m-d H:i:s');
                    $hDesde     = date('H:i',strtotime($now));
                    $hHasta     = date('H:i',strtotime($now."+ 1 minute")); //+1 para que no retorne cero 
                    $totalHoras = $this->getTiempo($idproducto,$hDesde,$hHasta);
                    $cantidadhn = $totalHoras['HN'];//Horas Normales
                    $preciohn   = $totalHoras['PN'];//Precio Horas Normal
                    if ($cantidadhn>0)
                    $this       ->setCuentaDetalle($idcuenta,$idproducto,$cantidadhn,$preciohn,'N'); 
                    $cantidadhp = $totalHoras['HP'];//Horas Promocion
                    $preciohp   = $totalHoras['PP'];//Precio Horas Promo
                    if ($cantidadhp>0)
                    $this       ->setCuentaDetalle($idcuenta,$idproducto,$cantidadhp,$preciohp,'P');                
                }
                //Devolvemos respuesta ajax
                $encoders       = array(new JsonEncoder());
                $normalizers    = array(new ObjectNormalizer());
                $serializer     = new Serializer($normalizers, $encoders);         
                $response       = new JsonResponse();
                $response       ->setStatusCode(200);
                $response       ->setData(
                    array(
                        'response'  => 'success',
                        'idmesa'    => $idmesa,
                        'data'      => $serializer->serialize($data, 'json'),
                    )
                );
                return $response;
            }else{
                //Registro normal
                return $this->redirectToRoute('cuenta_show', array('id' => $Cuenta->getId()));
            }            
        }

        return $this->render('BillarBundle:cuenta:new.html.twig', array(
            'Cuenta' => $Cuenta,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Cuenta entity.
     *
     */
    public function showAction(Cuenta $Cuenta)
    {
        $deleteForm = $this->createDeleteForm($Cuenta);

        $em         = $this->getDoctrine()->getManager();
        //Buscamos detalle de la cuenta, el maestro es recibido por parametro
        $idCuenta   = $Cuenta->getId();
        $detalle    = $em->getRepository('BillarBundle:CuentaDetalle')->findByCuenta($idCuenta);

        return $this->render('BillarBundle:cuenta:show.html.twig', array(
            'Cuenta' => $Cuenta,
            'detalle' => $detalle,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays Cuenta by Mesa.
     *
     */
    public function mesaAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $encoders   = array(new JsonEncoder());
            $normalizers= array(new ObjectNormalizer());

            $serializer = new Serializer($normalizers, $encoders);
            $idmesa     = $request->get('id');
            $em         = $this->getDoctrine()->getManager();
            //Buscamos si la mesa tiene cuentas activas 
            $cuenta     = $em->getRepository('BillarBundle:Cuenta')->findBy(
                array('mesa' => $idmesa, 'status' => 1)
            );
            $cuentas    = [];
            foreach ($cuenta as $key => $value){
                $idcuenta   = $value->getId();
                $cuentas[]  = $idcuenta; 
                $this       ->updateTiempoMesa($idcuenta,false);               
            }
            $detalle    = $em->getRepository('BillarBundle:CuentaDetalle')->findBy(
                array('cuenta'=>$cuentas),
                array('cuenta' => 'ASC', 'id'=> 'ASC')
            );

            $response   = new JsonResponse();
            $response   ->setStatusCode(200);
            $response   ->setData(array(
                'response'  => 'success',
                'idmesa'    => $idmesa,
                'cuenta'    => $serializer->serialize($cuenta, 'json'),
                'detalle'   => $serializer->serialize($detalle, 'json'),
            ));
            return $response;
        }
    }      

    /**
     * Agrega nuevo producto a Cuenta Detalle via Ajax.
     *
     */
    public function addProductoAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $encoders   = array(new JsonEncoder());
            $normalizers= array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
            //Obtenemos variables del formulario via ajax y las reasignamos a un array para retornarlas
            $data["idcuenta"]   = $idcuenta   = json_decode($request->get('idcuenta'));
            $data["idmesa"]     = $idmesa     = json_decode($request->get('idmesa'));
            $data["idproducto"] = $idproducto = json_decode($request->get('idproducto'));
            $data["cantidad"]   = $cantidad   = json_decode($request->get('cantidad'));
            $data["precio"]     = $precio     = json_decode($request->get('precio'));            
            //Verificamos si el productos esta en Promocion a la hora actual          
            $now        = date('Y-m-d H:i:s');
            $hDesde     = date('H:i',strtotime($now));
            if ($this->esHoraPromo($idproducto,$hDesde)){
                $em             = $this     ->getDoctrine()->getManager();
                $promocion      = $em       ->getRepository('BillarBundle:Promocion')->findByProducto($idproducto);
                $data["precio"] = $precio   = $promocion[0] ->getValorVenta();                
                $this           ->setCuentaDetalle($idcuenta,$idproducto,$cantidad,$precio,'P');
            }else{
                $this           ->setCuentaDetalle($idcuenta,$idproducto,$cantidad,$precio,'N');
            }
            //enviamos response
            $response   = new JsonResponse();
            $response   ->setStatusCode(200);
            $response   ->setData(array(
                'response' => 'success',
                'data' => $data,
            ));
            return $response;
        }
    }      

    /**
     * Update time Mesa by Cuenta.
     *
     */
    public function updateTiempoMesa($idcuenta,$nuevaMesa=false)
    {
        //Verificamos si la cuenta tiene producto tiempo
        $idproducto = $this->getProductoTiempo();
        $em         = $this->getDoctrine()->getManager();
        $detalle    = $em->getRepository('BillarBundle:CuentaDetalle')->findBy(
                array('cuenta' => $idcuenta, 'producto' => $idproducto)
            );
        if ($detalle || $nuevaMesa){
            //Buscamos la fecha/hora de creacion de la cuenta 
            $cuenta     = $em->getRepository('BillarBundle:Cuenta')->findBy(
                array('id' => $idcuenta, 'status' => 1)
            );
            $created    = $cuenta[0]->getFechaCreacion();
            $idmesa     = $cuenta[0]->getMesa()->getId();
            //Actualizamos tiempo transcurrido en el detalle de la cuenta, producto: Horas de billar/tiempo
            $now        = date('Y-m-d H:i:s');
            $hDesde     = date('H:i',$created->getTimestamp());
            $hHasta     = date('H:i',strtotime($now));
            $totalHoras = $this->getTiempo($idproducto,$hDesde,$hHasta);
            $cantidadhn = $totalHoras['HN'];//Horas Normales
            $preciohn   = $totalHoras['PN'];//Precio Horas Normal
            if ($cantidadhn>0)
            $this       ->setCuentaDetalle($idcuenta,$idproducto,$cantidadhn,$preciohn,'N'); 
            //Datos Promocion
            $cantidadhp = $totalHoras['HP'];//Horas Promocion
            $preciohp   = $totalHoras['PP'];//Precio Horas Promo
            if ($cantidadhp>0)
            $this       ->setCuentaDetalle($idcuenta,$idproducto,$cantidadhp,$preciohp,'P');             
        }

        return true;
        
    }      

    /**
     * Update o Created Nuevo Detalle.
     *
     */
    public function setCuentaDetalle($idcuenta,$idproducto,$cantidad,$precio,$tipo)
    {   
        //Buscamos si el producto seleccionado ya fue agregado a la cuenta
        $em         = $this->getDoctrine()->getManager();
        $cuenta     = $em->getRepository('BillarBundle:Cuenta')->find($idcuenta);
        $producto   = $em->getRepository('BillarBundle:Producto')->find($idproducto);
        $tipoProducto= $producto->getTipoProducto();           
        $detalle    = $em->getRepository('BillarBundle:CuentaDetalle')->findBy(
            array('cuenta' => $idcuenta, 'producto' => $idproducto, 'tipoPrecio' => $tipo)
        );
        $nuevoPrecio = false;
        //Si ya fue agregado el producto a la misma cuenta
        if ($detalle){ 
            //Si el tipo de precio es (P)romocion, obtenemos su ultimo precio calculado en el detalle, sino de Producto
            if ($tipo=='P'){
                $precioActual = $detalle[0]->getPrecio();
            }else{
                $precioActual = $producto->getValorVenta();
            }
            //Si ya fue agregado el producto pero es diferente el nuevo precio
            if ($precio>0 && $precioActual<>$precio){
                $nuevoPrecio    = true;
                $precio         = $precioActual;
            }else{
                /////////////////SE DESHABILITA MODALIDAD ACTUALIZANDO CANTIDADES DE PEDIDOS DE OTROS PRODUCTOS  
                //Sumamos cantidad anterior si es producto diferente a tipo tiempo
                //if ($tipoProducto<>'Tiempo') {
                //    $cantidad   = $detalle[0]->getCantidad() + $cantidad;
                //}
                /////////////////////////////////
                //Actualizamos solo SI es Tipo Tiempo
                if ($tipoProducto=='Tiempo') {
                    $detalle[0] ->setCantidad($cantidad);
                    $detalle[0] ->setPrecio($precio);
                    $em         ->flush();
                } 
            }
        }            

        $idmesa = $cuenta->getMesa()->getId();
        //Si no ha sido agregado el producto o cambio de precio
        if (!$detalle || $nuevoPrecio || $tipoProducto<>'Tiempo'){
            //Creamos un item nuevo con el producto seleccionado - insert
            $detalle    = new CuentaDetalle;
            $detalle    ->setCuenta($cuenta);
            $detalle    ->setProducto($producto);    
            $detalle    ->setCantidad($cantidad);
            $detalle    ->setPrecio($precio);            
            $detalle    ->setTipoPrecio($tipo);            
            $em         ->persist($detalle);
            $em         ->flush(); 
        }
    }

    /**
     * Update o Created Nuevo Detalle.
     *
     */
    public function getCuentaDetalle($idcuenta)   
    {
        $data    = [];
        $em      = $this->getDoctrine()->getManager();
        $cuenta  = $em->getRepository('BillarBundle:Cuenta')->find($idcuenta);
        if ($cuenta){ 
            $data['id']         = $cuenta->getId();
            $data['idmesa']     = $cuenta->getMesa()->getId();
            $data['mesa']       = $cuenta->getMesa()->getNombre();
            $data['idusuario']  = $cuenta->getUsuario()->getId();
            $data['usuario']    = $cuenta->getUsuario()->getNombre();
            $data['idcliente']  = $cuenta->getCliente()->getId();
            $data['cliente']    = $cuenta->getCliente()->getNombre();
            $data['creacion']   = $cuenta->getFechaCreacion();
            //Obtenemos detalle
            $detalle            = $em->getRepository('BillarBundle:CuentaDetalle')->findByCuenta($idcuenta);
            $i = 0;
            foreach ($detalle as $d) {
                $data['detalle'][$i]['idcuenta']    = $idcuenta;
                $data['detalle'][$i]['idproducto']  = $d->getProducto()->getId();
                $data['detalle'][$i]['producto']    = $d->getProducto()->getNombre();
                $data['detalle'][$i]['cantidad']    = $d->getCantidad();
                $data['detalle'][$i]['precio']      = $d->getPrecio();
                $data['detalle'][$i]['tipo']        = $d->getTipoPrecio();
                $data['detalle'][$i]['total']       = $d->getCantidad() * $d->getPrecio();
                $i++;
            }
        }

        //Array collection die($cuenta->detall);
        return $data;
    }

    /**
     * Function para validar si una mesa ya tiene producto tiempo.
     *
     */
    public function existeProductoTiempo($idmesa) {
        $em             = $this->getDoctrine()->getManager();
        $cuenta         = $em->getRepository('BillarBundle:Cuenta')->findBy(
                            array('mesa'=>$idmesa, 'status'=>1)
                        );
        foreach ($cuenta as $c) {
            $idproducto     = $this->getProductoTiempo();
            $idcuenta       = $c->getId();
            $existedetalle  = $em->getRepository('BillarBundle:CuentaDetalle')->findBy(
                array('cuenta'=>$idcuenta, 'producto'=>$idproducto)
            );
            $esmesabillar   = $em->getRepository('BillarBundle:Mesa')->findBy(
                array('id'=>$idmesa, 'tipo'=>'Billar')
            );
            //No Agregar producto tiempo si ya lo tiene y si la mesa no es de Billar
            if ($existedetalle || !$esmesabillar){
                return true;
            }
        }
        return false;
    }

    /**
     * Function para obtener producto tiempo.
     *
     */
    public function getProductoTiempo() {
        $em             = $this->getDoctrine()->getManager();
        $producto       = $em->getRepository('BillarBundle:Producto')->findBy(
            array('tipoProducto'=>'Tiempo', 'status'=>1)
        );
        if ($producto){
            foreach ($producto as $p) {
                $idproducto   = $p->getId();
            }
            return $idproducto;
        }else{
            return false;
        }
    }

   /**
     * Function para obtener los datos de la mesa activa.
     *
     */
    public function getDatosMesaAction(Request $request) {
        if($request->isXmlHttpRequest())
        {

            $encoders   = array(new JsonEncoder());
            $normalizers= array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
            //Obtenemos detalles de la cuenta
            $idcuenta   = $request->get('id');
            $data       = $this->getCuentaDetalle($idcuenta);
            //enviamos response
            $response   = new JsonResponse();
            $response   ->setStatusCode(200);
            $response   ->setData(array(
                'response' => 'success',
                'cuenta' => $data,
            ));
            return $response;
        }
    }

    /**
     * Submit Form Pasar Cuenta a Otra Mesa.
     *
     */
    public function pasarCuentaAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $encoders   = array(new JsonEncoder());
            $normalizers= array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
            //Obtenemos objeto de la mesa seleccionada
            $em         = $this->getDoctrine()->getManager();
            $idmesa     = $request->get('idmesa');
            $mesa       = $em->getRepository('BillarBundle:Mesa')->find($idmesa);
            //Buscamos la cuenta a modificar
            $idcuenta   = $request->get('id');
            $cuenta     = $em->getRepository('BillarBundle:Cuenta')->find($idcuenta);
            //Obtenemos la mesa modificada antes de pasar la cuenta
            $idmesamod  = $cuenta->getMesa()->getId();
            //Pasamos la cuenta a la mesa indicada
            $now        = date('Y-m-d H:i:s');
            $cuenta     ->setMesa($mesa);
            $cuenta     ->setFechaActualizacion(new \DateTime($now));
            $mesa       ->setStatus(1);
            $em         ->flush();
            //Verificamos si la mesa modificada tiene mas cuentas activas para actualizar su status
            $cuenta2    = $em->getRepository('BillarBundle:Cuenta')->findBy(
                        array('mesa' => $idmesamod, 'status'=>1)
            );
            //Si hay resultados enviamos cero para no actualizar la mesa anterior porque tiene otras cuentas
            if ($cuenta2){
                $idmesamod = 0;
            }else{
                $mesamod    = $em->getRepository('BillarBundle:Mesa')->find($idmesamod);
                $mesamod    ->setStatus(0);
                $em         ->flush();
            }
            //enviamos response
            $response   = new JsonResponse();
            $response   ->setStatusCode(200);
            $response   ->setData(array(
                'response'  => 'success',
                'idmesa'    => $idmesa,
                'idmesamod' => $idmesamod,
                'idcuenta'  => $idcuenta,
            ));
            return $response;            
        }
    }   
    /**
     * Function para saber si una hora esta dentro del rango de horas de promocion.
     *
     */
    public function esHoraPromo($idproducto,$hora) {
        //Obtenenmos horas promos
        $em             = $this->getDoctrine()->getManager();
        $promocion      = $em->getRepository('BillarBundle:Promocion')->findByProducto($idproducto);
        if ($promocion){
            $pDesde     = $promocion[0]->getHoraDesde();    
            $pHasta     = $promocion[0]->getHoraHasta();    
            //Convertimos horas promos
            $pDesde     = date('H:i',strtotime($pDesde));  
            $pHasta     = date('H:i',strtotime($pHasta));  
            $promoDesde = \DateTime::createFromFormat('!H:i', $pDesde);
            $promoHasta = \DateTime::createFromFormat('!H:i', $pHasta);
            $dateHora   = \DateTime::createFromFormat('!H:i', $hora);
            //Si la hora promo inicial es mayor, la hora hasta corresponde al dia siguiente
            if ($promoDesde > $promoHasta) $promoHasta->modify('+1 day');
            return  ($promoDesde <= $dateHora && $dateHora <= $promoHasta) || 
                    ($promoDesde <= $dateHora->modify('+1 day') && $dateHora <= $promoHasta);
        }else{
            return false;
        }
    }
    
    /**
     * Function para obtener cant y total horas y si estan en promocion .
     *
     */
    public function getTiempo($idProducto,$hDesde,$hHasta)    
    {
        $minutos        = 0;    
        $nHorasNormal   = 0;   
        $nHorasPromo    = 0;   
        $totalNormal    = 0;   
        $totalPromo     = 0;
        $totalGeneral   = 0;   
        //Buscamos precio del producto hora/tiempo
        $em             = $this         ->getDoctrine()->getManager();
        $producto       = $em           ->getRepository('BillarBundle:Producto')->find($idProducto);
        $precioNormal   = $producto     ->getValorVenta();
        $promocion      = $em           ->getRepository('BillarBundle:Promocion')->findByProducto($idProducto);
        $precioPromo    = $promocion[0] ->getValorVenta();
        //Convertimos horas string a tiempo
        $horaDesde      = new \DateTime($hDesde);
        $horaHasta      = new \DateTime($hHasta);
        //Inicializamos horas con el mismo tiempo
        $horaInicio     = $horaDesde->format('H');
        $horaSiguiente  = $horaDesde->format('H');
        //Si la hora de inicio es mayor, la hora hasta corresponde al dia siguiente. Caso: despues de medianoche
        if ($horaDesde>$horaHasta) $horaHasta = $horaHasta->modify('+1 day');
        while ($horaDesde<=$horaHasta){
            $minutos        += 1;
            $horaDesde      ->modify("+1 minute");
            $horaSiguiente  = $horaDesde->format('H');
            if ($horaInicio<>$horaSiguiente || $horaDesde==$horaHasta){
                $horaDesde      ->modify("-1 minute"); //volvemos al minuto 59
                $hDesde         = $horaDesde->format('H:i');
                $idProducto     = $this->getProductoTiempo();
                if ($this->esHoraPromo($idProducto,$hDesde))
                {
                    $nHorasPromo    += $minutos;
                    $precio         =  $precioPromo * $minutos/60;
                    $totalPromo     += $precio;
                }
                else
                {
                    $nHorasNormal   += $minutos;
                    $precio         = $precioNormal * $minutos/60;
                    $totalNormal    += $precio;
                }
                $minutos            = 0;
                $totalGeneral       += $precio;
                $horaInicio         = $horaSiguiente;
                $horaDesde         ->modify("+1 minute");//volvemos al minuto retrocedido
                $hDesde             = $horaDesde->format('H:i');
            }           
        }

        $data['HN'] = $nHorasNormal/60;
        $data['HP'] = $nHorasPromo/60;
        $data['PN'] = $precioNormal;
        $data['PP'] = $precioPromo;
        $data['TN'] = $totalNormal;
        $data['TP'] = $totalPromo;
        $data['TG'] = $totalGeneral;
        
        return $data;
    }

    /**
     * Close Cuenta.
     *
     */
    public function closeAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $encoders   = array(new JsonEncoder());
            $normalizers= array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
            //Buscamos la cuenta 
            $em         = $this->getDoctrine()->getManager();
            $idcuenta   = $request->get('id');
            //Actualizamos tiempo y cerramos cuenta status 0
            $this       ->updateTiempoMesa($idcuenta);           
            $now        = date('Y-m-d H:i:s');
            $fechaCierre= new \DateTime($now);
            $cuenta     = $em->getRepository('BillarBundle:Cuenta')->find($idcuenta);
            $idmesa     = $cuenta->getMesa()->getId();
            $cuenta     ->setStatus(0);
            $cuenta     ->setFechaActualizacion($fechaCierre);
            $cuenta     ->setFechaCierre($fechaCierre);
            $em         ->flush();
            //Buscamos si tiene mas cuentas abiertas para cerrar la mesa.
            $cuentasopen= $em->getRepository('BillarBundle:Cuenta')->findBy(
                array("mesa" => $idmesa, "status" => 1)
            );
            $cerrar = 0;
            if (!$cuentasopen){
                //Actualizamos mesa a status disponible
                $mesa       = $em->getRepository('BillarBundle:Mesa')->find($idmesa);
                $mesa       ->setStatus(0);
                $em         ->flush();
                $cerrar     = 1;            
            }
            //enviamos response
            $response   = new JsonResponse();
            $response   ->setStatusCode(200);
            $response   ->setData(array(
                'response'  => 'success',
                'idmesa'    => $idmesa,
                'cerrarmesa'=> $cerrar,
            ));
            return $response;            
        }

    }   
  
    /**
     * Print an existing Cuenta entity.
     *
     */
    public function imprimirAction(Cuenta $Cuenta)
    {

        $idCuenta   = $Cuenta->getId();
        $em         = $this->getDoctrine()->getManager();
        $detalle    = $em->getRepository('BillarBundle:CuentaDetalle')->findByCuenta($idCuenta);
        return $this->render('BillarBundle:cuenta:imprimir.html.php', array(
            'Cuenta' => $Cuenta,
            'detalle' => $detalle,
     
        ));
    }

    /**
     * Displays a form to edit an existing Cuenta entity.
     *
     */
    public function editAction(Request $request, Cuenta $Cuenta)
    {

        //$dateConvert=$Cuenta->getFechaCreacion()->format('Y-m-d H:i:s');
        //$Cuenta->setFechaCreacion($dateConvert);

        $deleteForm = $this->createDeleteForm($Cuenta);
        $editForm = $this->createForm('BillarBundle\Form\CuentaType', $Cuenta);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cuenta_show', array('id' => $Cuenta->getId()));
        }

        return $this->render('BillarBundle:cuenta:edit.html.twig', array(
            'Cuenta' => $Cuenta,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Cuenta entity.
     *
     */
    public function deleteAction(Request $request, Cuenta $Cuenta)
    {
        $form = $this->createDeleteForm($Cuenta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($Cuenta);
            $em->flush();
        }

        return $this->redirectToRoute('cuenta_index');
    }

    /**
     * Creates a form to delete a Cuenta entity.
     *
     * @param Cuenta $Cuenta The Cuenta entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Cuenta $Cuenta)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cuenta_delete', array('id' => $Cuenta->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
