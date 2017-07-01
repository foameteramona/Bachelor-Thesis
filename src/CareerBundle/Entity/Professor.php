<?php

namespace CareerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Professor
 *
 * @ORM\Table(name="professor")
 * @ORM\Entity(repositoryClass="CareerBundle\Repository\ProfessorRepository")
 */
class Professor extends MainUser
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
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="FacultyDepartment", inversedBy="professors")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     */
    private $department;

    /**
     * @var string
     *
     * @ORM\Column(name="personalWebsite", type="string", length=255, nullable=true)
     */
    private $personalWebsite;


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
     * @return Professor
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
     * Set departmentId
     *
     * @param integer $department
     *
     * @return Professor
     */
    public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get departmentId
     *
     * @return int
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set personalWebsite
     *
     * @param string $personalWebsite
     *
     * @return Professor
     */
    public function setPersonalWebsite($personalWebsite)
    {
        $this->personalWebsite = $personalWebsite;

        return $this;
    }

    /**
     * Get personalWebsite
     *
     * @return string
     */
    public function getPersonalWebsite()
    {
        return $this->personalWebsite;
    }
}

