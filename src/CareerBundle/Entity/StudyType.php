<?php

namespace CareerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StudyType
 *
 * @ORM\Table(name="study_type")
 * @ORM\Entity(repositoryClass="CareerBundle\Repository\StudyTypeRepository")
 */
class StudyType
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="diplomaType", type="string", length=255)
     */
    private $diplomaType;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="Specialisation", mappedBy="studyType")
     */
    private $specialisations;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return StudyType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return StudyType
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set diplomaType
     *
     * @param string $diplomaType
     *
     * @return StudyType
     */
    public function setDiplomaType($diplomaType)
    {
        $this->diplomaType = $diplomaType;

        return $this;
    }

    /**
     * Get diplomaType
     *
     * @return string
     */
    public function getDiplomaType()
    {
        return $this->diplomaType;
    }

    /**
     * @return mixed
     */
    public function getSpecialisations()
    {
        return $this->specialisations;
    }

    /**
     * @param mixed $specialisations
     */
    public function setSpecialisations($specialisations)
    {
        $this->specialisations = $specialisations;
    }
}

