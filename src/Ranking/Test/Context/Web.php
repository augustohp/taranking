<?php

namespace Ranking\Test\Context;

use Behat\Behat;
use Behat\Mink;

class Web extends Behat\Context\BehatContext
{
    const OPTION_BASE_URL = 'base_url';
    /** @var array */
    private $contextOptions;
    /** @var Behat\Mink\Session */
    private $browserSession;

    public function __construct(array $arguments)
    {
        $this->contextOptions = $arguments;
    }

    protected function createUrlFromPath($resourcePath)
    {
        $baseUrl = $this->contextOptions[self::OPTION_BASE_URL];
        $baseUrl = rtrim($baseUrl, '/');
        $resourcePath = ltrim($resourcePath, '/');

        return sprintf('%s/%s', $baseUrl, $resourcePath);
    }

    protected function fetchElement($searchSelector)
    {
        $page = $this->browserSession->getPage();
        $foundElements = $page->find('css', $searchSelector);
        if (empty($foundElements)) {
            throw new Mink\Exception\ElementNotFoundException($this->browserSession, $searchSelector.' ');
        }

        return $foundElements;
    }

    /**
     * @Given /^I visit "([^"]*)"$/
     */
    public function createBrowsingSession($resourcePath)
    {
        $driver = new Mink\Driver\GoutteDriver();
        $selectorHandler = new Mink\Selector\SelectorsHandler();
        $browserSession = new Mink\Session($driver, $selectorHandler);
        $browserSession->start();
        $browserSession->setRequestHeader('Accept', '*/*');
        $browserSession->visit($this->createUrlFromPath($resourcePath));
        $this->browserSession = $browserSession;

        return $this->browserSession;
    }

    /**
     * @Given /^I input "([^"]*)" on "([^"]*)"$/
     */
    public function setValueOnInputElement($value, $cssSelector)
    {
        $element = $this->fetchElement($cssSelector);
        $element->setValue($value);
    }

    /**
     * @Given /^I click on "([^"]*)"$/
     */
    public function clickAndFollowLink($elementText="#submit")
    {
        $link = $this->fetchElement($elementText);
        if ($link) {
            return $link->click();
        }

        throw new Mink\Exception\ElementNotFoundException($this->browserSession, $elementText.' ');
    }

    /**
     * @Then /^I should see "([^"]*)" on "([^"]*)"$/
     * @Then /^I should see "([^"]*)"$/
     */
    public function iShouldSee($expectedContent, $elementSelector="BODY")
    {
        $mink = $this->browserSession;
        $page = $mink->getPage();
        $elementContext = $this->fetchElement($elementSelector);
        $content = $elementContext->getText();
        if (false === stripos($content, $expectedContent)) {
            $message = sprintf('Content not found: %s', $expectedContent);
            throw new Mink\Exception\ExpectationException($message, $mink);
        }
    }
}
