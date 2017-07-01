<?php

namespace CareerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FacultyDepartment
 *
 * @ORM\Table(name="faculty_department")
 * @ORM\Entity(repositoryClass="CareerBundle\Repository\FacultyDepartmentRepository")
 */
class FacultyDepartment
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
     * @ORM\ManyToOne(targetEntity="Faculty", inversedBy="departments")
     * @ORM\JoinColumn(name="faculty_id", referencedColumnName="id")
     */
    private $faculty;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="Professor", mappedBy="department")
     */
    private $professors;


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
     * @return FacultyDepartment
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
     * Set facultyId
     *
     * @param integer $faculty
     *
     * @return FacultyDepartment
     */
    public function setFaculty($faculty)
    {
        $this->faculty = $faculty;

        return $this;
    }

    /**
     * Get facultyId
     *
     * @return int
     */
    public function getFaculty()
    {
        return $this->faculty;
    }

    /**
     * @return mixed
     */
    public function getProfessors()
    {
        return $this->professors;
    }

    /**
     * @param mixed $professors
     */
    public function setProfessors($professors)
    {
        $this->professors = $professors;
    }
}

