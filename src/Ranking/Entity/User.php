<?php
namespace Ranking\Entity;

use \DateTime;
use \DateTimeZone;
use \Exception;
use \InvalidArgumentException as Argument;
use Ranking\Validation\Validator as V;

/**
 * @Entity
 * @Table(name="user")
 */
class User
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
     * @Column(type="string", length=32)
     */
    protected $password;
    /**
     * @Column(type="datetime")
     */
    protected $created;
    /**
     * @Column(type="string")
     */
    protected $timezone = null;
    /**
     * @Column(type="string", length=32)
     */
    protected $salt=null;

    public function getId()
    {
        return $this->id;
    }

    public function setName($string)
    {
        v::nick()->assert($string);
        $this->name = $string;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPassword($plaintext)
    {
        v::password()->assert($plaintext);
        $this->password = $this->hashPassword($plaintext);
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function verifyPassword($plaintext)
    {
        $hashed = $this->hashPassword($plaintext);
        return strcmp($this->getPassword(), $hashed) === 0;
    }

    protected function hashPassword($plaintext)
    {
        $passwdHash = md5($plaintext.$this->getSalt());
        for ($i=0; $i<=66000; $i++) {
            $passwdHash = md5($passwdHash.RANKING_SALT);
        }
        return $passwdHash;
    }

    public function setCreated(DateTime $t=null, $tz=null)
    {
        if (!is_null($tz)) {
            $this->setTimeZone($tz);
        }
        if (is_null($t)) {
            $tz = $this->getTimeZone();
            $t  = new DateTime('now', new DateTimeZone($tz));
        }
        $this->created = $t;
        return $this;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setTimeZone($mixed)
    {
        if ($mixed instanceof DateTimeZone) {
            $this->timezone = $mixed->getName();
            return $this;
        }
        try {
            $tz             = new DateTimeZone($mixed);
            $this->timezone = $tz->getName();
        } catch (Exception $e) {
            throw new Argument('Unknown or bad timezone: '.$mixed, 0);
        }

        return $this;
    }

    public function getTimeZone()
    {
        if (!$this->timezone)
            $this->setTimezone('UTC');

        return $this->timezone;
    }

    public function getSalt()
    {
        if (empty($this->salt)) {
            $this->salt = md5(uniqid().RANKING_SALT);
        }
        return $this->salt;
    }
}
