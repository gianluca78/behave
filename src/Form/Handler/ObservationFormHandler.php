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
    private $originalChoiceItems;
    private $originalDirectObservationItems;
    private $originalIntegerItems;
    private $originalMeterItems;
    private $originalRangeItems;
    private $originalTextItems;

    public function __construct(
        EntityManagerInterface $entityManager,
        SessionInterface $session
    )
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->originalChoiceItems = new ArrayCollection();
        $this->originalDirectObservationItems = new ArrayCollection();
        $this->originalIntegerItems = new ArrayCollection();
        $this->originalMeterItems = new ArrayCollection();
        $this->originalRangeItems = new ArrayCollection();
        $this->originalTextItems = new ArrayCollection();
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

        $this->removeChoiceItems($validObject);
        $this->removeDirectObservationItems($validObject);
        $this->removeIntegerItems($validObject);
        $this->removeMeterItems($validObject);
        $this->removeRangeItems($validObject);
        $this->removeTextItems($validObject);

        $this->create($validObject, $message);

        return true;
    }

    public function create(Observation $entity, $message)
    {
        if ($entity->getChoiceItems()->isEmpty() == false) {
            foreach ($entity->getChoiceItems() as $relatedEntity) {
                $relatedEntity->setObservation($entity);
            }
        }
        if ($entity->getDirectObservationItems()->isEmpty() == false) {
            foreach ($entity->getDirectObservationItems() as $relatedEntity) {
                $relatedEntity->setObservation($entity);
            }
        }
        if ($entity->getIntegerItems()->isEmpty() == false) {
            foreach ($entity->getIntegerItems() as $relatedEntity) {
                $relatedEntity->setObservation($entity);
            }
        }
        if ($entity->getMeterItems()->isEmpty() == false) {
            foreach ($entity->getMeterItems() as $relatedEntity) {
                $relatedEntity->setObservation($entity);
            }
        }
        if ($entity->getRangeItems()->isEmpty() == false) {
            foreach ($entity->getRangeItems() as $relatedEntity) {
                $relatedEntity->setObservation($entity);
            }
        }
        if ($entity->getTextItems()->isEmpty() == false) {
            foreach ($entity->getTextItems() as $relatedEntity) {
                $relatedEntity->setObservation($entity);
            }
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $this->session->getFlashBag()->add('success', $message);
    }

    public function removeChoiceItems(Observation $entity)
    {
        foreach ($this->originalChoiceItems as $choiceItem) {
            if (false === $entity->getChoiceItems()->contains($choiceItem)) {
                $this->entityManager->remove($choiceItem);
                $this->entityManager->flush();
            }
        }

        return $entity;
    }

    public function removeDirectObservationItems(Observation $entity)
    {
        foreach ($this->originalDirectObservationItems as $directObservationItem) {
            if (false === $entity->getDirectObservationItems()->contains($directObservationItem)) {
                $this->entityManager->remove($directObservationItem);
                $this->entityManager->flush();
            }
        }

        return $entity;
    }

    public function removeIntegerItems(Observation $entity)
    {
        foreach ($this->originalIntegerItems as $integerItem) {
            if (false === $entity->getIntegerItems()->contains($integerItem)) {
                $this->entityManager->remove($integerItem);
                $this->entityManager->flush();
            }
        }

        return $entity;
    }

    public function removeMeterItems(Observation $entity)
    {
        foreach ($this->originalMeterItems as $meterItem) {
            if (false === $entity->getMeterItems()->contains($meterItem)) {
                $this->entityManager->remove($meterItem);
                $this->entityManager->flush();
            }
        }

        return $entity;
    }

    public function removeRangeItems(Observation $entity)
    {
        foreach ($this->originalRangeItems as $rangeItem) {
            if (false === $entity->getRangeItems()->contains($rangeItem)) {
                $this->entityManager->remove($rangeItem);
                $this->entityManager->flush();
            }
        }

        return $entity;
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

    /**
     * @param ArrayCollection $originalChoiceItems
     */
    public function setOriginalChoiceItems($originalChoiceItems)
    {
        foreach ($originalChoiceItems as $choiceItem) {
            $this->originalChoiceItems->add($choiceItem);
        }
    }

    /**
     * @param ArrayCollection $originalDirectObservationItems
     */
    public function setOriginalDirectObservationItems($originalDirectObservationItems)
    {
        foreach ($originalDirectObservationItems as $directObservationItem) {
            $this->originalDirectObservationItems->add($directObservationItem);
        }
    }

    /**
     * @param ArrayCollection $originalIntegerItems
     */
    public function setOriginalIntegerItems($originalIntegerItems)
    {
        foreach ($originalIntegerItems as $integerItem) {
            $this->originalIntegerItems->add($integerItem);
        }
    }

    /**
     * @param ArrayCollection $originalMeterItems
     */
    public function setOriginalMeterItems($originalMeterItems)
    {
        foreach ($originalMeterItems as $meterItem) {
            $this->originalMeterItems->add($meterItem);
        }
    }

    /**
     * @param ArrayCollection $originalRangeItems
     */
    public function setOriginalRangeItems($originalRangeItems)
    {
        foreach ($originalRangeItems as $rangeItem) {
            $this->originalRangeItems->add($rangeItem);
        }
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
}