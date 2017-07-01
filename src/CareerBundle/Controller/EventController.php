<?php

namespace CareerBundle\Controller;

use CareerBundle\Entity\Attendance;
use CareerBundle\Entity\Event;
use CareerBundle\Entity\Student;
use Recombee\RecommApi\Client;
use Recombee\RecommApi\Requests as Reqs;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

/**
 * Event controller.
 *
 * @Route("event")
 */
class EventController extends Controller
{
    private $databaseName = 'educational-bachelor-thesis';
    private $secretToken = 'brYtXCBBrXa2nSyysgZmlr2vqiMVThkgmTqGV7bn8x96gBtw4MF0yFIEr5wlkF4U';

    /**
     * Lists all event entities.
     *
     * @Route("/", name="event_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository('CareerBundle:Event')->findAll();

        return $this->render('event/index.html.twig', array(
            'events' => $events,
        ));
    }

    /**
     * Creates a new event entity.
     *
     * @Route("/new", name="event_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $event = new Event();
        $form = $this->createForm('CareerBundle\Form\EventType', $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            $this->addItemToRecombee($event);

            return $this->redirectToRoute('event_show', array('id' => $event->getId()));
        }

        return $this->render('event/new.html.twig', array(
            'event' => $event,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a event entity.
     *
     * @Route("/{id}", name="event_show")
     * @Method("GET")
     */
    public function showAction(Event $event)
    {
        $deleteForm = $this->createDeleteForm($event);
        $attendForm = $this->createAttendanceForm($event);

        $em = $this->getDoctrine()->getManager();
        $topics = $em->getRepository('CareerBundle:Tag')->findBy([], [], 7);

        $student = $em->getRepository(Student::class)->findOneBy(['securityUser' => $this->getUser()]);
        if ($student) {

            $user_id = sprintf('student-%d', $student->getId());
            $item_id = sprintf('event-%d', $event->getId());

            $client = new Client($this->databaseName, $this->secretToken);
            $request = new Reqs\AddDetailView($user_id, $item_id, [
                'timestamp' => time(),
                'cascadeCreate' => false
            ]);
            $request->setTimeout(30000);
            $client->send($request);
        }

        return $this->render('event/show.html.twig', array(
            'event' => $event,
            'delete_form' => $deleteForm->createView(),
            'attendance_form' => $attendForm->createView(),
            'topics' => $topics
        ));
    }

    /**
     * Displays a form to edit an existing event entity.
     *
     * @Route("/{id}/edit", name="event_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Event $event)
    {
        $deleteForm = $this->createDeleteForm($event);
        $editForm = $this->createForm('CareerBundle\Form\EventType', $event);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addItemToRecombee($event);

            return $this->redirectToRoute('event_edit', array('id' => $event->getId()));
        }

        return $this->render('event/edit.html.twig', array(
            'event' => $event,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    private function addItemToRecombee(Event $event, $create = true)
    {
        $itemId = sprintf('event-%d', $event->getId());
        $values = [
            'title' => $event->getName(),
            'type' => $event->getEventType()->getName(),
            'topics' => $event->getTopicsString(),
            'description' => $event->getDescription()
        ];

        $client = new Client($this->databaseName, $this->secretToken);
        $request = new Reqs\SetItemValues($itemId, $values, [
            'cascadeCreate' => $create
        ]);
        $request->setTimeout(30000);
        $client->send($request);
    }

    /**
     * Attends an event.
     *
     * @Route("/attend/{id}", name="event_attend")
     * @Method({"GET", "POST"})
     */
    public function attendAction(Request $request, Event $event)
    {
        $form = $this->createAttendanceForm($event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $attendance = new Attendance();
            $attendance->setEvent($event);

            $em = $this->getDoctrine()->getManager();
            $student = $em->getRepository(Student::class)->findOneBy(['securityUser' => $this->getUser()]);

            $attendance->setStudent($student);

            $em->persist($attendance);
            $em->flush();

            try {
                $userId = sprintf('student-%d', $student->getId());
                $itemId = sprintf('event-%d', $event->getId());
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
     * Deletes a event entity.
     *
     * @Route("/{id}", name="event_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Event $event)
    {
        $form = $this->createDeleteForm($event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush();

            $itemId = sprintf('job-%d', $event->getId());
            $client = new Client($this->databaseName, $this->secretToken);
            $request = new Reqs\DeleteItem($itemId);
            $request->setTimeout(30000);
            $client->send($request);
        }

        return $this->redirectToRoute('event_index');
    }

    /**
     * Creates a form to delete a event entity.
     *
     * @param Event $event The event entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Event $event)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('event_delete', array('id' => $event->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Creates a form to apply for a job.
     *
     * @param Event $event The event entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createAttendanceForm(Event $event)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('event_attend', array('id' => $event->getId())))
            ->setMethod('POST')
            ->getForm()
            ;
    }
}
