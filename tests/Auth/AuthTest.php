<?php

namespace App\Tests\Auth;

class AuthTest extends AbstarctAuthTest {

	public function testLogin() {
		$this->createUser();
		$client = $this->createAuthenticatedClient($this->getUsername(), $this->getPassword());
	}

	public function testLoginFail() {
	    $client = $this->getClient();
	    $client->request(
	      'POST',
	      '/api/login_check',
	      [],
	      [],
	      ['CONTENT_TYPE' => 'application/json'],
	      json_encode([
	        'username' => 'test',
	        'password' => 'test',
	      ])
	    );

	    $data = json_decode($client->getResponse()->getContent(), true);
		
		$this->assertEquals(401, $client->getResponse()->getStatusCode());
	}

	public function testRegister() {

	    $client = $this->getClient();
	    $client->request(
	      'POST',
	      '/api/v1/register',
	      [],
	      [],
	      ['CONTENT_TYPE' => 'application/json'],
	      json_encode([
			  "name" => "string",
			  "username" => "stringhhhh",
			  "email" => "string@sting.com",
			  "password" => "password1"
	      ])
	    );

	    $data = json_decode($client->getResponse()->getContent(), true);

		$expectedData = ["name" => "string", "username" => "stringhhhh","email" => "string@sting.com"];
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		foreach ($expectedData as $key => $value) {
			$this->assertEquals($value, $data[$key])	;
		}


	    $client = $this->getClient();
	    $client->request(
	      'POST',
	      '/api/v1/register',
	      [],
	      [],
	      ['CONTENT_TYPE' => 'application/json'],
	      json_encode([
			  "email" => "string@sting.com",
			  "password" => "password1"
	      ])
	    );

	    $data = json_decode($client->getResponse()->getContent(), true);

		$expectedData = ["name" => "name required", "username" => "username required"];
		$this->assertEquals(400, $client->getResponse()->getStatusCode());

		foreach ($expectedData as $key => $value) {
			$this->assertEquals($value, $data['message'][$key]);
		}
	}
}
