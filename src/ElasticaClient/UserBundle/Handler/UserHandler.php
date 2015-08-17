<?php

namespace ElasticaClient\UserBundle\Handler;

use ElasticaClient\UserBundle\Entity\User;
use ElasticaClient\UserBundle\Form\UserType;
use ElasticaClient\UserBundle\Exception\InvalidFormException;
use ElasticaClient\UserBundle\Exception\UserExistException;
use ElasticaClient\UserBundle\Service\UserServiceInterface;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Handles User Actions
 *
 * @author ahmet akbana
 */
class UserHandler {
	/**
	 * User Service
	 *
	 * @var UserServiceInterface
	 */
	private $userService;

	/**
	 * @var FormFactoryInterface
	 */
	private $formFactory;

	public function __construct(UserServiceInterface $userService, FormFactoryInterface $formFactory) {
		$this -> userService = $userService;
		$this -> formFactory = $formFactory;
	}

	/**
	 * Get a list of Users.
	 *
	 * @return array
	 */
	public function all() {
		return $this -> userService -> getUsers();
	}

	/**
	 * Get a User by id.
	 *
	 * @param mixed $id
	 *
	 * @return User Entity
	 */
	public function get($userId) {
		return $this -> userService -> getUser($userId);
	}

	/**
	 * Get a User by email.
	 *
	 * @param string $email
	 *
	 * @return User Entity
	 */
	private function getByEmail($email) {
		return $this -> userService -> getByEmail($email);
	}

	/**
	 * Create a new User.
	 *
	 * @param array $parameters
	 *
	 * @return User Entity
	 */
	public function post(Array $parameters) {
		$user = new User();
		return $this -> processForm($user, $parameters, 'POST');
	}

	/**
	 * Edit a user.
	 *
	 * @param array $parameters
	 *
	 * @return User Entity
	 */
	public function put($userID, Array $parameters) {
		$user = new User();
		$user -> setId($userID);
		return $this -> processForm($user, $parameters, 'PUT');
	}

	/**
	 * Deletes a single user
	 *
	 * @param string $userID
	 */
	public function delete($userID) {
		$this -> userService -> deleteUser($userID);
	}

	/**
	 * Processes the form.
	 *
	 * @param User $user
	 * @param array         $parameters
	 * @param String        $method
	 *
	 * @return User Entity
	 *
	 * @throws ElasticaClient\UserBundle\Exception\InvalidFormException
	 */
	private function processForm(User $user, $parameters, $method = 'PUT') {
		$form = $this -> formFactory -> create(new UserType(), $user, array('method' => $method));

		$form -> submit($parameters);

		if ($form -> isValid()) {
			$user = $form -> getData();

			if ($userResponse = $this -> getByEmail($user -> getEmail())) {
				if ($method == 'POST') {
					throw new UserExistException('This User Exist', 'user-exist');
				} else {
					if ($user -> getId() == $userResponse -> getId()) {
						$this -> userService -> deleteUser($user -> getId());
						$this -> userService -> addUser($user);
						return $user;
					} else {
						throw new UserExistException('This User Exist', 'user-exist');
					}
				}
			} else {
				$userId = $this -> userService -> addUser($user);
				$user -> setId($userId);
				return $user;
			}
		}
		throw new InvalidFormException('Invalid submitted data', $form);
	}

}
