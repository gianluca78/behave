<?php

namespace App\Controller;

use App\Security\Encoder\OpenSslEncoder;
use Symfony\Component\Routing\Annotation\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController,
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
class StaticPagesController extends AbstractController
{
    /**
     * @Route("/{_locale}/privacy-policy", name="privacy-policy", requirements={"locale": "en|it"}, methods={"GET"})
     * @Template
     *
     */
    public function privacyPolicyAction()
    {
        return array();
    }

    /**
     * @Route("/{_locale}/online-guide", name="online-guide", requirements={"locale": "en|it"}, methods={"GET"})
     * @Template
     *
     */
    public function onlineGuideAction()
    {
        return array();
    }
}