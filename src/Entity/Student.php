<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StudentRepository")
 * @UniqueEntity(
 *      fields={"studentId", "creatorUserId"},
 *      message = "The student id is already used"
 * )
 * @ORM\HasLifecycleCallbacks
 */
class Student
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="student_id", type="string", length=255)
     */
    private $studentId;

    /**
     * @ORM\Column(name="creator_user_id", type="encrypted_string", length=255)
     */
    private $creatorUserId;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     *
     */
    private $createdAt;

    /**
     * @var datetime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     *
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Observation", mappedBy="student", cascade={"persist", "remove"}))
     */
    private $observations;

    /**
     * @ORM\Column(type="integer")
     */
    private $sex;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StudentHealthInformation", mappedBy="student", orphanRemoval=true, cascade={"persist", "remove"}))
     */
    private $healthInformation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $yearOfBirth;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $countryType;

    public function __construct()
    {
        $this->observations = new ArrayCollection();
        $this->healthInformation = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStudentId()
    {
        return $this->studentId;
    }

    public function setStudentId(string $studentId)
    {
        $this->studentId = $studentId;

        return $this;
    }

    /**
     * @param mixed $creatorUserId
     */
    public function setCreatorUserId($creatorUserId)
    {
        $this->creatorUserId = $creatorUserId;
    }

    /**
     * @return mixed
     */
    public function getCreatorUserId()
    {
        return $this->creatorUserId;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdateAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getUpdateAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return Collection|Observation[]
     */
    public function getObservations()
    {
        return $this->observations;
    }

    public function addObservation(Observation $observation)
    {
        if (!$this->observations->contains($observation)) {
            $this->observations[] = $observation;
            $observation->setStudent($this);
        }

        return $this;
    }

    public function removeObservation(Observation $observation)
    {
        if ($this->observations->contains($observation)) {
            $this->observations->removeElement($observation);
            // set the owning side to null (unless already changed)
            if ($observation->getStudent() === $this) {
                $observation->setStudent(null);
            }
        }

        return $this;
    }

    public function getSex()
    {
        return $this->sex;
    }

    public function setSex(int $sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * @return Collection|StudentHealthInformation[]
     */
    public function getHealthInformation()
    {
        return $this->healthInformation;
    }

    public function addHealthInformation(StudentHealthInformation $healthInformation)
    {
        if (!$this->healthInformation->contains($healthInformation)) {
            $this->healthInformation[] = $healthInformation;
            $healthInformation->setStudent($this);
        }

        return $this;
    }

    public function removeHealthInformation(StudentHealthInformation $healthInformation)
    {
        if ($this->healthInformation->contains($healthInformation)) {
            $this->healthInformation->removeElement($healthInformation);
            // set the owning side to null (unless already changed)
            if ($healthInformation->getStudent() === $this) {
                $healthInformation->setStudent(null);
            }
        }

        return $this;
    }

    public function getYearOfBirth()
    {
        return $this->yearOfBirth;
    }

    public function setYearOfBirth(int $yearOfBirth)
    {
        $this->yearOfBirth = $yearOfBirth;

        return $this;
    }

    public function getCountryType()
    {
        return $this->countryType;
    }

    public function setCountryType(string $countryType)
    {
        $this->countryType = $countryType;

        return $this;
    }



}
