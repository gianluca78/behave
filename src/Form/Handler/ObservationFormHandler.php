<?php
namespace App\Form\Handler;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Form\FormInterface,
    Symfony\Component\HttpFoundation\Session\SessionInterface;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Observation;

class ObservationFormHandler
{

    private $entityManager;
    private $session;
    private $originalTextItems;
    private $originalTextFrequencyItems;

    public function __construct(
        EntityManagerInterface $entityManager,
        SessionInterface $session
    )
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->originalTextItems = new ArrayCollection();
        $this->originalTextFrequencyItems = new ArrayCollection();
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

        $validObject = $form->getData();

        $this->removeTextItems($validObject);
        $this->removeTextFrequencyItems($validObject);

        $this->create($validObject, $message);

        return true;
    }

    public function create(Observation $entity, $message)
    {
        if ($entity->getTextItems()->isEmpty() == false) {
            foreach ($entity->getTextItems() as $relatedEntity) {
                $relatedEntity->setObservation($entity);
            }
        }
        if ($entity->getFrequencyItems()->isEmpty() == false) {
            foreach ($entity->getFrequencyItems() as $relatedEntity) {
                $relatedEntity->setObservation($entity);
            }
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $this->session->getFlashBag()->add('success', $message);
    }

    public function removeTextItems(Observation $entity)
    {
        foreach ($this->originalTextItems as $textItem) {
            if (false === $entity->getTextItems()->contains($textItem)) {
                $this->entityManager->remove($textItem);
                $this->entityManager->flush();
            }
        }

        return $entity;
    }

    public function removeTextFrequencyItems(Observation $entity)
    {
        foreach ($this->originalTextFrequencyItems as $textFrequencyItem) {
            if (false === $entity->getFrequencyItems()->contains($textFrequencyItem)) {
                $this->entityManager->remove($textFrequencyItem);
                $this->entityManager->flush();
            }
        }

        return $entity;
    }

    /**
     * @param ArrayCollection $originalTextItems
     */
    public function setOriginalTextItems($originalTextItems)
    {
        foreach ($originalTextItems as $textItem) {
            $this->originalTextItems->add($textItem);
        }
    }

    /**
     * @param ArrayCollection $originalTextFrequencyItems
     */
    public function setOriginalTextFrequencyItems($originalTextFrequencyItems)
    {
        foreach ($originalTextFrequencyItems as $textFrequencyItem) {
            $this->originalTextFrequencyItems->add($textFrequencyItem);
        }
    }
}