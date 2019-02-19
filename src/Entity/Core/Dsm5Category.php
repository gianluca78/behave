<?php

namespace App\Entity\Core;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Dsm5CategoryRepository")
 * @ORM\Table(name="dmn_dsm5_category")
 */
class Dsm5Category
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Core\Dsm5Disorder", mappedBy="dsm5Category")
     */
    private $dsm5Disorders;

    public function __construct()
    {
        $this->dsm5Disorders = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Dsm5Disorder[]
     */
    public function getDsm5Disorders(): Collection
    {
        return $this->dsm5Disorders;
    }

    public function addDsm5Disorder(Dsm5Disorder $dsm5Disorder): self
    {
        if (!$this->dsm5Disorders->contains($dsm5Disorder)) {
            $this->dsm5Disorders[] = $dsm5Disorder;
            $dsm5Disorder->setDsm5Category($this);
        }

        return $this;
    }

    public function removeDsm5Disorder(Dsm5Disorder $dsm5Disorder): self
    {
        if ($this->dsm5Disorders->contains($dsm5Disorder)) {
            $this->dsm5Disorders->removeElement($dsm5Disorder);
            // set the owning side to null (unless already changed)
            if ($dsm5Disorder->getDsm5Category() === $this) {
                $dsm5Disorder->setDsm5Category(null);
            }
        }

        return $this;
    }
}
