<?php

namespace CareerBundle\Controller;

use CareerBundle\Entity\FacultyDepartment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Facultydepartment controller.
 *
 * @Route("faculty-department")
 */
class FacultyDepartmentController extends Controller
{
    /**
     * Lists all facultyDepartment entities.
     *
     * @Route("/", name="faculty-department_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $facultyDepartments = $em->getRepository('CareerBundle:FacultyDepartment')->findAll();

        return $this->render('facultydepartment/index.html.twig', array(
            'facultyDepartments' => $facultyDepartments,
        ));
    }

    /**
     * Creates a new facultyDepartment entity.
     *
     * @Route("/new", name="faculty-department_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $facultyDepartment = new FacultyDepartment();
        $form = $this->createForm('CareerBundle\Form\FacultyDepartmentType', $facultyDepartment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($facultyDepartment);
            $em->flush();

            return $this->redirectToRoute('faculty-department_show', array('id' => $facultyDepartment->getId()));
        }

        return $this->render('facultydepartment/new.html.twig', array(
            'facultyDepartment' => $facultyDepartment,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a facultyDepartment entity.
     *
     * @Route("/{id}", name="faculty-department_show")
     * @Method("GET")
     */
    public function showAction(FacultyDepartment $facultyDepartment)
    {
        $deleteForm = $this->createDeleteForm($facultyDepartment);

        return $this->render('facultydepartment/show.html.twig', array(
            'facultyDepartment' => $facultyDepartment,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing facultyDepartment entity.
     *
     * @Route("/{id}/edit", name="faculty-department_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, FacultyDepartment $facultyDepartment)
    {
        $deleteForm = $this->createDeleteForm($facultyDepartment);
        $editForm = $this->createForm('CareerBundle\Form\FacultyDepartmentType', $facultyDepartment);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('faculty-department_edit', array('id' => $facultyDepartment->getId()));
        }

        return $this->render('facultydepartment/edit.html.twig', array(
            'facultyDepartment' => $facultyDepartment,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a facultyDepartment entity.
     *
     * @Route("/{id}", name="faculty-department_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, FacultyDepartment $facultyDepartment)
    {
        $form = $this->createDeleteForm($facultyDepartment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($facultyDepartment);
            $em->flush();
        }

        return $this->redirectToRoute('faculty-department_index');
    }

    /**
     * Creates a form to delete a facultyDepartment entity.
     *
     * @param FacultyDepartment $facultyDepartment The facultyDepartment entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(FacultyDepartment $facultyDepartment)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('faculty-department_delete', array('id' => $facultyDepartment->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
