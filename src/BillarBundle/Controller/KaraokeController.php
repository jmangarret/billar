<?php
		
namespace BillarBundle\Controller;

use BillarBundle\Entity\Mesa;
use BillarBundle\Entity\Cuenta;
use BillarBundle\Entity\Cliente;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class KaraokeController extends Controller
{
    public function searchAction()
    {
        //return $this->render('BillarBundle:youtube:search.html.php', array());        
        return $this->render('BillarBundle:Default:karaokeSearch.html.twig', array());        
    }    

    public function searchResultsAction()
    {
        return $this->render('BillarBundle:youtube:search.html.php', array());        
    }  

    public function listAction()
    {
        return $this->render('BillarBundle:youtube:list.html.php', array());        
    } 
    public function addvideoAction()
    {
        return $this->render('BillarBundle:youtube:addvideo.html.php', array());        
    }   

    public function oauth2callbackAction(Request $request)
    {
        $code = $request->get('code');        

        return $this->render('BillarBundle:youtube:oauth2callback.html.php',array('code'=>$code));        
    }

    public function indexAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

   		$em = $this->getDoctrine()->getManager();

        $mesas_karaoke  = $em->getRepository('BillarBundle:Mesa')->findByTipo('Karaoke');
        $mesas_ocupadas = $em->getRepository('BillarBundle:Mesa')->findBy(
            array('tipo'=>'Karaoke', 'status'=>1)
        );
        $productos      = $em->getRepository('BillarBundle:Producto')->findBy(
            array('tipoProducto' => 'Consumible', 'status' => 1)
        );

        $cuenta         = new Cuenta();
        $formCuenta     = $this->createForm('BillarBundle\Form\CuentaType', $cuenta);

        $cliente        = new Cliente();
        $formCliente    = $this->createForm('BillarBundle\Form\ClienteType', $cliente);

        return $this->render('BillarBundle:Default:karaokeIndex.html.twig',
        	array(
        		"mesaskaraoke"   =>$mesas_karaoke,
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
