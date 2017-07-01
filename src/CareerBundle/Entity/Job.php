<?php

namespace CareerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Job
 *
 * @ORM\Table(name="job")
 * @ORM\Entity(repositoryClass="CareerBundle\Repository\JobRepository")
 */
class Job
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="shortDescription", type="string", length=255)
     */
    private $shortDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="requiredKnowledge", type="blob")
     */
    private $requiredKnowledge;

    /**
     * @var string
     *
     * @ORM\Column(name="niceToHaveKnowledge", type="blob")
     */
    private $niceToHaveKnowledge;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="blob")
     */
    private $description;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="JobType")
     * @ORM\JoinColumn(name="job_type_id", referencedColumnName="id")
     */
    private $jobType;

    /** @var
     *
     * @ORM\ManyToOne(targetEntity="Company")
     * @ORM\JoinColumn(name="created_by_id", referencedColumnName="id")
     */
    private $createdBy;

    /**
     * @var
     *
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(name="jobs_tags",
     *      joinColumns={@ORM\JoinColumn(name="job_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    private $topics;

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
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     *
     * @return Job
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set requiredKnowledge
     *
     * @param string $requiredKnowledge
     *
     * @return Job
     */
    public function setRequiredKnowledge($requiredKnowledge)
    {
        $this->requiredKnowledge = $requiredKnowledge;

        return $this;
    }

    /**
     * Get requiredKnowledge
     *
     * @return string
     */
    public function getRequiredKnowledge()
    {
        if ($this->requiredKnowledge != '') {
            return stream_get_contents($this->requiredKnowledge);
        }

        return $this->requiredKnowledge;
    }

    /**
     * Set niceToHaveKnowledge
     *
     * @param string $niceToHaveKnowledge
     *
     * @return Job
     */
    public function setNiceToHaveKnowledge($niceToHaveKnowledge)
    {
        $this->niceToHaveKnowledge = $niceToHaveKnowledge;

        return $this;
    }

    /**
     * Get niceToHaveKnowledge
     *
     * @return string
     */
    public function getNiceToHaveKnowledge()
    {
        if ($this->niceToHaveKnowledge != '') {
            return stream_get_contents($this->niceToHaveKnowledge);
        }

        return $this->niceToHaveKnowledge;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Job
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        if ($this->description != '') {
            return stream_get_contents($this->description);
        }
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getJobType()
    {
        return $this->jobType;
    }

    /**
     * @param mixed $jobType
     */
    public function setJobType($jobType)
    {
        $this->jobType = $jobType;
    }

    /**
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param mixed $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return mixed
     */
    public function getTopics()
    {
        return $this->topics;
    }

    /**
     * @param mixed $topics
     */
    public function setTopics($topics)
    {
        $this->topics = $topics;
    }

    public function getTopicsString()
    {
        $result = '';

        /** @var Tag $topic */
        foreach ($this->topics as $topic) {
            $result = sprintf('%s %s', $result, $topic->getName());
        }

        return $result;
    }
}

