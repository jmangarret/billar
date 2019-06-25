<?php

namespace BillarBundle\Controller;

use BillarBundle\Entity\Mesa;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Mesa controller.
 *
 */
class MesaController extends Controller
{
    /**
     * Lists all mesa entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $mesas = $em->getRepository('BillarBundle:Mesa')->findAll();

        return $this->render('BillarBundle:mesa:index.html.twig', array(
            'mesas' => $mesas,
        ));
    }

    /**
     * Creates a new mesa entity.
     *
     */
    public function newAction(Request $request)
    {
        $mesa = new Mesa();
        $form = $this->createForm('BillarBundle\Form\MesaType', $mesa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mesa);
            $em->flush();

            return $this->redirectToRoute('mesa_show', array('id' => $mesa->getId()));
        }

        return $this->render('BillarBundle:mesa:new.html.twig', array(
            'mesa' => $mesa,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a mesa entity.
     *
     */
    public function showAction(Mesa $mesa)
    {
        $deleteForm = $this->createDeleteForm($mesa);

        return $this->render('BillarBundle:mesa:show.html.twig', array(
            'mesa' => $mesa,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing mesa entity.
     *
     */
    public function editAction(Request $request, Mesa $mesa)
    {
        $deleteForm = $this->createDeleteForm($mesa);
        $editForm = $this->createForm('BillarBundle\Form\MesaType', $mesa);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mesa_show', array('id' => $mesa->getId()));
        }

        return $this->render('BillarBundle:mesa:edit.html.twig', array(
            'mesa' => $mesa,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a mesa entity.
     *
     */
    public function deleteAction(Request $request, Mesa $mesa)
    {
        $form = $this->createDeleteForm($mesa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($mesa);
            $em->flush();
        }

        return $this->redirectToRoute('mesa_index');
    }

    /**
     * Creates a form to delete a mesa entity.
     *
     * @param Mesa $mesa The mesa entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Mesa $mesa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('mesa_delete', array('id' => $mesa->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
