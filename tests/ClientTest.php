<?php

use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testInstantiation()
    {
        $client = new \klingmotiocontrol\Client();
        $this->assertNotNull($client);
    }
}