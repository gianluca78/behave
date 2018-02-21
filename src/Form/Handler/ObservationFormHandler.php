<?php
namespace App\Form\Handler;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Form\FormInterface,
    Symfony\Component\HttpFoundation\Session\SessionInterface;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Observation;

class ObservationFormHandler {

    private $entityManager;
    private $session;
        private $originalChoiceItems;
        private $originalDurationItems;
        private $originalFrequencyItems;
        private $originalIntegerItems;
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
                $this->originalDurationItems = new ArrayCollection();
                $this->originalFrequencyItems = new ArrayCollection();
                $this->originalIntegerItems = new ArrayCollection();
                $this->originalRangeItems = new ArrayCollection();
                $this->originalTextItems = new ArrayCollection();
            }

    public function handle(FormInterface $form, Request $request, $message)
    {
        if(!$request->isMethod('POST')) {
            return false;
        }

        $form->handleRequest($request);

        if(!$form->isValid()) {
            return false;
        }

        $validObject = $form->getData();

                $this->removeChoiceItems($validObject);
                $this->removeDurationItems($validObject);
                $this->removeFrequencyItems($validObject);
                $this->removeIntegerItems($validObject);
                $this->removeRangeItems($validObject);
                $this->removeTextItems($validObject);
        
        $this->create($validObject, $message);

        return true;
    }

    public function create(Observation $entity, $message)
    {
                if($entity->getChoiceItems()->isEmpty()==false) {
            foreach($entity->getChoiceItems() as $relatedEntity) {
                $relatedEntity->setObservation($entity);
            }
        }
                if($entity->getDurationItems()->isEmpty()==false) {
            foreach($entity->getDurationItems() as $relatedEntity) {
                $relatedEntity->setObservation($entity);
            }
        }
                if($entity->getFrequencyItems()->isEmpty()==false) {
            foreach($entity->getFrequencyItems() as $relatedEntity) {
                $relatedEntity->setObservation($entity);
            }
        }
                if($entity->getIntegerItems()->isEmpty()==false) {
            foreach($entity->getIntegerItems() as $relatedEntity) {
                $relatedEntity->setObservation($entity);
            }
        }
                if($entity->getRangeItems()->isEmpty()==false) {
            foreach($entity->getRangeItems() as $relatedEntity) {
                $relatedEntity->setObservation($entity);
            }
        }
                if($entity->getTextItems()->isEmpty()==false) {
            foreach($entity->getTextItems() as $relatedEntity) {
                $relatedEntity->setObservation($entity);
            }
        }
        
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $this->session->getFlashBag()->add('success', $message);
    }

        public function removeChoiceItems(Observation $entity) {
        foreach($this->originalChoiceItems as $choiceItem) {
            if (false === $entity->getChoiceItems()->contains($choiceItem)) {
                $this->entityManager->remove($choiceItem);
                $this->entityManager->flush();
            }
        }

        return $entity;
    }
        public function removeDurationItems(Observation $entity) {
        foreach($this->originalDurationItems as $durationItem) {
            if (false === $entity->getDurationItems()->contains($durationItem)) {
                $this->entityManager->remove($durationItem);
                $this->entityManager->flush();
            }
        }

        return $entity;
    }
        public function removeFrequencyItems(Observation $entity) {
        foreach($this->originalFrequencyItems as $frequencyItem) {
            if (false === $entity->getFrequencyItems()->contains($frequencyItem)) {
                $this->entityManager->remove($frequencyItem);
                $this->entityManager->flush();
            }
        }

        return $entity;
    }
        public function removeIntegerItems(Observation $entity) {
        foreach($this->originalIntegerItems as $integerItem) {
            if (false === $entity->getIntegerItems()->contains($integerItem)) {
                $this->entityManager->remove($integerItem);
                $this->entityManager->flush();
            }
        }

        return $entity;
    }
        public function removeRangeItems(Observation $entity) {
        foreach($this->originalRangeItems as $rangeItem) {
            if (false === $entity->getRangeItems()->contains($rangeItem)) {
                $this->entityManager->remove($rangeItem);
                $this->entityManager->flush();
            }
        }

        return $entity;
    }
        public function removeTextItems(Observation $entity) {
        foreach($this->originalTextItems as $textItem) {
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
        foreach($originalChoiceItems as $choiceItem) {
            $this->originalChoiceItems->add($choiceItem);
        }
    }
        /**
    * @param ArrayCollection $originalDurationItems
    */
    public function setOriginalDurationItems($originalDurationItems)
    {
        foreach($originalDurationItems as $durationItem) {
            $this->originalDurationItems->add($durationItem);
        }
    }
        /**
    * @param ArrayCollection $originalFrequencyItems
    */
    public function setOriginalFrequencyItems($originalFrequencyItems)
    {
        foreach($originalFrequencyItems as $frequencyItem) {
            $this->originalFrequencyItems->add($frequencyItem);
        }
    }
        /**
    * @param ArrayCollection $originalIntegerItems
    */
    public function setOriginalIntegerItems($originalIntegerItems)
    {
        foreach($originalIntegerItems as $integerItem) {
            $this->originalIntegerItems->add($integerItem);
        }
    }
        /**
    * @param ArrayCollection $originalRangeItems
    */
    public function setOriginalRangeItems($originalRangeItems)
    {
        foreach($originalRangeItems as $rangeItem) {
            $this->originalRangeItems->add($rangeItem);
        }
    }
        /**
    * @param ArrayCollection $originalTextItems
    */
    public function setOriginalTextItems($originalTextItems)
    {
        foreach($originalTextItems as $textItem) {
            $this->originalTextItems->add($textItem);
        }
    }
    }