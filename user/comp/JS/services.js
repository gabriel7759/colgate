angular.module('MyApp.services', [])
    .factory('Rally', function($http, $log, $rootScope){
        return {
            login: function (info, success, error){
                $http({
                    method : "POST",
                    url : $rootScope.mainURL+"php/magic_php.php",
                    data : {what:"user", toDo:"login", email:info.email, password:info.password}
                }).then(function (response) {
                    if(response.data.status=="win"){
                        success();
                    }
                    else{
                        error();
                    }

                }, function () {
                    error();
                });
            }
        }
    })
;
