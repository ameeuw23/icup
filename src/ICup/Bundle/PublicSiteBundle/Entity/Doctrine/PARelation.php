<?php

namespace ICup\Bundle\PublicSiteBundle\Entity\Doctrine;

use Doctrine\ORM\Mapping as ORM;

/**
 * ICup\Bundle\PublicSiteBundle\Entity\Doctrine\Enrollment
 *
 * @ORM\Table(name="parelations",uniqueConstraints={@ORM\UniqueConstraint(name="IdxByCat", columns={"pid", "cid"})})
 * @ORM\Entity
 */
class PARelation
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
     * Relation to PlaygroundAttribute - pid=playgroundattribute.id 
     * @ORM\Column(name="pid", type="integer", nullable=false)
     */
    private $pid;

    /**
     * @var integer $cid
     * Relation to Category - pid=category.id 
     * @ORM\Column(name="cid", type="integer", nullable=false)
     */
    private $cid;

    /**
     * @var string $finals
     * Indicates this category relation is restricted to finals - Y=Yes, N=No
     * @ORM\Column(name="finals", type="string", length=1, nullable=false)
     */
    private $finals;

    /**
     * @var integer $matchtime
     * Matches generated by this playground attribute relation durate for the specified time in minutes
     * Note: this amount includes all breaks - before, during, and after the match
     * @ORM\Column(name="matchtime", type="integer", nullable=false)
     */
    private $matchtime;

    /**
     * @var integer $order
     * Order of assignment of matches for playgrounds - low numbers have highest priority
     * @ORM\Column(name="order", type="integer", nullable=false)
     */
    private $order;

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
     * Set parent id - related playground attributes
     *
     * @param integer $pid
     * @return PARelation
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
    
        return $this;
    }

    /**
     * Get parent id - related playground attributes
     *
     * @return integer 
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * Set child id - related category
     *
     * @param integer $cid
     * @return PARelation
     */
    public function setCid($cid)
    {
        $this->cid = $cid;
    
        return $this;
    }

    /**
     * Get child id - related category
     *
     * @return integer 
     */
    public function getCid()
    {
        return $this->cid;
    }
    
    /**
     * Set match time
     *
     * @param integer $matchtime
     * @return PARelation
     */
    public function setMatchtime($matchtime)
    {
        $this->matchtime = $matchtime;
    
        return $this;
    }

    /**
     * Get match time
     *
     * @return integer 
     */
    public function getMatchtime()
    {
        return $this->matchtime;
    }

    /**
     * Set order
     *
     * @param integer $order
     * @return PARelation
     */
    public function setOrder($order)
    {
        $this->order = $order;
    
        return $this;
    }

    /**
     * Get order
     *
     * @return integer 
     */
    public function getOrder()
    {
        return $this->order;
    }
}