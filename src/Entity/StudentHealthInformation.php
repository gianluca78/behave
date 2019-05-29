<?php

namespace App\Entity;

use App\Entity\Core\Dsm5Disorder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StudentHealthInformationRepository")
 * @ORM\Table(name="student_health_information")
 */
class StudentHealthInformation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Core\Dsm5Disorder")
     * @ORM\JoinColumn(name="dsm5_disorder", nullable=false)
     */
    private $dsm5Disorder;

    /**
     * @ORM\Column(name="age_of_onset", type="integer", nullable=true)
     */
    private $ageOfOnset;

    /**
     * @ORM\Column(name="is_secondary_to_another_medical_condition", type="boolean")
     */
    private $isSecondaryToAnotherMedicalCondition;

    /**
     * @ORM\Column(name="medical_condition", type="string", length=255, nullable=true)
     */
    private $medicalCondition;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Core\Dsm5Disorder")
     * @ORM\JoinTable(name="student_dsm5_disorder",
     *      joinColumns={@ORM\JoinColumn(name="student_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="student_health_information_id", referencedColumnName="id")}
     *      )
     */
    private $comorbidDsm5Disorders;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Student", inversedBy="healthInformation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;

    public function __construct()
    {
        $this->comorbidDsm5Disorders = new ArrayCollection();
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if($this->getIsSecondaryToAnotherMedicalCondition()) {
            if(!$this->getMedicalCondition()) {
                $context->buildViolation('Required field')
                    ->atPath('medicalCondition')
                    ->addViolation();
            }
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDsm5Disorder(): ?Dsm5Disorder
    {
        return $this->dsm5Disorder;
    }

    public function setDsm5Disorder(?Dsm5Disorder $dsm5Disorder): self
    {
        $this->dsm5Disorder = $dsm5Disorder;

        return $this;
    }

    public function getAgeOfOnset(): ?int
    {
        return $this->ageOfOnset;
    }

    public function setAgeOfOnset(?int $ageOfOnset): self
    {
        $this->ageOfOnset = $ageOfOnset;

        return $this;
    }

    public function getIsSecondaryToAnotherMedicalCondition(): ?bool
    {
        return $this->isSecondaryToAnotherMedicalCondition;
    }

    public function setIsSecondaryToAnotherMedicalCondition(bool $isSecondaryToAnotherMedicalCondition): self
    {
        $this->isSecondaryToAnotherMedicalCondition = $isSecondaryToAnotherMedicalCondition;

        return $this;
    }

    public function getMedicalCondition(): ?string
    {
        return $this->medicalCondition;
    }

    public function setMedicalCondition(?string $medicalCondition): self
    {
        $this->medicalCondition = $medicalCondition;

        return $this;
    }

    /**
     * @return Collection|Dsm5Disorder[]
     */
    public function getComorbidDsm5Disorders(): Collection
    {
        return $this->comorbidDsm5Disorders;
    }

    public function addComorbidDsm5Disorder(Dsm5Disorder $comorbidDsm5Disorder): self
    {
        if (!$this->comorbidDsm5Disorders->contains($comorbidDsm5Disorder)) {
            $this->comorbidDsm5Disorders[] = $comorbidDsm5Disorder;
        }

        return $this;
    }

    public function removeComorbidDsm5Disorder(Dsm5Disorder $comorbidDsm5Disorder): self
    {
        if ($this->comorbidDsm5Disorders->contains($comorbidDsm5Disorder)) {
            $this->comorbidDsm5Disorders->removeElement($comorbidDsm5Disorder);
        }

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }
}
