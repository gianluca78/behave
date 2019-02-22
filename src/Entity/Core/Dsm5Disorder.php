<?php

namespace App\Entity\Core;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Dsm5DisorderRepository")
 * @ORM\Table(name="dmn_dsm5_disorder")
 */
class Dsm5Disorder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dsmId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $icd9;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $icd10;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Core\Dsm5Category", inversedBy="dsm5Disorders")
     * @ORM\JoinColumn(name="dsm_category_id", nullable=false)
     */
    private $dsm5Category;

    public function __toString()
    {
        return $this->description;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDsmId(): ?string
    {
        return $this->dsmId;
    }

    public function setDsmId(string $dsmId): self
    {
        $this->dsmId = $dsmId;

        return $this;
    }

    public function getIcd9(): ?string
    {
        return $this->icd9;
    }

    public function setIcd9(string $icd9): self
    {
        $this->icd9 = $icd9;

        return $this;
    }

    public function getIcd10(): ?string
    {
        return $this->icd10;
    }

    public function setIcd10(string $icd10): self
    {
        $this->icd10 = $icd10;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDsm5Category(): ?Dsm5Category
    {
        return $this->dsm5Category;
    }

    public function setDsm5Category(?Dsm5Category $dsm5Category): self
    {
        $this->dsm5Category = $dsm5Category;

        return $this;
    }
}
