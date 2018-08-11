
var question1 = angular.module('upperlink_question1', []);

question1.controller('membersController', ['$scope', '$http', function ($scope, $http) {

		//Lets get the method and display the data in JSON
		$http({	
            method: 'get',
            url: 'getData.php'
            })
		  .then(function successCallback(response) {
                $scope.members = response.data;
                
          });


}]);


