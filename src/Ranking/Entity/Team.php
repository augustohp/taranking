<?php
namespace Ranking\Entity;

use Respect\Validation\Validator as V;

/**
 * @Entity
 * @Table(name="team")
 */
class Team
{
    /**
     * @var integer
     * @Id
     * @Column(type="integer") 
     * @GeneratedValue
     */
    protected $id;
    /**
     * @var Ranking\Entity\Match
     * @Column(type="integer", name="match_id")
     */
    protected $match;
    /**
     * @var Ranking\Entity\User
     * @Column(type="integer", name="user_id")
     */
    protected $player;
    /**
     * @var string
     * @Column(type="string", name="user_id")
     */
    protected $race;
    /**
     * @var integer
     * @Column(type="smallint")
     */
    protected $number;

    public function getId()
    {
        return $this->id;
    }

    public function setMatch(Match $match)
    {
        $this->match = $match;
        return $this;
    }

    public function getMatch()
    {
        return $this->match;
    }

    public function setPlayer(User $player)
    {
        $this->player = $player;
        return $this;
    }

    public function getPlayer()
    {
        return $this->player;
    }

    public static function getRaceValidator()
    {
        return V::oneOf(
            V::alpha()->equals('Core'),
            V::alpha()->equals('Arm')
        )->setName('Race');
    }

    public function setRace($race)
    {
        self::getRaceValidator()->assert($race);
        $this->race = $race;
        return $this;
    }

    public function getRace()
    {
        return $this->race;
    }

    public static function getNumberValidator()
    {
        return V::int()->min(1, true)->max(10, true)->setName('Team Number');
    }

    public function setNumber($num)
    {
        self::getNumberValidator()->assert($num);
        $this->number = $num;
        return $this;
    }

    public function getNumber()
    {
        return $this->number;
    }
}