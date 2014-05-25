<?php

namespace Ranking\Test\Context;

use Behat\Behat;
use Behat\Behat\Context\Step;

class Main extends Behat\Context\BehatContext
{
    /**
     * Base url used for testing.
     */
    private $baseUrl;

    public function __construct(array $params)
    {
        $this->baseUrl = $params['base_url'];
        $this->appendSubContexts($params);
    }

    private function appendSubContexts($params)
    {
        $this->useContext('cli', new Cli($params));
        $this->useContext('web', new Web($params));
    }

    /**
     * @Given /^I am the first visitor$/
     */
    public function bootstrapApplication()
    {
        return array(
            new Step\Given('I have CLI command "orm:schema-tool:drop"'),
            new Step\Given('I add option "--force" to command'),
            new Step\Given('I run command'),
            new Step\Given('I have CLI command "orm:schema-tool:update"'),
            new Step\Given('I add option "--force" to command'),
            new Step\Given('I run command'),
        );
    }
}
