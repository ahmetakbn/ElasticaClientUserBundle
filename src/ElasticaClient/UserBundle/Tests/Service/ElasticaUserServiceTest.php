<?php

namespace ElasticaClient\UserBundle\Tests\Service;

use ElasticaClient\UserBundle\Entity\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests ElasticaUserService
 *
 * @author ahmet akbana
 */
class ElasticaUserServiceTest extends WebTestCase {

	/**
	 * Instance of ElasticaUserService
	 *
	 * @var string
	 */
	private $elasticaService;

	/**
	 * Boots Symfony Kernel and gets elastica.user service
	 */
	public function setUp() {
		$kernel = static::createKernel();
		$kernel -> boot();

		$this -> elasticaService = $kernel -> getContainer() -> get('elastica.user.service');
	}

	/**
	 * Testing of Add, Get and Delete of a user
	 */
	public function testUserActions() {
		$newUserId = '23huu3-47hkj-n23-48mn69nd9-3s3u54bd-3nfjhvu000ll3858';
		// Deletes user
		$this -> elasticaService -> deleteUser($newUserId);

		// Checks if user exist
		$user = $this -> elasticaService -> getUser($newUserId);
		$this -> assertFalse($user);

		$newUser = new User();
		$newUser -> setId($newUserId) -> setName('John') -> setEmail('john@doe.com') -> setPassword('test');

		$this -> elasticaService -> addUser($newUser);

		// Checks if user exist
		$user = $this -> elasticaService -> getUser($newUserId);
		$this -> assertEquals('John', $user -> getName());
		$this -> assertEquals('john@doe.com', $user -> getEmail());
		$this -> assertEquals(md5('test'), $user -> getPassword());

		// Deletes user
		$this -> elasticaService -> deleteUser($newUserId);

		// Checks if user exist
		$user = $this -> elasticaService -> getUser($newUserId);
		$this -> assertFalse($user);
	}

	/**
	 * Testing get list of user
	 */
	public function testGetUserList() {
		$users = $this -> elasticaService -> getUsers();

		$this -> assertTrue(is_array($users));
	}

	/**
	 * Testing get user by email
	 */
	public function testGetByEmail() {
		$newUserId = '9879fgj4-dsd-fsdf-3s3u54bd-fgh4f878776sf8s7d-4535lou345';
		// Deletes user
		$this -> elasticaService -> deleteUser($newUserId);

		$newUser = new User();
		$newUser -> setId($newUserId) -> setName('John') -> setEmail('john@doe.com') -> setPassword('test');

		$this -> elasticaService -> addUser($newUser);

		// Check if user exists
		$user = $this -> elasticaService -> getByEmail('john@doe.com');
		$this -> assertTrue(!empty($user));

		// Deletes user
		$this -> elasticaService -> deleteUser($newUserId);
	}

}
