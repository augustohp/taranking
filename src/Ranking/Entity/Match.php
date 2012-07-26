<?php
namespace Ranking\Entity;

/**
 * @Entity
 * @Table(name="match")
 */
class Match
{
    /**
     * @var integer
     * @Id
     * @Column(type="integer") 
     * @GeneratedValue
     */
    protected $id;
    /**
     * @var DateTime
     * @Column(type="datetime")
     */
    protected $played;
    /**
     * @var DateTime
     * @Column(type="datetime")
     */
    protected $created;
    /**
     * @var Ranking\Entity\User
     * @Column(type="integer")
     */
    protected $creator;
    /**
     * @var Ranking\Entity\Map
     * @Column(type="integer")
     */
    protected $map;

    public function getId()
    {
        return $this->id;
    }

    public function getPlayed()
    {
        return $this->played;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function getMap()
    {
        return $this->map;
    }
}