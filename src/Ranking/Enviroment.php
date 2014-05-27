<?php
namespace Ranking;

use StdClass;
use InvalidArgumentException as Argument;
use BadMethodCallException as Method;

/**
 * Class to retrieve enviroment variables.
 *
 * @author Augusto Pascutti
 */
class Enviroment
{
    const DEFAULT_SALT = '4l:8_+:7|WxsE+O+JN&w_Wr$mRc?0l88oRTD$OcJuOI^Qk&852H1)%W{yc+-BmwY';
    const DEFAULT_NAME = 'development';
    const DEFAULT_DB_HOST = 'localhost';
    const DEFAULT_DB_USER = 'root';
    const DEFAULT_DB_PASSWD = 'root';
    const DEFAULT_DB_NAME = 'ranking';
    const DEFAULT_DB_DRIVER = 'pdo_mysql';

    protected $name;
    protected $salt;
    protected $db;

    public function getName()
    {
        if (!$this->name) {
            $this->name = getenv('RANKING_ENVIROMENT') ?: self::DEFAULT_NAME ;
        }

        return $this->name;
    }

    public function getSalt()
    {
        if (!$this->salt) {
            $this->salt = getenv('RANKING_SALT') ?: self::DEFAULT_SALT ;
        }
        return $this->salt;
    }

    public function getDatabase($optionName=null)
    {
        if (!$this->db) {
            $this->db = new StdClass;
            $this->db->host = getenv('RANKING_DB_HOST') ?: self::DEFAULT_DB_HOST;
            $this->db->user = getenv('RANKING_DB_USER') ?: self::DEFAULT_DB_USER;
            $this->db->passwd = (false === getenv('RANKING_DB_PASSWD')) ? self::DEFAULT_DB_PASSWD : getenv('RANKING_DB_PASSWD');
            $this->db->name = getenv('RANKING_DB_NAME') ?: self::DEFAULT_DB_NAME;
            $this->db->driver = getenv('RANKING_DB_DRIVER') ?: self::DEFAULT_DB_DRIVER;
        }
        if (is_null($optionName)) {
            return $this->db;
        }
        if (isset($this->db->$optionName)) {
            return $this->db->$optionName;
        }
        $msg = 'Option "%s" not found';
        throw new Argument(sprintf($msg, $optionName));
    }

    /**
     * Shortcut to call database options as whole methods.
     *
     * @magic
     * @example Enviroment::getDatabaseHost()
     * @return string
     */
    public function __call($name, $args)
    {
        $needle = 'getDatabase';
        if (strpos($name, $needle) === false) {
            $msg = 'Method "%s" not found';
            throw new Method(sprintf($msg, $name));
        }

        $sufix = str_replace($needle, '', $name);
        $sufix = strtolower($sufix);
        return $this->getDatabase($sufix);
    }
}
