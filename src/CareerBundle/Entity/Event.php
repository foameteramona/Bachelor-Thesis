<?php

namespace CareerBundle\Entity;

use CareerBundle\Entity\EventType;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="CareerBundle\Repository\EventRepository")
 */
class Event
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
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255)
     */
    private $location;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime")
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finish", type="datetime")
     */
    private $finish;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="blob")
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Professor")
     * @ORM\JoinColumn(name="hostedBy", referencedColumnName="id")
     */
    private $hostedBy;

    /**
     * @var int
     *
     * @ORM\Column(name="noOfParticipants", type="integer")
     */
    private $noOfParticipants;

    /**
     * @var EventType
     *
     * @ORM\ManyToOne(targetEntity="EventType")
     * @ORM\JoinColumn(name="event_type_id", referencedColumnName="id")
     */
    private $eventType;

    /**
     * @var
     *
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(name="events_tags",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
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
     * Set name
     *
     * @param string $name
     *
     * @return Event
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
     * @return EventType
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * @param EventType $eventType
     */
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return Event
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     *
     * @return Event
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set finish
     *
     * @param \DateTime $finish
     *
     * @return Event
     */
    public function setFinish($finish)
    {
        $this->finish = $finish;

        return $this;
    }

    /**
     * Get finish
     *
     * @return \DateTime
     */
    public function getFinish()
    {
        return $this->finish;
    }

    /**
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
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Set hostedBy
     *
     * @param integer $hostedBy
     *
     * @return Event
     */
    public function setHostedBy($hostedBy)
    {
        $this->hostedBy = $hostedBy;

        return $this;
    }

    /**
     * Get hostedBy
     *
     * @return int
     */
    public function getHostedBy()
    {
        return $this->hostedBy;
    }

    /**
     * Set noOfParticipants
     *
     * @param integer $noOfParticipants
     *
     * @return Event
     */
    public function setNoOfParticipants($noOfParticipants)
    {
        $this->noOfParticipants = $noOfParticipants;

        return $this;
    }

    /**
     * Get noOfParticipants
     *
     * @return int
     */
    public function getNoOfParticipants()
    {
        return $this->noOfParticipants;
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

