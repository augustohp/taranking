<?php
namespace Ranking\Entity;

use \DateTime;
use \InvalidArgumentException as Argument;
use Respect\Validation\Validator as V;
use Ranking\Entity\Map;
use Ranking\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="`match`")
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
     * @Column(type="integer", name="user_id")
     */
    protected $creator;
    /**
     * @var Ranking\Entity\Map
     * @Column(type="integer", name="map_id")
     */
    protected $map;
    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     * @OneToMany(targetEntity="Ranking\Entity\Team", mappedBy="match")
     */
    protected $teams;

    public function __construct()
    {
        $this->teams = new ArrayCollection;
    }

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

    public function getTeams()
    {
        return $this->teams;
    }

    public static function getTeamValidator()
    {
        return V::attribute(
            'player', V::instance('Ranking\Entity\User'),
            'race', Team::getRaceValidator(),
            'number', Team::getNumberValidator()
        );
    }

    public function addTeam(Team $team)
    {
        if ($this->teams->contains($team) ) {
            throw new Argument('Team already exists in collection');
        }
        self::getTeamValidator()->assert($team);
        $this->teams[] = $team;
        $team->setMatch($this);
        return $this;
    }
}