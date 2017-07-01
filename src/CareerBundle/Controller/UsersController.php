<?php

namespace CareerBundle\Controller;

use CareerBundle\Entity\Company;
use CareerBundle\Entity\Professor;
use CareerBundle\Entity\Student;
use FOS\UserBundle\Model\UserManagerInterface;
use Recombee\RecommApi\Client;
use Recombee\RecommApi\Requests as Reqs;
use Recombee\RecommApi\Exceptions as Ex;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 * @package CareerBundle\Controller
 *
 * @Route("user")
 */
class UsersController extends Controller
{
    private $databaseName = 'educational-bachelor-thesis';
    private $secretToken = 'brYtXCBBrXa2nSyysgZmlr2vqiMVThkgmTqGV7bn8x96gBtw4MF0yFIEr5wlkF4U';

    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('CareerBundle:Default:index.html.twig');
    }

    /**
     * @Route("/new_student", name="new_student")
     * @Method({"GET", "POST"})
     */
    public function newStudentAction(Request $request)
    {
        $student = new Student();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('CareerBundle\Form\StudentType', $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var $userManager UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $user = $userManager->createUser();
            $user->setEmail($student->getEmail());
            $user->setPlainPassword($student->getPassword());
            $user->setUsername($student->getUsername());
            $user->setEnabled(false);
            $user->setRoles(['ROLE_USER']);

            $userManager->updateUser($user);

            $securityUser = $em->getRepository('CareerBundle:SecurityUser')
                ->find($user->getId());
            $student->setSecurityUser($securityUser);

            $em->persist($student);
            $em->flush();

            $itemId = sprintf('student-%d', $student->getId());
            $values = [
                'year' => $student->getStartYear(),
                'studyType' => $student->getStudyType()->getName(),
                'specialisation' => $student->getSpecialisation()->getName(),
                'interests' => $student->getInterestsString()
            ];

            $client = new Client($this->databaseName, $this->secretToken);
            $client->send(new Reqs\AddUser($itemId));
            try {
                $request = new Reqs\SetUserValues($itemId, $values, [
                    'cascadeCreate' => true
                ]);
                $request->setTimeout(30000);
                $client->send($request);
            } catch(Ex\ApiTimeoutException $e) {
                $request->setTimeout(50000);
                $client->send($request);
            }

            return $this->redirectToRoute('list_student');
        }


        return $this->render('users/createStudent.html.twig', array(
            'student' => $student,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/students", name="list_student")
     */
    public function studentsListAction()
    {
        $em = $this->getDoctrine()->getManager();

        $students = $em->getRepository('CareerBundle:Student')->findAll();

        return $this->render('users/students.html.twig', array(
            'students' => $students,
        ));
    }

    /**
     * Displays a form to edit an existing student entity
     * and update the recommendation database
     *
     * @Route("/student/{id}/edit", name="student_edit")
     * @Method({"GET", "POST"})
     */
    public function editStudentAction(Request $request, Student $student)
    {
        $editForm = $this->createForm('CareerBundle\Form\StudentType', $student);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addUserToRecombee($student);

            return $this->redirectToRoute('student_edit', array('id' => $student->getId()));
        }

        return $this->render('users/student_edit.html.twig', array(
            'student' => $student,
            'edit_form' => $editForm->createView(),
        ));
    }

    private function addUserToRecombee(Student $student, $create = true)
    {
        $itemId = sprintf('student-%d', $student->getId());
        $values = $this->createUserProperties($student);

        $client = new Client($this->databaseName, $this->secretToken);
        try {
            $request = new Reqs\SetUserValues($itemId, $values, [
                'cascadeCreate' => $create
            ]);
            $request->setTimeout(30000);
            $client->send($request);
        } catch (Exception $e) {
            $request->setTimeout(50000);
            $client->send($request);
        }
    }

    private function createUserProperties(Student $student)
    {
        return [
            'year' => $student->getStartYear(),
            'studyType' => $student->getStudyType()->getName(),
            'specialisation' => $student->getSpecialisation()->getName(),
            'interests' => $student->getInterestsString()
        ];
    }

    /**
     * @Route("/new_professor", name="new_professor")
     * @Method({"GET", "POST"})
     */
    public function newProfessorAction(Request $request)
    {
        $professor = new Professor();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('CareerBundle\Form\ProfessorType', $professor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var $userManager UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $user = $userManager->createUser();
            $user->setEmail($professor->getEmail());
            $user->setPlainPassword($professor->getPassword());
            $user->setUsername($professor->getUsername());
            $user->setEnabled(true);
            $user->setRoles(['ROLE_ADMIN']);

            $userManager->updateUser($user);

            $securityUser = $em->getRepository('CareerBundle:SecurityUser')
                ->find($user->getId());
            $professor->setSecurityUser($securityUser);

            $em->persist($professor);
            $em->flush();

            return $this->redirectToRoute('faculty_index');
        }


        return $this->render('users/createProfessor.html.twig', array(
            'professor' => $professor,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/professors", name="list_professor")
     */
    public function professorsListAction()
    {
        $em = $this->getDoctrine()->getManager();

        $professors = $em->getRepository('CareerBundle:Professor')->findAll();

        return $this->render('users/professors.html.twig', array(
            'professors' => $professors,
        ));
    }

    /**
     * Displays a form to edit an existing professor entity.
     *
     * @Route("/professor/{id}/edit", name="professor_edit")
     * @Method({"GET", "POST"})
     */
    public function editProfessorAction(Request $request, Professor $professor)
    {
        $editForm = $this->createForm('CareerBundle\Form\ProfessorType', $professor);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('professor_edit', array('id' => $professor->getId()));
        }

        return $this->render('users/professor_edit.html.twig', array(
            'professor' => $professor,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * @Route("/new_company", name="new_company")
     * @Method({"GET", "POST"})
     */
    public function newCompanyAction(Request $request)
    {
        $company = new Company();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('CareerBundle\Form\CompanyType', $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var $userManager UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $user = $userManager->createUser();
            $user->setEmail($company->getEmail());
            $user->setPlainPassword($company->getPassword());
            $user->setUsername($company->getUsername());
            $user->setEnabled(true);
            $user->setRoles(['ROLE_ADMIN']);

            $userManager->updateUser($user);

            $securityUser = $em->getRepository('CareerBundle:SecurityUser')
                ->find($user->getId());
            $company->setSecurityUser($securityUser);

            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('faculty_index');
        }


        return $this->render('users/createCompany.html.twig', array(
            'companies' => $company,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/companies", name="list_companies")
     */
    public function companiesListAction()
    {
        $em = $this->getDoctrine()->getManager();

        $companies = $em->getRepository('CareerBundle:Company')->findAll();

        return $this->render('users/companies.html.twig', array(
            'companies' => $companies,
        ));
    }

    /**
     * Displays a form to edit an existing company entity.
     *
     * @Route("/company/{id}/edit", name="company_edit")
     * @Method({"GET", "POST"})
     */
    public function editCompanyAction(Request $request, Company $company)
    {
        $editForm = $this->createForm('CareerBundle\Form\CompanyType', $company);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('company_edit', array('id' => $company->getId()));
        }

        return $this->render('users/company_edit.html.twig', array(
            'company' => $company,
            'edit_form' => $editForm->createView(),
        ));
    }
}