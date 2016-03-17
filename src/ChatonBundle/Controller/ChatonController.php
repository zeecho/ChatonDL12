<?php

namespace ChatonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ChatonBundle\Entity\Chaton;
use ChatonBundle\Form\ChatonType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Chaton controller.
 *
 * @Route("/chaton")
 */
class ChatonController extends Controller
{
    /**
     * Lists all Chaton entities.
     *
     * @Route("/", name="chaton_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $chatons = $em->getRepository('ChatonBundle:Chaton')->findAll();

        return $this->render('chaton/index.html.twig', array(
            'chatons' => $chatons,
        ));
    }

    /**
     * Creates a new Chaton entity.
     *
     * @Route("/new", name="chaton_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $chaton = new Chaton();
        $form = $this->createForm('ChatonBundle\Form\ChatonType', $chaton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($chaton);
            $em->flush();

            return $this->redirectToRoute('chaton_show', array('id' => $chaton->getId()));
        }

        return $this->render('chaton/new.html.twig', array(
            'chaton' => $chaton,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Chaton entity.
     *
     * @Route("/{id}", name="chaton_show")
     * @Method("GET")
     */
    public function showAction(Chaton $chaton)
    {
        $deleteForm = $this->createDeleteForm($chaton);

        return $this->render('chaton/show.html.twig', array(
            'chaton' => $chaton,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and makes a Chaton meow.
     *
     * @Route("/{id}/meow", name="chaton_meow")
     * @Method("GET")
     */
    public function meowAction(Chaton $chaton)
    {
        return new Response($chaton->miaou());
    }

    /**
     * Finds and makes a Chaton eat.
     *
     * @Route("/{id}/eat", name="chaton_eat")
     * @Method("GET")
     */
    public function eatCroquettesAction(Chaton $chaton)
    {
        return new Response($chaton->eatCroquettes());
    }

    /**
     * Displays a form to edit an existing Chaton entity.
     *
     * @Route("/{id}/edit", name="chaton_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Chaton $chaton)
    {
        $deleteForm = $this->createDeleteForm($chaton);
        $editForm = $this->createForm('ChatonBundle\Form\ChatonType', $chaton);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($chaton);
            $em->flush();

            return $this->redirectToRoute('chaton_edit', array('id' => $chaton->getId()));
        }

        return $this->render('chaton/edit.html.twig', array(
            'chaton' => $chaton,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Chaton entity.
     *
     * @Route("/{id}", name="chaton_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Chaton $chaton)
    {
        $form = $this->createDeleteForm($chaton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($chaton);
            $em->flush();
        }

        return $this->redirectToRoute('chaton_index');
    }

    /**
     * Creates a form to delete a Chaton entity.
     *
     * @param Chaton $chaton The Chaton entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Chaton $chaton)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('chaton_delete', array('id' => $chaton->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
