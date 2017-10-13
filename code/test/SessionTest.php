<?php

namespace Test;

use Library\Session;

class SessionTest extends BaseTestCase
{

    /*
     *
     */
    public function testSessionStart()
    {
        //$this->markTestIncomplete('TODO FINISH ME!');
        $session = (new Session);
        $session->start();

        $this->assertNotNull(session_id());
    }

    public function testGetSetSession()
    {
        //$this->markTestIncomplete('TODO FINISH ME!');
        $items = [
            'teszt1' => 'tesz_val1',
            'teszt2' => 'tesz_val2',
            'teszt3' => 'tesz_val3',
        ];
        $session = (new Session);
        $session->start();
        $session->setArray($items);

        $this->assertEquals($items, $session->getAll());
        $this->assertEquals($_SESSION, $session->getAll());
    }

    public function testPushAndPop()
    {
        $stack = [];
        $this->assertEquals(0, count($stack));

        array_push($stack, 'foo');
        $this->assertEquals('foo', $stack[count($stack) - 1]);
        $this->assertEquals(1, count($stack));

        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));
    }
}
