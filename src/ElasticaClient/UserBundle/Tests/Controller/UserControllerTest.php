<?php

namespace ElasticaClient\UserBundle\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use ElasticaClient\UserBundle\Tests\Fixture\Entity\UserDataFixture;

/**
 * UserController Test
 *
 * @author ahmet akbana
 */
class UserControllerTest extends WebTestCase {

	public function setUp() {
		$this -> client = static::createClient();
		$this -> elasticaService = static::$kernel -> getContainer() -> get('elastica.user.service');
	}

	/**
	 * Test of getUsersAction in json format
	 */
	public function testJsonGetPagesAction() {
		$route = $this -> client -> getContainer() -> get('router') -> generate('api_v1_get_users', array('_format' => 'json'));
		$this -> client -> request('GET', $route);
		$this -> assertJsonResponse($this -> client -> getResponse(), 200);
	}

	/**
	 * Test of get single user action in json fromat
	 */
	public function testJsonGetPageAction() {
		$fixture = new UserDataFixture($this -> elasticaService);
		$fixture -> addUser();
		$users = $fixture -> users;
		$user = array_pop($users);

		$route = $this -> client -> getContainer() -> get('router') -> generate('api_v1_get_user', array('userID' => $user -> getId(), '_format' => 'json'));

		$this -> client -> request('GET', $route, array('ACCEPT' => 'application/json'));
		$response = $this -> client -> getResponse();

		$this -> assertJsonResponse($response, 200);

		$content = json_decode($response -> getContent(), true);

		$this -> assertTrue(isset($content['user']['name']));

		$fixture -> deleteUser();
	}

	/**
	 * Test of post user by sending a user with an existing email
	 */
	public function testJsonPostUserActionWithExistingUserEmail() {
		$route = $this -> client -> getContainer() -> get('router') -> generate('api_v1_post_user', array('_format' => 'json'));

		$fixture = new UserDataFixture($this -> elasticaService);
		$fixture -> addUser();

		$this -> client -> request('POST', $route, array(), array(), array('CONTENT_TYPE' => 'application/json'), '{"name":"john", "email":"john@doe.com", "password":"test"}');

		$response = $this -> client -> getResponse();
		$this -> assertJsonResponse($response, 200, false);
		$content = json_decode($response -> getContent(), true);
		$this -> assertTrue(isset($content['error']));
		$fixture -> deleteUser();
	}

	/**
	 * Test of post user by sending a user with a non existing email
	 */
	public function testJsonPostUserActionWithNonExistingUserEmail() {
		$route = $this -> client -> getContainer() -> get('router') -> generate('api_v1_post_user', array('_format' => 'json'));

		$fixture = new UserDataFixture($this -> elasticaService);
		$fixture -> deleteUserByEmail('john@doe.com');

		$this -> client -> request('POST', $route, array(), array(), array('CONTENT_TYPE' => 'application/json'), '{"name":"john", "email":"john@doe.com", "password":"test"}');

		$this -> assertJsonResponse($this -> client -> getResponse(), 201, false);
		$fixture -> deleteUserByEmail('john@doe.com');

	}

	/**
	 * Test of post a user with bad paramteres
	 */
	public function testJsonPostUserActionWithBadParameters() {
		$route = $this -> client -> getContainer() -> get('router') -> generate('api_v1_post_user', array('_format' => 'json'));

		$this -> client -> request('POST', $route, array(), array(), array('CONTENT_TYPE' => 'application/json'), '{"param":"bad"}');

		$this -> assertJsonResponse($this -> client -> getResponse(), 400, false);
	}

	/**
	 * Test of put user by sending a user with an existing email
	 */
	public function testJsonPutUserActionWithExistingUserEmail() {
		$fixture = new UserDataFixture($this -> elasticaService);
		$fixture -> addUser();
		$users = $fixture -> users;
		$user = array_pop($users);

		$route = $this -> client -> getContainer() -> get('router') -> generate('api_v1_put_user', array('userID' => 000000000, '_format' => 'json'));

		$this -> client -> request('PUT', $route, array(), array(), array('CONTENT_TYPE' => 'application/json'), '{"name":"john", "email":"john@doe.com", "password":"test"}');

		$response = $this -> client -> getResponse();
		$this -> assertJsonResponse($response, 200, false);
		$content = json_decode($response -> getContent(), true);
		$this -> assertTrue(isset($content['error']));
		$fixture -> deleteUser();
	}

	/**
	 * Test of post user by sending a user with a non existing email
	 */
	public function testJsonPutUserActionWithNonExistingUserEmail() {
		$fixture = new UserDataFixture($this -> elasticaService);
		$fixture -> addUser();
		$users = $fixture -> users;
		$user = array_pop($users);

		$route = $this -> client -> getContainer() -> get('router') -> generate('api_v1_put_user', array('userID' => $user -> getId(), '_format' => 'json'));

		$this -> client -> request('PUT', $route, array(), array(), array('CONTENT_TYPE' => 'application/json'), '{"name":"jack", "email":"jack@test.com", "password":"test"}');

		$this -> assertJsonResponse($this -> client -> getResponse(), 201, false);
		$checkedUser = $fixture->getUserByEmail('jack@test.com');
		$this->assertEquals('jack', $checkedUser->getName());
		$this->assertEquals(md5('test'), $checkedUser->getPassword());
		$fixture -> deleteUserByEmail('jack@test.com');

	}

	/**
	 * Test of deleting an existing user
	 */
	public function testUserDeleteExistingUser() {
		$fixture = new UserDataFixture($this -> elasticaService);
		$fixture -> addUser();
		$users = $fixture -> users;
		$user = array_pop($users);
		$route = $this -> client -> getContainer() -> get('router') -> generate('api_v1_delete_user', array('userID' => $user -> getId(), '_format' => 'json'));
		$this -> client -> request('DELETE', $route);
		$response = $this -> client -> getResponse();
		$this -> assertJsonResponse($response, 200, false);
		$content = json_decode($response -> getContent(), true);
		$this -> assertTrue(isset($content['code']));
		$fixture -> deleteUser();
	}

	/**
	 * Test of deleting a non existing user
	 */
	public function testUserDeleteNonExistingUer() {
		$route = $this -> client -> getContainer() -> get('router') -> generate('api_v1_delete_user', array('userID' => 111111111, '_format' => 'json'));
		$this -> client -> request('DELETE', $route);

		$this -> assertJsonResponse($this -> client -> getResponse(), 404, false);
	}

	/**
	 * Assertation of Json Response
	 */
	protected function assertJsonResponse($response, $statusCode = 200, $checkValidJson = true, $contentType = 'application/json') {
		$this -> assertEquals($statusCode, $response -> getStatusCode(), $response -> getContent());
		$this -> assertTrue($response -> headers -> contains('Content-Type', $contentType), $response -> headers);
		if ($checkValidJson) {
			$decode = json_decode($response -> getContent());
			$this -> assertTrue(($decode != null && $decode != false), 'is response valid json: [' . $response -> getContent() . ']');
		}
	}

}
