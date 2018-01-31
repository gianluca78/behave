<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *  @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 *  @ORM\InheritanceType("SINGLE_TABLE")
 *  @ORM\DiscriminatorColumn(name="type", type="string")
 *  @ORM\DiscriminatorMap({
 *                          "choice" = "ChoiceItem",
 *                          "duration" = "DurationItem",
 *                          "frequency" = "FrequencyItem",
 *                          "range" = "RangeItem",
 *                          "text" = "TextItem",
 * })
 */
abstract class Item
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="position_number", type="integer")
     */
    protected $positionNumber;

    /**
     * @var string $label
     *
     * @ORM\Column(name="label", type="encrypted_string", length=255)
     */
    protected $label;

}
