<?php
use Ranking\Route\Routine\Json;

class JsonTest extends PHPUnit_Framework_TestCase
{
    public function testArrayToJsonConversion()
    {
        $data = array('user'=>'tbone');
        $json = new Json();
        $encoded = $json($data);
        $this->assertEquals($encoded, json_encode($data));
    }

    public function testArrayToJsonWithViewKey()
    {
        $data = array('user'=>'tbone', '_view'=>'index.html');
        $json = new Json();
        $encoded = $json($data);
        unset($data['_view']);
        $this->assertEquals($encoded, json_encode($data), 'Index "_view" not removed from JSON');
    }
}