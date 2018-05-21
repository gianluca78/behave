<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Validator\Constraints as AppAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StudentRepository")
 * @AppAssert\IsUserIdUnique
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
     * @ORM\Column(name="student_id", type="string", length=10)
     */
    private $studentId;

    /**
     * @ORM\Column(name="creator_username", type="encrypted_string", length=255)
     */
    private $creatorUsername;

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
    private $updateAt;

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
     * @param mixed $creatorUsername
     */
    public function setCreatorUsername($creatorUsername)
    {
        $this->creatorUsername = $creatorUsername;
    }

    /**
     * @return mixed
     */
    public function getCreatorUsername()
    {
        return $this->creatorUsername;
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
     * @param mixed $updateAt
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;
    }

    /**
     * @return mixed
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }


}
