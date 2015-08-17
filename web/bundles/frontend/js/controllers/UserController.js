
angular.module('UserController', []).controller('UserController', function($scope, $routeParams, User){
	
	//Gets all user
	$scope.getUsers = function (){
		User.getUsers().success(function (data){
			$scope.users = data.users;
		}).error(function (data){
			console.log('Error : ' + data);
		});
	};

	//Gets a single user
	$scope.getUser = function (){
		User.getUser($routeParams.id).success(function (data){
			$scope.user = data.user;
			$scope.user.password = '';
		}).error(function (data){
			console.log('Error : ' + data);
		});
	};

	//Adds new user
	$scope.addUser = function (isValid){
		if(isValid){
			User.addUser($scope.user).success(function (data){
				if(data.error){
					$scope.userexist = true;
				}else{
					window.location.href = '#/';
				}
			}).error(function (data){
				console.log('Error : ' + data);
			});
		}
	};
	
	//Edits a single user
	$scope.editUser = function (isValid){
		if(isValid){
			User.editUser($scope.user).success(function (data){
				if(data.error){
					$scope.userexist = true;
				}else{
					window.location.href = '#/';
				}
			}).error(function (data){
				console.log('Error : ' + data);
			});
		}
	};

	//Deletes a single user
	$scope.deleteUser = function (userID){
		User.deleteUser(userID).success(function (data){
			location.reload();
		}).error(function (data){
			console.log('Error : ' + data);
		});
	};

});