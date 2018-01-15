angular
    .module('Login', ['ngMaterial', 'ngMessages','MyApp.services'])
    .controller('LoginCtrl',function ($scope, $mdDialog, $mdMedia, $rootScope, $mdToast, $controller, Rally) {
        $rootScope.mainURL="http://mkdo.mx/ganamasconcolgate/admin/";

        // $rootScope.mainURL="http://momentos.photos/test/";
        $scope.user={
            email: "",
            password: ""
        };
        $scope.doLogin = function (){
            Rally.login($scope.user, function (){
                document.cookie = "email="+$scope.user.email;
                document.cookie = "password="+$scope.user.password;
                window.location.href = "startSession.php";
                $("#hide").submit();
            }, function (){
            });
        }
    })
;
