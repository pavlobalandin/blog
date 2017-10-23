<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
	public function testIndex()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/admin');

		$this->assertEquals(302, $client->getResponse()->getStatusCode());
		$this->assertRegExp('/Redirecting to.*?login/', $client->getResponse()->getContent());
	}
}


