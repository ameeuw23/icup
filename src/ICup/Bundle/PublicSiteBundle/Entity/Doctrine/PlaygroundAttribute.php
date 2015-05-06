<?php

namespace ICup\Bundle\PublicSiteBundle\Entity\Doctrine;

use Doctrine\ORM\Mapping as ORM;

/**
 * ICup\Bundle\PublicSiteBundle\Entity\Doctrine\Category
 *
 * @ORM\Table(name="playgroundattributes",uniqueConstraints={@ORM\UniqueConstraint(name="IdxByDate", columns={"pid", "date", "start"})})
 * @ORM\Entity
 */
class PlaygroundAttribute
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer $pid
     * Relation to Playground - pid=playground.id 
     * @ORM\Column(name="pid", type="integer", nullable=false)
     */
    private $pid;

    /**
     * @var integer $timeslot
     * Relation to Timeslot - pid=timeslot.id 
     * @ORM\Column(name="timeslot", type="integer", nullable=false)
     */
    private $timeslot;

    /**
     * @var string $date
     * Date for this calendar event - DD/MM/YYYY
     * @ORM\Column(name="date", type="string", length=10, nullable=false)
     */
    private $date;

    /**
     * @var string $start
     * Calendar event start time - HH:MM
     * @ORM\Column(name="start", type="string", length=5, nullable=false)
     */
    private $start;

    /**
     * @var string $end
     * Calendar event end time - HH:MM
     * @ORM\Column(name="end", type="string", length=5, nullable=false)
     */
    private $end;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set parent id - related tournament
     *
     * @param integer $pid
     * @return PlaygroundAttributes
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
    
        return $this;
    }

    /**
     * Get parent id - related tournament
     *
     * @return integer 
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * Set child id - related timeslot
     *
     * @param integer $timeslot
     * @return PlaygroundAttributes
     */
    public function setTimeslot($timeslot)
    {
        $this->timeslot = $timeslot;
    
        return $this;
    }

    /**
     * Get child id - related timeslot
     *
     * @return integer 
     */
    public function getTimeslot()
    {
        return $this->timeslot;
    }
    
    /**
     * Set date
     *
     * @param string $date
     * @return PlaygroundAttributes
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return string 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set start time
     *
     * @param string $start
     * @return PlaygroundAttributes
     */
    public function setStart($start)
    {
        $this->start = $start;
    
        return $this;
    }

    /**
     * Get start time
     *
     * @return string 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end time
     *
     * @param string $end
     * @return PlaygroundAttributes
     */
    public function setEnd($end)
    {
        $this->end = $end;
    
        return $this;
    }

    /**
     * Get end time
     *
     * @return string 
     */
    public function getEnd()
    {
        return $this->end;
    }
}