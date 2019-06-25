<?php
		
namespace BillarBundle\Controller;

use BillarBundle\Entity\Mesa;
use BillarBundle\Entity\Cuenta;
use BillarBundle\Entity\Cliente;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    public function homeAction()
    {
        return $this->render('BillarBundle:Default:home.html.twig');        
    }

    public function karaokeSearchAction()
    {
        return $this->render('BillarBundle:Default:karaokeSearch.html.twig');        
    }

    public function indexAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

   		$em = $this->getDoctrine()->getManager();

        $mesas_billar   = $em->getRepository('BillarBundle:Mesa')->findByTipo('Billar');
        $mesas_ocupadas = $em->getRepository('BillarBundle:Mesa')->findBy(
            array('tipo'=>'Billar', 'status'=>1)
        );
        $productos      = $em->getRepository('BillarBundle:Producto')->findBy(
                                array('tipoProducto' => 'Consumible', 'status' => 1)
                            );

        $cuenta         = new Cuenta();
        $formCuenta     = $this->createForm('BillarBundle\Form\CuentaType', $cuenta);

        $cliente        = new Cliente();
        $formCliente    = $this->createForm('BillarBundle\Form\ClienteType', $cliente);

        return $this->render('BillarBundle:Default:index.html.twig',
        	array(
        		"mesasbillar"   =>$mesas_billar,
                "mesasocupadas" =>$mesas_ocupadas,
                "productos"     =>$productos,
                "cuenta"        =>$cuenta,
                "formCuenta"    =>$formCuenta->createView(),      
                "cliente"       =>$cliente,
                "formCliente"   =>$formCliente->createView(),
        	)
        );
    }
}
