<?php

namespace CareerBundle\Entity;

use CareerBundle\Form\StudentType;
use Doctrine\ORM\Mapping as ORM;

/**
 * Student
 *
 * @ORM\Table(name="student")
 * @ORM\Entity(repositoryClass="CareerBundle\Repository\StudentRepository")
 */
class Student extends MainUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\OneToOne(targetEntity="SecurityUser")
     * @ORM\JoinColumn(name="security_user_id", referencedColumnName="id")
     */
    private $securityUser;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var StudyType
     *
     * @ORM\ManyToOne(targetEntity="StudyType")
     * @ORM\JoinColumn(name="study_type_id", referencedColumnName="id")
     */
    private $studyType;

    /**
     * @var int
     *
     * @ORM\Column(name="startYear", type="integer")
     */
    private $startYear;

    /**
     * @var Specialisation
     *
     * @ORM\ManyToOne(targetEntity="Specialisation")
     * @ORM\JoinColumn(name="specialisation_id", referencedColumnName="id")
     */
    private $specialisation;

    /**
     * @var string
     *
     * @ORM\Column(name="shortCV", type="blob")
     */
    private $shortCV;

    /**
     * @var
     *
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(name="students_tags",
     *      joinColumns={@ORM\JoinColumn(name="student_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    private $interests;

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
     * @return int
     */
    public function getSecurityUser()
    {
        return $this->securityUser;
    }

    /**
     * @param int $securityUser
     */
    public function setSecurityUser($securityUser)
    {
        $this->securityUser = $securityUser;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Student
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
     * @return Student
     */
    public function setStudyType($studyType)
    {
        $this->studyType = $studyType;

        return $this;
    }

    /**
     * Get studyType
     *
     * @return StudyType
     */
    public function getStudyType()
    {
        return $this->studyType;
    }

    /**
     * Set startYear
     *
     * @param integer $startYear
     *
     * @return Student
     */
    public function setStartYear($startYear)
    {
        $this->startYear = $startYear;

        return $this;
    }

    /**
     * Get startYear
     *
     * @return int
     */
    public function getStartYear()
    {
        return $this->startYear;
    }

    /**
     * Set specialisationId
     *
     * @param integer $specialisation
     *
     * @return Student
     */
    public function setSpecialisation($specialisation)
    {
        $this->specialisation = $specialisation;

        return $this;
    }

    /**
     * Get specialisation
     *
     * @return Specialisation
     */
    public function getSpecialisation()
    {
        return $this->specialisation;
    }

    /**
     * @return string
     */
    public function getShortCV()
    {
        if ($this->shortCV != '') {
            return stream_get_contents($this->shortCV);
        }

        return $this->shortCV;
    }

    /**
     * @param string $shortCV
     */
    public function setShortCV($shortCV)
    {
        $this->shortCV = $shortCV;
    }

    /**
     * @return mixed
     */
    public function getInterests()
    {
        return $this->interests;
    }

    /**
     * @param mixed $interests
     */
    public function setInterests($interests)
    {
        $this->interests = $interests;
    }

    public function getInterestsString()
    {
        $result = '';

        /** @var Tag $interest */
        foreach ($this->interests as $interest) {
            $result = sprintf('%s %s', $result, $interest->getName());
        }

        return $result;
    }
}

