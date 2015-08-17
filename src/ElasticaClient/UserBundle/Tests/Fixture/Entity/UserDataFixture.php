<?php

namespace ElasticaClient\UserBundle\Tests\Fixture\Entity;

use ElasticaClient\UserBundle\Entity\User;
use ElasticaClient\UserBundle\Service\UserServiceInterface;

/**
 * Loads user data for test
 *
 * @author ahmet akbana
 */
class UserDataFixture {

	/**
	 * @var array $user
	 */
	public $users = array();

	/**
	 * @var UserServiceInterface $userService
	 */
	private $userService;

	/**
	 * @param UserServiceInterface $userService
	 */
	public function __construct(UserServiceInterface $userService) {
		$this -> userService = $userService;
	}

	/**
	 * Adds user data to storage
	 */
	public function addUser() {
		$user = new User();
		$user -> setId('999999999') -> setName('john') -> setEmail('john@doe.com') -> setPassword('test');

		$this -> userService -> addUser($user);

		$this -> users[] = $user;
	}

	/**
	 * Deletes user data from storage
	 */
	public function deleteUser() {
		if (!empty($this -> users)) {
			$user = array_pop($this -> users);
			$this -> userService -> deleteUser($user -> getId());
		}
	}

	/**
	 * Deletes user by email
	 */
	public function deleteUserByEmail($userEmail) {
		$user = $this -> userService -> getByEmail($userEmail);
		if ($user) {
			$this -> userService -> deleteUser($user -> getId());
		}
	}

}
