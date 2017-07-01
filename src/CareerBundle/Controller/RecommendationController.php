<?php
/**
 * Created by PhpStorm.
 * User: Ramona
 * Date: 6/4/2017
 * Time: 5:34 PM
 */
namespace CareerBundle\Controller;

use CareerBundle\Entity\FacultyDepartment;
use CareerBundle\Entity\Student;
use Recombee\RecommApi\Client;
use Recombee\RecommApi\Requests as Reqs;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Recommendation controller.
 *
 * @Route("recommendation")
 */
class RecommendationController extends Controller
{
    private $databaseName = 'educational-bachelor-thesis';
    private $secretToken = 'brYtXCBBrXa2nSyysgZmlr2vqiMVThkgmTqGV7bn8x96gBtw4MF0yFIEr5wlkF4U';


    /**
     * Add all properties of an item (job).
     *
     * @Route("/item_properties", name="item_properties")
     */
    public function itemPropertiesAction()
    {
        $client = new Client($this->databaseName, $this->secretToken);
//        $request = new Reqs\DeleteItemProperty('requirements');
//        $request->setTimeout(30000);
//        $client->send($request);
        $client -> send(new Reqs\AddItemProperty("topics", "string"));
        $client -> send(new Reqs\AddItemProperty("title", "string"));
        $client -> send(new Reqs\AddItemProperty("description", "string"));
        $client -> send(new Reqs\AddItemProperty("type", "string"));

        return $this->render('recommendation/index.html.twig');
    }

    /**
     * Adds all properties of an user (student)
     *
     * @Route("/user_properties", name="user_properties")
     */
    public function userPropertiesAction()
    {
        $client = new Client($this->databaseName, $this->secretToken);
//        $request = new Reqs\DeleteUserProperty('name');
//        $request->setTimeout(30000);
//        $client->send($request);
        $client -> send(new Reqs\AddUserProperty("name", "string"));
        $client -> send(new Reqs\AddUserProperty("year", "int"));
        $client -> send(new Reqs\AddUserProperty("studyType", "string"));
        $client -> send(new Reqs\AddUserProperty("specialisation", "string"));
        $client -> send(new Reqs\AddUserProperty("interests", "string"));


        return $this->render('recommendation/index.html.twig');
    }

    /**
     * Deletes a faculty entity.
     *
     * @Route("/user/{id}/delete", name="user_delete")
     */
    public function deleteUserAction(Request $request, Student $student)
    {
        $userId = sprintf('student-%d', $student->getId());

        $client = new Client($this->databaseName, $this->secretToken);
        $request = new Reqs\SetUserValues($userId, ['year' => 2016]);//new Reqs\DeleteUser($userId);
        $request->setTimeout(30000);
        $client->send($request);

        return $this->render('recommendation/index.html.twig');
    }

    /**
     * Deletes a faculty entity.
     *
     * @Route("/user/list", name="user_list")
     */
    public function listUsersAction()
    {
        $client = new Client($this->databaseName, $this->secretToken);

//        $userId = 'student-1';
//        $request = new Reqs\UserBasedRecommendation($userId, 4);//new Reqs\DeleteUser($userId);
//        $request->setTimeout(30000);
//        dump($client->send($request));die;

        $request = new Reqs\ListUsers();
        $request->setTimeout(30000);
        $result = $client->send($request);
        dump($result);die;
    }
}