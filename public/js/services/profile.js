app.factory('profile', ['$http', function($http) {
    return $http.get('/api/userProfile/'+9)
        .success(function(data) {
            return data;
        })
        .error(function(err) {
            return err;
        });
}]);
