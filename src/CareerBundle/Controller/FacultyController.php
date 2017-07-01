<?php

namespace CareerBundle\Controller;

use CareerBundle\Entity\Faculty;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Faculty controller.
 *
 * @Route("faculty")
 */
class FacultyController extends Controller
{
    /**
     * Lists all faculty entities.
     *
     * @Route("/", name="faculty_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $faculties = $em->getRepository('CareerBundle:Faculty')->findAll();

        return $this->render('faculty/index.html.twig', array(
            'faculties' => $faculties,
        ));
    }

    /**
     * Creates a new faculty entity.
     *
     * @Route("/new", name="faculty_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newAction(Request $request)
    {
        $faculty = new Faculty();
        $form = $this->createForm('CareerBundle\Form\FacultyType', $faculty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($faculty);
            $em->flush();

            return $this->redirectToRoute('faculty_show', array('id' => $faculty->getId()));
        }

        return $this->render('faculty/new.html.twig', array(
            'faculty' => $faculty,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a faculty entity.
     *
     * @Route("/{id}", name="faculty_show")
     * @Method("GET")
     */
    public function showAction(Faculty $faculty)
    {
        $deleteForm = $this->createDeleteForm($faculty);

        return $this->render('faculty/show.html.twig', array(
            'faculty' => $faculty,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing faculty entity.
     *
     * @Route("/{id}/edit", name="faculty_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction(Request $request, Faculty $faculty)
    {
        $deleteForm = $this->createDeleteForm($faculty);
        $editForm = $this->createForm('CareerBundle\Form\FacultyType', $faculty);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('faculty_edit', array('id' => $faculty->getId()));
        }

        return $this->render('faculty/edit.html.twig', array(
            'faculty' => $faculty,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a faculty entity.
     *
     * @Route("/{id}", name="faculty_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Faculty $faculty)
    {
        $form = $this->createDeleteForm($faculty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($faculty);
            $em->flush();
        }

        return $this->redirectToRoute('faculty_index');
    }

    /**
     * Creates a form to delete a faculty entity.
     *
     * @param Faculty $faculty The faculty entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Faculty $faculty)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('faculty_delete', array('id' => $faculty->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
