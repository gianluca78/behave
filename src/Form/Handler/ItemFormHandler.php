<?php
namespace App\Form\Handler;

use App\CouchDb\Client;
use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Form\FormInterface,
    Symfony\Component\HttpFoundation\Session\SessionInterface;

use Doctrine\ORM\EntityManagerInterface;


class ItemFormHandler
{
    private $couchDbClient;
    private $entityManager;
    private $session;

    public function __construct(
        EntityManagerInterface $entityManager,
        Client $couchDbClient,
        SessionInterface $session
    )
    {
        $this->entityManager = $entityManager;
        $this->couchDbClient = $couchDbClient;
        $this->session = $session;
    }

    public function handle(FormInterface $form, Request $request, $message)
    {
        if (!$request->isMethod('POST')) {
            return false;
        }

        $form->handleRequest($request);

        if (!$form->isValid()) {
            return false;
        }

        $data = $form->getData();

        $this->create($data, $message);

        return true;
    }

    public function create($data, $message)
    {
        var_dump($data);exit;

        $this->couchDbClient->connect()->postDataToDatabase($data);



        exit;

        /*
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $this->session->getFlashBag()->add('success', $message);
        */
    }
}