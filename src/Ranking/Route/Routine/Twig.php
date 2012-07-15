<?php
namespace Ranking\Route\Routine;

use Twig_Environment;
use Respect\Config\Container;

/**
 * Converts the given data into an HTML with Twig templates.
 * 
 * The given data MUST provide a value for '_view' that is the Twig view to be 
 * rendered.
 * @author Augusto Pascutti <augusto@phpsp.org.br>
 */
class Twig
{
    /**
     * @var Twig_Enviroment
     **/
    protected $twig;

    public function __construct(Twig_Environment $twig=null)
    {
        if (!is_null($twig))
            return $this->twig = $twig;
        
        $container  = new Container(RANKING_ROOT.DS.'conf'.DS.'Twig.ini');
        $this->twig = $container->twig;
    }

    /**
     * @return string|array
     */
    public function __invoke(array $data=null)
    {
        if (is_null($data))
            return '';
        
        if (!is_array($data) || !isset($data['_view']))
            return $data;

        $view      = $data['_view'];
        unset($data['_view']);
        return $this->twig->render($view, $data);
    }
}