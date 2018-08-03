<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

use App\Entity\Observation;
use App\CouchDb\Client as CouchDbClient;

/**
 * @Route("/")
 *
 * Class HomepageController
 * @package App\Controller
 */
class HomepageController extends Controller
{
    /**
     * @Route("/homepage", name="homepage")
     * @Method({"GET"})
     * @Template
     *
     */
    public function homepageAction()
    {
        return array();
    }
}