<?php

namespace ElasticaClient\UserBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use ElasticaClient\UserBundle\Exception\InvalidFormException;
use ElasticaClient\UserBundle\Exception\UserExistException;
use ElasticaClient\UserBundle\Form\UserType;

/**
 * User Controller
 *
 * @author ahmet akbana
 */
class UserController extends FOSRestController {

	/**
	 * Get all users
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   statusCodes = {
	 *     200 = "Returned when successful"
	 *   }
	 * )
	 *
	 * @Annotations\View()
	 *
	 * @return array
	 *
	 */
	public function getUsersAction() {
		$userHandler = $this -> get('elastica.user.handler');
		$users = $userHandler -> all();

		return array('users' => $users);
	}

	/**
	 * Get single user
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   statusCodes = {
	 *     200 = "Returned when successful",
	 *     404 = "Returned when the user is not found"
	 *   }
	 * )
	 *
	 * @Annotations\View()
	 *
	 * @param $userID
	 * @return array
	 * @throws NotFoundHttpException
	 *
	 */
	public function getUserAction($userID) {
		$user = $this -> getOr404($userID);
		return array("user" => $user);
	}

	/**
	 * New form to create user
	 *
	 * @ApiDoc(
	 * 	 resource = true,
	 *   statusCodes = {
	 * 		200 = "Returned when successful"
	 * 	 }
	 * )
	 *
	 * @Annotations\View()
	 *
	 * @return FormTypeInterFace
	 *
	 */
	public function newUserAction() {
		return $this -> createForm(new UserType());
	}

	/**
	 * Post a single user
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   description = "Creates a new user from the submitted data.",
	 *   input = "ElasticaClient\UserBundle\Form\UserType",
	 *   statusCodes = {
	 *     201 = "Returned when the Page is created in json format",
	 *     200 = "Returned when successful with error user posted by email already exist",
	 *     400 = "Returned when the form has errors"
	 *   }
	 * )
	 *
	 * @Annotations\View()
	 *
	 * @param Request $request
	 * @throws UserExistException, InvalidFormException
	 * @return FormTypeInterFace | View
	 *
	 */
	public function postUserAction(Request $request) {
		try {

			$userHandler = $this -> get('elastica.user.handler');
			$user = $userHandler -> post($request -> request -> all());

			$routeOptions = array('userID' => $user -> getId(), '_format' => $request -> get('_format'));

			return $this -> routeRedirectView('api_v1_get_user', $routeOptions, 201);

		} catch(UserExistException $exception) {
			return array('error' => $exception -> getError());
		} catch(InvalidFormException $exception) {
			return $exception -> getForm();
		}
	}

	/**
	 * Updates a single user by user id
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   description = "Updates a user with the submitted data.",
	 *   input = "ElasticaClient\UserBundle\Form\UserType",
	 *   statusCodes = {
	 *     201 = "Returned when the Page is updated",
	 *     200 = "Returned when successful with error user posted by email already exist",
	 *     400 = "Returned when the form has errors"
	 *   }
	 * )
	 *
	 * @Annotations\View()
	 *
	 * @param string $userID
	 * @param Request $request
	 * @throws UserExistException, InvalidFormException
	 * @return FormTypeInterFace | View
	 *
	 */
	public function putUserAction($userID, Request $request) {
		try {

			$userHandler = $this -> get('elastica.user.handler');
			$user = $userHandler -> put($userID, $request -> request -> all());

			$routeOptions = array('userID' => $user -> getId(), '_format' => $request -> get('_format'));

			return $this -> routeRedirectView('api_v1_get_user', $routeOptions, 201);

		} catch(UserExistException $exception) {
			return array('error' => $exception -> getError());
		} catch(InvalidFormException $exception) {
			return $exception -> getForm();
		}
	}

	/**
	 * Deletes a single user
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   statusCodes = {
	 *     200 = "Returned when successful",
	 *     404 = "Returned when the user is not found"
	 *   }
	 * )
	 *
	 * @param string $userID
	 * @throws HttpNotFoundException
	 * @return array
	 */
	public function deleteUserAction($userID) {
		$user = $this -> getOr404($userID);
		if ($user) {
			$userHandler = $this -> get('elastica.user.handler');
			$userHandler -> delete($userID);

			return array('code' => 200);
		}
	}

	/**
	 * Fetch a User or throw a 404 Exception.
	 *
	 * @param mixed $id
	 * @return User Entity
	 * @throws NotFoundHttpException
	 */
	protected function getOr404($userID) {
		if (!($user = $this -> get('elastica.user.handler') -> get($userID))) {
			throw new NotFoundHttpException(sprintf('The user by id \'%s\' was not found.', $userID));
		}

		return $user;
	}

}
