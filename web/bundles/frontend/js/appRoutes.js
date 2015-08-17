
angular.module('appRoutes', []).config(['$routeProvider', '$locationProvider', function ($routeProvider, $locationProvider){

	$routeProvider

		.when('/',{

			templateUrl : '../bundles/frontend/view/users.html',
			controller  : 'UserController'

		})

		.when('/user/add',{

			templateUrl : '../bundles/frontend/view/user_add.html',
			controller  : 'UserController'

		})

		.when('/user/show/:id',{

			templateUrl : '../bundles/frontend/view/user_show.html',
			controller  : 'UserController'

		})

		.when('/user/edit/:id',{

			templateUrl : '../bundles/frontend/view/user_edit.html',
			controller  : 'UserController'

		})
		
		.otherwise({
			redirectTo : '/'
		});

	//$locationProvider.html5Mode(true);

}]);