angular
    .module('Login', ['ngMaterial', 'ngMessages', 'material.svgAssetsCache','MyApp.services'])
    .controller('LoginCtrl',function ($scope, $mdDialog, $mdMedia, $rootScope, $mdToast, $controller, Rally) {
        $rootScope.mainURL="http://bussoly.com/users/";
        $scope.user={
            email: "",
            password: ""
        };
        $scope.doLogin = function (){
            swal({
                title: "Checking info",
                    showConfirmButton: false
            });
            Rally.login($scope.user, function (){
                swal("Good job!", "Welcome!", "success");
                document.cookie = "email="+$scope.user.email;
                document.cookie = "password="+$scope.user.password;
                window.location.href = "startSession.php";
                $("#hide").submit();
            }, function (){
                swal("Something wrong happened", "Check your info or try again later", "error");
            });
        }
    })
    .controller('RegisterCtrl',function ($scope, $mdDialog, $mdMedia, $rootScope, $mdToast, $controller, Rally) {
        $rootScope.mainURL="http://bussoly.com/users/";
        $scope.doRegister = function (){

            swal({
                title: "Checking info",
                showConfirmButton: false
            });
            if( notEmpty($scope.user.name) &&
                notEmpty($scope.user.country) &&
                notEmpty($scope.user.found) &&
                isValidEmailAddress($scope.user.email) &&
                notEmpty($scope.user.password))
            Rally.register($scope.user, function (){
                swal("Good job!", "Now you can login", "success");
                setTimeout(function (){
                    window.location.href = "login.php";

                }, 1000);
            }, function (){
                swal("Cancelled", "Check your info or try again later", "error");
            });
        };
        function notEmpty(content) {
            var result = /[a-z|A-Z|0-9]/.exec(content);
            if (result != null)
                return true;
            else
                return false;
        }
        function isValidEmailAddress(emailAddress) {
            var pattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);
            return pattern.test(emailAddress);
        }
    });
