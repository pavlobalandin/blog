<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
	public function testIndex()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertContains('Blog Public', $client->getResponse()->getContent());
	}

	public function testNotFound()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/somenotexisting');
		$this->assertEquals(404, $client->getResponse()->getStatusCode());
	}
}
