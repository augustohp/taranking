<?php

namespace Ranking\Cli;

use Respect\Config;
use Doctrine\ORM;
use Symfony\Component\Console;

class Application extends Console\Application
{
    private $doctrineContainer;
    private $entityManager;
    private $doctrineConsoleRunner;

    public function __construct(Config\Container $doctrineContainer)
    {
        $this->doctrineConsoleRunner = new ORM\Tools\Console\ConsoleRunner();
        $this->entityManager = $doctrineContainer->entityManager;
        parent::__construct('TA Ranking Console Application');
    }

    protected function getDefaultCommands()
    {
        $symfonyCommands = parent::getDefaultCommands();
        $ourCommands = array();
        $this->doctrineConsoleRunner->addCommands($this);

        return array_merge($symfonyCommands, $ourCommands);
    }

    protected function getDefaultHelperSet()
    {
        $symfonyHelperSet = parent::getDefaultHelperSet();
        $doctrineHelperSet = $this->doctrineConsoleRunner->createHelperSet($this->entityManager);
        foreach ($doctrineHelperSet as $helperAlias=>$helper) {
            $symfonyHelperSet->set($helper, $helperAlias);
        }

        return $symfonyHelperSet;
    }
}
