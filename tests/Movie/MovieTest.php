<?php

namespace App\Tests\Auth;

use App\Tests\Auth\AbstarctAuthTest;

class MovieTest extends AbstarctAuthTest {

	public function testCreateMovie() {
		$this->createUser();
		$this->createAuthenticatedClient($this->getUsername(), $this->getPassword());

		$expectedData = [
				"name" => "string",
				"release_date" => "10-10-2022", 
				"director" => "string",
				"casts" => [
				    "abc",
				    "xyz",
				    "cbc"
			  	],
				"ratings" => [
			        "imdb" =>  7.8,
        			"rotten_tomatto" => 8.2
				]
			];
	    $client = $this->getClient();
	    $client->request(
	      'POST',
	      '/api/v1/movie',
	      [],
	      [],
	      ['CONTENT_TYPE' => 'application/json'],
	      json_encode($expectedData)
	    );

	    $data = json_decode($client->getResponse()->getContent(), true);
		$this->assertEquals(200, $client->getResponse()->getStatusCode());

		foreach ($expectedData as $key => $value) {
			$this->assertEquals($value, $data[$key]);
		}
	}

	public function testCreateMovieAccessDenied() {
		$expectedData = [
				"release_date" => "10-10-2022", 
				"director" => "string",
				"casts" => [
				    "abc",
				    "xyz",
				    "cbc"
			  	],
				"ratings" => [
			        "imdb" =>  7.8,
        			"rotten_tomatto" => 8.2
				]
			];
	    $client = $this->getClient();
	    $client->request(
	      'POST',
	      '/api/v1/movie',
	      [],
	      [],
	      ['CONTENT_TYPE' => 'application/json'],
	      json_encode($expectedData)
	    );

	    $data = json_decode($client->getResponse()->getContent(), true);
		$this->assertEquals(401, $client->getResponse()->getStatusCode());
	}

	public function testCreateMovieInvalidData() {
		$this->createUser();
		$this->createAuthenticatedClient($this->getUsername(), $this->getPassword());
		$expectedData = [
				"release_date" => "10-10-2022", 
				"director" => "string",
				"casts" => [
				    "abc",
				    "xyz",
				    "cbc"
			  	],
				"ratings" => [
			        "imdb" =>  7.8,
        			"rotten_tomatto" => 8.2
				]
			];
	    $client = $this->getClient();
	    $client->request(
	      'POST',
	      '/api/v1/movie',
	      [],
	      [],
	      ['CONTENT_TYPE' => 'application/json'],
	      json_encode($expectedData)
	    );

	    $exp["name"] = "name required";
	    $data = json_decode($client->getResponse()->getContent(), true);
		$this->assertEquals(400, $client->getResponse()->getStatusCode());

		foreach ($exp as $key => $value) {
			$this->assertEquals($value, $data['message'][$key]);
		}
	}

	public function testListMovie() {
		$this->createUser();
		$this->createAuthenticatedClient($this->getUsername(), $this->getPassword());
		$expectedData = [
				"name" => "string",
				"release_date" => "10-10-2022", 
				"director" => "string",
				"casts" => [
				    "abc",
				    "xyz",
				    "cbc"
			  	],
				"ratings" => [
			        "imdb" =>  7.8,
        			"rotten_tomatto" => 8.2
				]
			];
	    $client = $this->getClient();
	    $client->request(
	      'POST',
	      '/api/v1/movie',
	      [],
	      [],
	      ['CONTENT_TYPE' => 'application/json'],
	      json_encode($expectedData)
	    );

	    $data = json_decode($client->getResponse()->getContent(), true);
		$this->assertEquals(200, $client->getResponse()->getStatusCode());

		// Create another Movie with another user
		$this->createNewUser();
		$this->createAuthenticatedClient('newMockName', $this->getPassword());
		$expectedData = [
				"name" => "new string",
				"release_date" => "10-10-2022", 
				"director" => "new string",
				"casts" => [
				    "abcd",
				    "xyzdd",
				    "cbcddd"
			  	],
				"ratings" => [
			        "imdb" =>  7.8,
        			"rotten_tomatto" => 8.2
				]
			];
	    $client = $this->getClient();
	    $client->request(
	      'POST',
	      '/api/v1/movie',
	      [],
	      [],
	      ['CONTENT_TYPE' => 'application/json'],
	      json_encode($expectedData)
	    );
		$data = json_decode($client->getResponse()->getContent(), true);
		$this->assertEquals(200, $client->getResponse()->getStatusCode());

		// check list movie with another user
	    $client->request(
	      'GET',
	      '/api/v1/movie',
	      [],
	      [],
	      ['CONTENT_TYPE' => 'application/json']
	    );
		
		$data = json_decode($client->getResponse()->getContent(), true);
	    $this->assertEquals(200, $client->getResponse()->getStatusCode());
	    $this->assertEquals(1, count($data));
	    foreach($expectedData as $key => $value) {
	    	$this->assertEquals($value, $data[0][$key]);
	    }
	}

	public function testGetMovieById() {
		// Create another Movie with another user
		$this->createNewUser();
		$this->createAuthenticatedClient('newMockName', $this->getPassword());
		$expectedData = [
				"name" => "new string",
				"release_date" => "10-10-2022", 
				"director" => "new string",
				"casts" => [
				    "abcd",
				    "xyzdd",
				    "cbcddd"
			  	],
				"ratings" => [
			        "imdb" =>  7.8,
        			"rotten_tomatto" => 8.2
				]
			];
	    $client = $this->getClient();
	    $client->request(
	      'POST',
	      '/api/v1/movie',
	      [],
	      [],
	      ['CONTENT_TYPE' => 'application/json'],
	      json_encode($expectedData)
	    );
		$data = json_decode($client->getResponse()->getContent(), true);
		$this->assertEquals(200, $client->getResponse()->getStatusCode());

		// check list movie with another user
	    $client->request(
	      'GET',
	      '/api/v1/movie/1',
	      [],
	      [],
	      ['CONTENT_TYPE' => 'application/json']
	    );
		
		$data = json_decode($client->getResponse()->getContent(), true);
	    $this->assertEquals(200, $client->getResponse()->getStatusCode());
	    foreach($expectedData as $key => $value) {
	    	$this->assertEquals($value, $data[$key]);
	    }

	    // Another user cannot access movie created by first user
		$this->createUser();
		$this->createAuthenticatedClient($this->getUsername(), $this->getPassword());

	    $client->request(
	      'GET',
	      '/api/v1/movie/1',
	      [],
	      [],
	      ['CONTENT_TYPE' => 'application/json']
	    );
		
		$data = json_decode($client->getResponse()->getContent(), true);

		$result["message"] =  "no data found!";
	    $this->assertEquals(200, $client->getResponse()->getStatusCode());

	    foreach($result as $key => $value) {
	    	$this->assertEquals($value, $data[$key]);
	    }
	}
}