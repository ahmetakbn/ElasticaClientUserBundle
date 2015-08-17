<?php

namespace ElasticaClient\UserBundle\Service;

use ElasticaClient\UserBundle\Entity\User;

/**
 * Contains user storage actions
 *
 * @author ahmet akbana
 */
interface UserServiceInterface {

	/**
	 * Gets the user list
	 *
	 * @return array of User Entity
	 */
	public function getUsers();

	/**
	 * Gets a single user by id
	 *
	 * @param $userId string
	 * @return Entity User
	 */
	public function getUser($userId);

	/**
	 * Deletes a user by id
	 *
	 * @param $userId string
	 */
	public function deleteUser($userId);

	/**
	 * Adds a new user
	 *
	 * @param $user User Entity
	 * @return string user id
	 */
	public function addUser(User $user);

	/**
	 * Gets user by email
	 *
	 * @param $userEmail string
	 * @return Entity User or boolean false
	 */
	public function getByEmail($userEmail);
}
