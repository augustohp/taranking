<?php
namespace Ranking\Entity;

use \DateTime;
use Respect\Validation\Validator as V;

/**
 * @Entity
 * @Table(name="map")
 */
class Map
{
    /** 
     * @Id
     * @Column(type="integer") 
     * @GeneratedValue
     */
    protected $id;
    /**
     * @Column(type="string", length=45, unique=true)
     */
    protected $name;
    /**
     * @Column(type="datetime")
     */
    protected $created;

    public function __toString()
    {
        return 'Ranking\Entity\Map: '.$this->getName();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($string)
    {
        $allowedChars = '-_[]/\\.';
        V::alnum($allowedChars)->length(5, 255)
         ->setName('Map name')->assert($string);
        $this->name = $string;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setCreated(DateTime $t=null)
    {
        if (is_null($t)) {
            $t  = new DateTime('now');
        }
        $this->created = $t;
        return $this;
    }

    public function getCreated()
    {
        return $this->created;
    }
}