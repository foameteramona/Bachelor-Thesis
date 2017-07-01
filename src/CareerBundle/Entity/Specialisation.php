<?php

namespace CareerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Specialisation
 *
 * @ORM\Table(name="specialisation")
 * @ORM\Entity(repositoryClass="CareerBundle\Repository\SpecialisationRepository")
 */
class Specialisation
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
     * @ORM\ManyToOne(targetEntity="StudyType", inversedBy="specialisations")
     * @ORM\JoinColumn(name="study_type_id", referencedColumnName="id")
     */
    private $studyType;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Faculty", inversedBy="specialisations")
     * @ORM\JoinColumn(name="faculty_id", referencedColumnName="id")
     */
    private $faculty;


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
     * @return Specialisation
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
     * Set studyTypeId
     *
     * @param integer $studyType
     *
     * @return Specialisation
     */
    public function setStudyType($studyType)
    {
        $this->studyType = $studyType;

        return $this;
    }

    /**
     * Get studyTypeId
     *
     * @return int
     */
    public function getStudyType()
    {
        return $this->studyType;
    }

    /**
     * @return mixed
     */
    public function getFaculty()
    {
        return $this->faculty;
    }

    /**
     * @param mixed $faculty
     */
    public function setFaculty($faculty)
    {
        $this->faculty = $faculty;
    }
}

