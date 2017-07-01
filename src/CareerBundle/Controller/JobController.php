<?php

namespace CareerBundle\Controller;

use CareerBundle\Entity\Application;
use CareerBundle\Entity\Company;
use CareerBundle\Entity\Job;
use CareerBundle\Entity\Student;
use Recombee\RecommApi\Client;
use Recombee\RecommApi\Requests as Reqs;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

/**
 * Job controller.
 *
 * @Route("job")
 */
class JobController extends Controller
{
    private $databaseName = 'educational-bachelor-thesis';
    private $secretToken = 'brYtXCBBrXa2nSyysgZmlr2vqiMVThkgmTqGV7bn8x96gBtw4MF0yFIEr5wlkF4U';

    /**
     * Lists all job entities.
     *
     * @Route("/", name="job_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $jobs = $em->getRepository('CareerBundle:Job')->findAll();
        $jobTypes = $em->getRepository('CareerBundle:JobType')->findAll();
        $companies = $em->getRepository('CareerBundle:Company')->findBy([], [], 7);
        $topics = $em->getRepository('CareerBundle:Tag')->findBy([], [], 7);

        return $this->render('job/index.html.twig', array(
            'jobs' => $jobs,
            'jobTypes' => $jobTypes,
            'companies' => $companies,
            'topics' => $topics
        ));
    }

    /**
     * Creates a new job entity.
     *
     * @Route("/new", name="job_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $job = new Job();
        $form = $this->createForm('CareerBundle\Form\JobType', $job);
        $form->handleRequest($request);

        $error = '';
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $company = $em->getRepository(Company::class)->findOneBy(['securityUser' => $this->getUser()]);
            $job->setCreatedBy($company);

            $em->persist($job);
            $em->flush();

            $this->addItemToRecombee($job);

            return $this->redirectToRoute('job_show', array('id' => $job->getId()));
        }

        return $this->render('job/new.html.twig', array(
            'job' => $job,
            'form' => $form->createView(),
            'error' => $error
        ));
    }

    /**
     * Finds and displays a job entity.
     *
     * @Route("/{id}", name="job_show")
     * @Method("GET")
     */
    public function showAction(Job $job)
    {
        $applyForm = $this->createApplicationForm($job);
        $deleteForm = $this->createDeleteForm($job);

        $em = $this->getDoctrine()->getManager();

        $student = $em->getRepository(Student::class)->findOneBy(['securityUser' => $this->getUser()]);
        $client = new Client($this->databaseName, $this->secretToken);
        $item_id = sprintf('job-%d', $job->getId());

        if ($student) {
            $user_id = sprintf('student-%d', $student->getId());

//            $request = new Reqs\AddDetailView($user_id, $item_id, [
//                'timestamp' => time(),
//                'cascadeCreate' => false
//            ]);
//            $request->setTimeout(30000);
//            $client->send($request);
        }

        $options = [];
        if ($student) {
            $options['targetUserId'] = $user_id;
            $options['userImpact'] = 0.5;
        }
        $options['diversity'] = 0.1;
        $request = new Reqs\ItemBasedRecommendation($item_id, 6, $options);
        $request->setTimeout(30000);
        $recommendations = $client->send($request);

        $recommendedJobs = [];
        foreach ($recommendations as $recommendation) {
            $parts = explode('-', $recommendation);

            if ($parts[0] === 'job') {
                $reccJob = $em->getRepository(Job::class)->find((int)$parts[1]);
                if ($reccJob) {
                    $recommendedJobs[] = $reccJob;
                }
            }
        }

        $jobTypes = $em->getRepository('CareerBundle:JobType')->findAll();
        $companies = $em->getRepository('CareerBundle:Company')->findBy([], [], 7);
        $topics = $em->getRepository('CareerBundle:Tag')->findBy([], [], 7);

        return $this->render('job/show.html.twig', array(
            'job' => $job,
            'jobTypes' => $jobTypes,
            'companies' => $companies,
            'topics' => $topics,
            'recommendedJobs' => $recommendedJobs,
            'delete_form' => $deleteForm->createView(),
            'application_form' => $applyForm->createView()
        ));
    }

    /**
     * Applies for a job.
     *
     * @Route("/apply/{id}", name="job_apply")
     * @Method({"GET", "POST"})
     */
    public function applyAction(Request $request, Job $job)
    {
        $form = $this->createApplicationForm($job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $application = new Application();
            $application->setJob($job);

            $em = $this->getDoctrine()->getManager();
            $student = $em->getRepository(Student::class)->findOneBy(['securityUser' => $this->getUser()]);

            $application->setStudent($student);

            $em->persist($application);
            $em->flush();

            try {
                $userId = sprintf('student-%d', $student->getId());
                $itemId = sprintf('job-%d', $job->getId());
                $client = new Client($this->databaseName, $this->secretToken);
                $request = new Reqs\AddPurchase($userId, $itemId, [ //optional parameters:
                    'timestamp' => $date = date('Y/m/d h:i:s', time()),
                    'cascadeCreate' => false
                ]);
                $request->setTimeout(30000);
                $client->send($request);
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        return $this->redirectToRoute('job_index');
    }


    /**
     * Displays a form to edit an existing job entity.
     *
     * @Route("/{id}/edit", name="job_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Job $job)
    {
        $deleteForm = $this->createDeleteForm($job);
        $editForm = $this->createForm('CareerBundle\Form\JobType', $job);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addItemToRecombee($job);

            return $this->redirectToRoute('job_edit', array('id' => $job->getId()));
        }

        return $this->render('job/edit.html.twig', array(
            'job' => $job,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    private function addItemToRecombee(Job $job, $create = true)
    {
        $itemId = sprintf('job-%d', $job->getId());
        $values = [
            'title' => $job->getTitle(),
            'type' => $job->getJobType()->getName(),
            'topics' => $job->getTopicsString(),
            'description' => $job->getShortDescription()
        ];

        $client = new Client($this->databaseName, $this->secretToken);
        $request = new Reqs\SetItemValues($itemId, $values, [
            'cascadeCreate' => $create
        ]);
        $request->setTimeout(30000);
        $client->send($request);
    }

    /**
     * Deletes a job entity.
     *
     * @Route("/{id}", name="job_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Job $job)
    {
        $form = $this->createDeleteForm($job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($job);
            $em->flush();

            $itemId = sprintf('job-%d', $job->getId());
            $client = new Client($this->databaseName, $this->secretToken);
            $request = new Reqs\DeleteItem($itemId);
            $request->setTimeout(30000);
            $client->send($request);
        }

        return $this->redirectToRoute('job_index');
    }

    /**
     * Creates a form to delete a job entity.
     *
     * @param Job $job The job entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Job $job)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('job_delete', array('id' => $job->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Creates a form to apply for a job.
     *
     * @param Job $job The job entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createApplicationForm(Job $job)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('job_apply', array('id' => $job->getId())))
            ->setMethod('POST')
            ->getForm()
            ;
    }
}
