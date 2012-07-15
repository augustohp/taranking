<?php
use Respect\Config\Container;
use Ranking\Route\Routine\Twig;

class TwigTest extends PHPUnit_Framework_TestCase
{
    public function testConstructWithoutArguments()
    {
        $twig = new Twig;
        $this->assertAttributeInstanceOf('Twig_Environment', 'twig', $twig);
    }

    public function testConstructWithTwigEnviroment()
    {
        $container = new Container(RANKING_ROOT.DS.'conf'.DS.'Twig.ini');
        $instance  = $container->twig;
        $twig      = new Twig($instance);
        $this->assertAttributeInstanceOf('Twig_Environment', 'twig', $twig);
        $this->assertAttributeEquals($instance, 'twig', $twig);
    }

    public function testRenderIndexTemplate()
    {
        $twig = new Twig();
        $data = array('_view'=>'index.html');
        $this->assertGreaterThan(0, strlen($twig($data)));
    }
}