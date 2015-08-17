<?php

namespace ElasticaClient\UserBundle\Service;

use ElasticaClient\UserBundle\Entity\User;
use ElasticaClient\UserBundle\Service\UserServiceInterface;

use Elastica\Client;
use Elastica\Query;
use Elastica\Document;
use Elastica\Type\Mapping;

/**
 * Service for connecting to Elastica to get list of users, get a single user, add user or delete user
 * from Elasticsearch data storage
 * 
 * @author ahmet akbana
 */
class ElasticaUserService implements UserServiceInterface
{
	
	/**
	 * User Entity
	 * 
	 * @var Entity user
	 */
	private $user;
	
	
	/**
	 * Elastica client
	 * 
	 * @var string
	 */
	private $clint;
	
	
	/**
	 * index of stored data in elasticsearch
	 * 
	 * @var string
	 */
	private $index;
	
	
	/**
	 * Type of stored data in elasticsearch
	 * 
	 * @var string
	 */
	private $type;
	
	
	/**
	 * Creates a new Client of Elastica with the parameters $host and $port, And receive the index and type of stored data 
	 *
	 * @param $user User Entity, $host string, $port string, $index string, $type string
	 */
	public function __construct(User $user, $host, $port, $index, $type)
	{
		$this->user = $user;
		
		$this->client = new Client(array(
		    'host' => $host,
		    'port' => $port
		));
		
		$this->index = $this->client->getIndex($index);
		
		if(!$this->index->exists()){
			$this->index->create();
			$this->index->refresh();			
		}
		
		$this->type = $this->index->getType($type);
		$mapping = new Mapping();
		$mapping->setType($this->type);
		$mapping->setProperties(array(
			'name' => array('type'=>'string'),
			'email' => array('type'=>'string'),
			'password' => array('type'=>'string'),
		));	
	}
	
	
	/**
	 * Gets the user list
	 * 
	 * @return array of User Entity
	 */
	public function getUsers()
	{
		$query = new Query('');
		
		$elasticaUsers = $this->index->search($query)->getResults();
		
		$users = array();
		
		foreach ($elasticaUsers as $elasticaUser) {
			$user = new User();
			$user->setId($elasticaUser->getId());
			$user->setName($elasticaUser->getData()['name']);
			$user->setEmail($elasticaUser->getData()['email']);
			$user->setPassword($elasticaUser->getData()['password']);
			$users[] = $user;
		}
		return $users;
	}
	
	
	/**
	 * Gets a single user by id
	 * 
	 * @param $userId string
	 * @return Entity User
	 */
	public function getUser($userId)
	{
		$path = $this->index->getName() . '/' . $this->type->getName() . '/' .$userId;

		$response = $this->client->request($path, 'GET', '');
		$elasticaUser = $response->getData();
		
		if(array_key_exists('_source', $elasticaUser)){
			$this->user->setId($userId);
			$this->user->setName($elasticaUser['_source']['name']);
			$this->user->setEmail($elasticaUser['_source']['email']);
			$this->user->setPassword($elasticaUser['_source']['password']);
			return $this->user;
		}else{
			return false;
		}
	}
	
	
	/**
	 * Deletes a user by id
	 * 
	 * @param $userId string
	 */
	public function deleteUser($userId)
	{
		$path = $this->index->getName() . '/' . $this->type->getName() . '/' .$userId;		
		$response = $this->client->request($path, 'DELETE', '');				
		$this->type->getIndex()->refresh();
	}
	
	
	/**
	 * Adds a new user
	 * 
	 * @param $user User Entity
	 * @return string user id
	 */
	public function addUser(User $user)
	{
		$newUser = array(
			'name' => $user->getName(),
			'email' => $user->getEmail(),
			'password' => $user->getHashPassword()
		);
		
		$userDocument = new Document($user->getId(), $newUser);
		$response = $this->type->addDocument($userDocument)->getData();
		$this->type->getIndex()->refresh();
		
		return $response['_id'];
	}
	
	
	/**
	 * Gets user by email
	 * 
	 * @param $userEmail string
	 * @return User Entity or boolean false
	 */
	public function getByEmail($userEmail)
	{
		/* 
		 * While searching an email with elasticsearch it brings the result if any part of email matches.
		 * Because of this, to split the email and search both part to know if both of them matches
		 */
		$emailParts = explode('@', $userEmail);
		
		$query1 = new Query(array(
		    'query' => array(
		        'bool' => array(
					'must' => array(
						'match' => array(
							'email' => $emailParts[0]
						)
					)
				)
		    )
		));
		$result1 = $this->index->search($query1)->getResults();
		
		$query2 = new Query(array(
		    'query' => array(
		        'bool' => array(
					'must' => array(
						'match' => array(
							'email' => $emailParts[1]
						)
					)
				)
		    )
		));
		$result2 = $this->index->search($query2)->getResults();
		
		if(!empty($result1) && !empty($result2)){
			$this->user->setId($result1[0]->getId());
			$this->user->setName($result1[0]->getData()['name']);
			$this->user->setEmail($result1[0]->getData()['email']);
			$this->user->setPassword($result1[0]->getData()['password']);
			return $this->user;
		}else{
			return false;
		}
	}
	
	
	
}
