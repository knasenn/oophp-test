<?php

namespace Aiur\Hundra;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Guess.
 */
class ClassHundraTest extends TestCase
{
    /**
     * Testing the method testChangePlayer. It verifyes that the correct value -
     * is returned
     */
    public function testValueChangePlayer()
    {
        $hundra = new Player();
        $this->assertInstanceOf("\Aiur\Hundra\Player", $hundra);

        $res = $hundra->changePlayer(1);
        $exp = 2;
        $this->assertEquals($exp, $res);

        $res = $hundra->changePlayer(2);
        $exp = 1;
        $this->assertEquals($exp, $res);
    }

    /**
     * Testing the method testChangePlayer. It verifyes that the correct type -
     * is returned
     */
    public function testTypeChangePlayer()
    {
        $hundra = new Player();
        $this->assertInstanceOf("\Aiur\Hundra\Player", $hundra);

        $res = $hundra->changePlayer(1);
        $this->assertInternalType("int", $res);
    }


    /**
     * Testing the method testChangePlayer. It verifyes that the correct type -
     * is returned
     */
    public function testValueCheckPlayer()
    {
        $hundra = new Player();
        $this->assertInstanceOf("\Aiur\Hundra\Player", $hundra);

        $res = $hundra->checkPlayer(100);
        $this->assertInternalType("bool", $res);

        $res = $hundra->checkPlayer(1);
        $this->assertInternalType("bool", $res);
    }




    /**
     * Testing the method roll. It verifyes that INT is returned
     *
     */
    public function testRollInt()
    {
        $hundra = new Dicehand();
        $this->assertInstanceOf("\Aiur\Hundra\Dicehand", $hundra);

        $res = $hundra->roll();
        $this->assertInternalType("int", $res[0], $res[1]);
    }


    /**
     * Testing the method roll. It verifyes that ARRAY is returned
     *
     */
    public function testRollArray()
    {
        $hundra = new Dicehand();
        $this->assertInstanceOf("\Aiur\Hundra\Dicehand", $hundra);

        $res = $hundra->roll();
        $this->assertInternalType('array', $res);
    }


    /**
     * Testing the method check. It verifyes that the correct value -
     * is returned
     */
    public function testCheck()
    {
        $hundra = new Dicehand();
        $this->assertInstanceOf("\Aiur\Hundra\Dicehand", $hundra);

        $res = $hundra->check(array(1,1), 1);
        $exp = array(0, 2);
        $this->assertEquals($exp, $res);

        $res2 = $hundra->check(array(2,2), 1);
        $exp2 = array(4, 1);
        $this->assertEquals($exp2, $res2);
    }


    /**
     * Testing the method check. It verifyes that the correct value -
     * is returned
     */
    public function testRollDiceType()
    {
        $hundra = new Dice();
        $this->assertInstanceOf("\Aiur\Hundra\Dice", $hundra);

        $res = $hundra->rollDice();
        $this->assertInternalType('int', $res);
    }


    /**
     * Testing the method check. It verifyes that the correct value -
     * is returned
     */
    public function testAddDiceValue()
    {
        $hundra = new Dice();
        $this->assertInstanceOf("\Aiur\Hundra\Dice", $hundra);

        $res = $hundra->addDice(1, 1);
        $exp = 2;
        $this->assertEquals($exp, $res);
    }


    /**
     * Testing the method check. It verifyes that the correct value -
     * is returned
     */
    public function testAddDiceType()
    {
        $hundra = new Dice();
        $this->assertInstanceOf("\Aiur\Hundra\Dice", $hundra);

        $res = $hundra->AddDice(1, 1);
        $this->assertInternalType('int', $res);
    }
}
