<?php

namespace App\Controller;

use App\Security\Encoder\OpenSslEncoder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

use App\Entity\Observation;
use App\CouchDb\Client as CouchDbClient;

/**
 * @Route("/static")
 *
 * Class StaticPagesController
 * @package App\Controller
 */
class StaticPagesController extends Controller
{
    /**
     * @Route("/{_locale}/privacy-policy", name="privacy-policy", requirements={"locale": "en|it"})
     * @Method({"GET"})
     * @Template
     *
     */
    public function privacyPolicyAction()
    {
        return array();
    }
}