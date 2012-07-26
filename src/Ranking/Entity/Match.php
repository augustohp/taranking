<?php
namespace Ranking\Entity;

use \DateTime;
use Respect\Validation\Validator as V;
use Ranking\Entity\Map;
use Ranking\Entity\User;

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

    public function setPlayed(DateTime $when=null)
    {
        if (is_null($when)) {
            $when = new DateTime;
        }
        V::date()->setName('Played')->assert($when);
        $this->played = $when;
        return $this;
    }

    public function getPlayed()
    {
        return $this->played;
    }

    public function setCreated(DateTime $when=null)
    {
        if (is_null($when)) {
            $when = new DateTime;
        }
        V::date()->setName('Crated')->assert($when);
        $this->created = $when;
        return $this;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreator(User $creator)
    {
        $this->creator = $creator;
        return $this;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function setMap(Map $map)
    {
        $this->map = $map;
        return $this;
    }

    public function getMap()
    {
        return $this->map;
    }
}