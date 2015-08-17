
angular.module('UserService', []).factory('User',['$http', function($http){

	return {
		
		//Gets all user
		getUsers : function (){
			return $http.get('api/v1/users.json');
		},

		//Gets a single user
		getUser : function (userID){
			return $http.get('api/v1/users/'+ userID +'.json');
		},

		//Adds new user
		addUser : function (user){
			return $http.post(
				'api/v1/users.json',
				{
					'name': user.name,
					'email': user.email,
					'password': user.password
				}
			);
		},
		
		//Edits a single user
		editUser : function (user){
			return $http.put(
				'api/v1/users/'+user.id+'.json',
				{
					'name': user.name,
					'email': user.email,
					'password': user.password
				}
			);
		},

		//Deletes a single user
		deleteUser : function (userId){
			return $http.delete('api/v1/users/'+userId+'.json');
		}

	}

}])