<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
	public function testIndex()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/api');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$response = json_decode($client->getResponse()->getContent(), TRUE);
		$this->assertArrayHasKey('posts', $response);
	}
}
