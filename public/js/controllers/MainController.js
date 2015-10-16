app.controller('MainController', ['$scope', 'profile', function($scope, profile) {
    profile.success(function(data) {
       // console.log(data);
        $scope.p = data;
    });
}]);
