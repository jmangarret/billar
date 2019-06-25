<?php

namespace BillarBundle\Controller;

use BillarBundle\Entity\Promocion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Promocion controller.
 *
 */
class PromocionController extends Controller
{
    /**
     * Lists all promocion entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $promocions = $em->getRepository('BillarBundle:Promocion')->findAll();

        return $this->render('BillarBundle:promocion:index.html.twig', array(
            'promocions' => $promocions,
        ));
    }

    /**
     * Creates a new promocion entity.
     *
     */
    public function newAction(Request $request)
    {
        $promocion = new Promocion();
        $form = $this->createForm('BillarBundle\Form\PromocionType', $promocion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            //$promocion->idProducto=$promocion->getIdProducto()->getId();
            //echo "<pre>";var_dump($promocion->getIdProducto());
            //$promocion->idProducto=$promocion->getProducto();

            $em = $this->getDoctrine()->getManager();
            $em->persist($promocion);
            $em->flush();

            return $this->redirectToRoute('promocion_show', array('id' => $promocion->getId()));
        }

        return $this->render('BillarBundle:promocion:new.html.twig', array(
            'promocion' => $promocion,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a promocion entity.
     *
     */
    public function showAction(Promocion $promocion)
    {
        $deleteForm = $this->createDeleteForm($promocion);

        return $this->render('BillarBundle:promocion:show.html.twig', array(
            'promocion' => $promocion,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing promocion entity.
     *
     */
    public function editAction(Request $request, Promocion $promocion)
    {
        $deleteForm = $this->createDeleteForm($promocion);
        $editForm = $this->createForm('BillarBundle\Form\PromocionType', $promocion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
           // $promocion->idProducto=$promocion->getIdProducto()->getId();

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('promocion_edit', array('id' => $promocion->getId()));
        }

        return $this->render('BillarBundle:promocion:edit.html.twig', array(
            'promocion' => $promocion,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a promocion entity.
     *
     */
    public function deleteAction(Request $request, Promocion $promocion)
    {
        $form = $this->createDeleteForm($promocion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($promocion);
            $em->flush();
        }

        return $this->redirectToRoute('promocion_index');
    }

    /**
     * Creates a form to delete a promocion entity.
     *
     * @param Promocion $promocion The promocion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Promocion $promocion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('promocion_delete', array('id' => $promocion->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
