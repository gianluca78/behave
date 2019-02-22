<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/dsm5-disorder")
 *
 * Class Dsm5DisorderController
 * @package App\Controller
 */
class Dsm5DisorderController extends Controller
{
    /**
     * @Route("/search", name="disorder_search")
     */
    public function search(Request $request)
    {
        $results = array();

        if(!$request->isXmlHttpRequest()) {
            $response = new Response('not allowed');
            $response->setStatusCode(403);

            return $response;
        }

        $results = $this->getDoctrine()->getRepository('App\Entity\Core\Dsm5Disorder')->searchByDescriptionOrCodes($request->get('term'));

        //dump($results); exit;

        return new Response(
            json_encode($results)
        );
    }

}
