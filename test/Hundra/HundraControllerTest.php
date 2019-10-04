<?php

namespace Aiur\Hundra;

use Anax\DI\DIMagic;
use Anax\Response\ResponseUtility;
use PHPUnit\Framework\TestCase;

/**
 * Test the controller like it would be used from the router,
 * simulating the actual router paths and calling it directly.
 */
class HundraControllerTest extends TestCase
{
    private $controller;
    private $app;


    /**
     * Call the controller index action.
     */
    public function setUp(): void
    {
        global $di;

        // Init service container $di to contain $app as a service
        $di = new DIMagic();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $app = $di;
        $this->app = $app;
        $di->set("app", $app);



        $this->controller = new HundraController();
        $this->controller->setApp($app);
    }




    /**
     * Call the controller index action.
     */
    public function testIndexAction()
    {
        $res = $this->controller->indexAction();
        $this->assertIsString($res);
        $this->assertContains("Index", $res);
    }

    /**
     * Call the controller debug action.
     */
    public function testDebugAction()
    {
        $res = $this->controller->debugActionGet();
        $this->assertIsString($res);
        $this->assertContains("Debug", $res);
    }


    // /**
    //  * Call the controller init action.
    //  */
    // public function testInitAction()
    // {
    //     $res = $this->controller->initActionGet();
    //     $this->assertInstanceOf(ResponseUtility::class, $res);
    // }



    /**
     * Call the controller play GET action.
     */
    public function testPlayActionGet()
    {
        $res = $this->controller->playActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }


    /**
     * Call the controller play POST action.
     */
    public function testPlayActionPost()
    {
        //RESET
        $this->app->request->setGlobals([
            "post" => [
                "reset" => "reset",
            ]
        ]);

        $res = $this->controller->playActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        //ROLL
        $this->app->request->setGlobals([
            "post" => [
                "roll" => "roll",
            ]
        ]);

        $res = $this->controller->playActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        //SAVE
        $this->app->request->setGlobals([
            "post" => [
                "save" => "save",
                "check" => [
                    0,
                ]
            ]
        ]);

        $res = $this->controller->playActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }


    /**
     * Call the controller playComputer GET action.
     */
    public function testPlayCompActionGet()
    {
        //SAVE
        $this->app->request->setGlobals([
            "rollChance1" => [0],
        ]);

        $res = $this->controller->playCompActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);

        //SAVE
        $this->app->request->setGlobals([
            "rollChance2" => [0],
        ]);

        $res = $this->controller->playCompActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }
}
