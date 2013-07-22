<?php

namespace PaynetEasy\PaynetEasyApi\Workflow;

use PaynetEasy\PaynetEasyApi\Transport\Response;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-06-15 at 21:53:18.
 */
class AbstractWorkflowTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConcreteWorkflow
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new FakeWorkflow;
    }

    /**
     * @dataProvider testSetNeededActionProvider
     */
    public function testSetNeededAction($responseData, $neededAction)
    {
        $response = new Response($responseData);

        $this->object->setNeededAction($response);

        $this->assertEquals($neededAction, $response->getNeededAction());
    }

    public function testSetNeededActionProvider()
    {
        return array(
        array
        (
            array('status' => 'processing'),
            Response::NEEDED_STATUS_UPDATE
        ),
        array
        (
            array('redirect-url' => 'http://redirect-url.com'),
            Response::NEEDED_REDIRECT
        ),
        array
        (
            array('html' => '<html>code</html>'),
            Response::NEEDED_SHOW_HTML
        ));
    }
}
