<?php

namespace Ranking\Test\Context;

use Respect\Config;
use Ranking;
use Behat\Behat;
use Symfony\Component\Console;

class Cli extends Behat\Context\BehatContext
{
    const OPTION_DOCTRINE_CONFIG_FILE = 'doctrine_container';
    /** @var array */
    private $contextOptions;
    /** @var array */
    private $currentCommandArguments;

    public function __construct(array $arguments)
    {
        $this->contextOptions = $arguments;
    }

    /**
     * @Given /^I have CLI command "([^"]*)"$/
     */
    public function createCommandExecution($commandName)
    {
        $this->currentCommandArguments = array('command' => $commandName);
    }

    /**
     * @Given /^I add option "([^"]*)" to command$/
     * @Given /^I add argument "([^"]*)" to command as "([^"]*)"$/
     */
    public function appendCommandArgument($optionName, $optionValue=true)
    {
        $this->currentCommandArguments[$optionName] = $optionValue;
    }

    /**
     * @Given /^I (run|execute) command$/
     */
    public function executeCommand()
    {
        $doctrineConfigurationContainer = new Config\Container($this->contextOptions[self::OPTION_DOCTRINE_CONFIG_FILE]);
        $cliApplication = new Ranking\Cli\Application($doctrineConfigurationContainer);
        $cliApplication->setAutoExit(false);
        $cliTester = new Console\Tester\ApplicationTester($cliApplication);
        $cliTester->run($this->currentCommandArguments);
    }


}
