<?php
namespace Ranking\Entity;

use \DateTime;
use \DateTimeZone;
use InvalidArgumentException as Argument;
use Respect\Validation\Validator as V;

class User
{
    protected $id;
    protected $name;
    protected $password;
    protected $created;
    protected $timezone = null;
    protected $salt=null;

    public function getId()
    {
        return $this->id;
    }

    public function setName($string)
    {
        $allowedChars = '-_';
        V::alnum($allowedChars)->noWhitespace()->length(3, 45)->assert($string);
        $this->name = $string;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPassword($plaintext)
    {
        if (!V::alnum()->length(6, 45)->validate($plaintext)) {
            $msg = 'Password must be only alpha-numeric and have length between 6 and 45';
            throw new Argument($msg);
        }
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
            $t = new DateTime('now', $this->getTimeZone());
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
            $this->timezone = $mixed;
            return $this;
        }
        try {
            $this->timezone = new DateTimeZone($mixed);
        } catch (Exception $e) {
            throw new Argument('Unknown or bad timezone: '.$mixed, 0);
        }
        
        return $this;
    }

    public function getTimeZone()
    {
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