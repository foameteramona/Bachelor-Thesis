<?php

namespace CareerBundle\Controller;

use CareerBundle\Entity\JobType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * JobType controller.
 *
 * @Route("job_type")
 */
class JobTypeController extends Controller
{
    /**
     * Lists all jobType entities.
     *
     * @Route("/", name="job_type_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $jobTypes = $em->getRepository('CareerBundle:JobType')->findAll();

        return $this->render('jobtype/index.html.twig', array(
            'jobTypes' => $jobTypes,
        ));
    }

    /**
     * Creates a new jobType entity.
     *
     * @Route("/new", name="job_type_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $jobType = new JobType();
        $form = $this->createForm('CareerBundle\Form\JobTypeType', $jobType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($jobType);
            $em->flush();

            return $this->redirectToRoute('job_type_show', array('id' => $jobType->getId()));
        }

        return $this->render('jobtype/new.html.twig', array(
            'jobType' => $jobType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a jobType entity.
     *
     * @Route("/{id}", name="job_type_show")
     * @Method("GET")
     */
    public function showAction(JobType $jobType)
    {
        $deleteForm = $this->createDeleteForm($jobType);

        return $this->render('jobtype/show.html.twig', array(
            'jobType' => $jobType,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing jobType entity.
     *
     * @Route("/{id}/edit", name="job_type_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, JobType $jobType)
    {
        $deleteForm = $this->createDeleteForm($jobType);
        $editForm = $this->createForm('CareerBundle\Form\JobTypeType', $jobType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('job_type_edit', array('id' => $jobType->getId()));
        }

        return $this->render('jobtype/edit.html.twig', array(
            'jobType' => $jobType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a jobType entity.
     *
     * @Route("/{id}", name="job_type_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, JobType $jobType)
    {
        $form = $this->createDeleteForm($jobType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($jobType);
            $em->flush();
        }

        return $this->redirectToRoute('job_type_index');
    }

    /**
     * Creates a form to delete a jobType entity.
     *
     * @param JobType $jobType The jobType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(JobType $jobType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('job_type_delete', array('id' => $jobType->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
