<?php

namespace App\Controller;

use App\Utility\Auth0Api;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use GuzzleHTTP\Client as GuzzleClient;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/user")
 *
 * Class StudentController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @Route("/search", name="user_search")
     */
    public function search(Request $request, Auth0Api $auth0Api)
    {
        $results = array();

        if(!$request->isXmlHttpRequest()) {
            $response = new Response('not allowed');
            $response->setStatusCode(403);

            return $response;
        }

        foreach($auth0Api->getUsers($request->get('term')) as $key => $user) {
            $results[] = array(
                'id' => $user->user_id,
                'label' => '<img width="10%" src="' . $user->picture . '">' . $user->name . '</img>',
                'value' => $user->user_id,
                'picture' => $user->picture
            );
        }

        return new Response(
            json_encode($results)
        );
    }

}
