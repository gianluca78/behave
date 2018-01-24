<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnswerRepository")
 *  @ORM\InheritanceType("SINGLE_TABLE")
 *  @ORM\DiscriminatorColumn(name="type", type="string")
 *  @ORM\DiscriminatorMap({"text" = "TextAnswer", "frequency" = "FrequencyAnswer"})
 */
class Answer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Item $item
     *
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="answers")
     * @ORM\JoinColumn(name="observation_id", referencedColumnName="id")
     */
    private $observation;

}
