angular
    .module('MyApp', ['ngMaterial', 'ngMessages', 'material.svgAssetsCache','MyApp.services'])
    .controller('AppCtrl', function ($scope, $rootScope, $timeout, $mdSidenav, $log, $mdToast, $http, Labels, Rally, SideNav, DashboardService) {
        $rootScope.HOMEPAGE = 1;
        $rootScope.CREATE_RALLY = 2;
        $rootScope.TRASH = 3;
        $rootScope.CHATS = 4;
        $rootScope.PURCHASES = 5;
        $rootScope.CREATE_NEW_RALLY = 6;
        $rootScope.START_RALLY = 7;
        $rootScope.SELECT_RALLY_TO_START = 8;
        $rootScope.SELECT_RALLY = 9;
        $rootScope.SELECT_RALLY_TO_EDIT = 10;
        $rootScope.EDIT_RALLY = 11;
        $rootScope.DASHBOARD = 12;
        $rootScope.SELECT_RALLY_TO_DASHBOARD = 13;
        $rootScope.SELECT_RALLY_TO_CHAT = 14;
        $rootScope.SELECT_RALLY_TO_BUY = 14;
        $rootScope.LOGOUT = 15;
        $scope.dashboardOn=false;
        $rootScope.canDelete=false;
        Rally.getAll( function (response){
            if(response!="empty\n")
                $rootScope.rallys=response;
        }, function (){
        });
        $rootScope.template = "Templates/initialFrame.html";
        $scope.title = "Homepage Rally";
        $rootScope.editingRally = false;
        $scope.sideNavButtons =SideNav.getAll() ;
        Labels.all(function (data){$scope.labels=data}, function () {});
        $rootScope.rallySelected = 0;
        $scope.toggleLeft = buildDelayedToggler('left');
        $scope.toggleRight = buildToggler('right');
        $rootScope.afterSelect = 0;
        $rootScope.needToFillUp = false;
        $scope.zones = {
            lastId: 0,
            zone: []
        };
        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i = 0; i <ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length,c.length);
                }
            }
            return "";
        }
        $rootScope.company=getCookie("company");
        $scope.isOpenRight = function () {
            return $mdSidenav('right').isOpen();
        };
        $scope.changeFrame = function (item, e) {
            $mdSidenav('left').close()
                .then(function () {
                });
            $(".sideNav-button").removeClass("active");
            $(e.currentTarget).addClass("active");
            performChange(item.action);
        };
        this.chageFrameC =function (frame){
            $rootScope.template=frame;
        };
        $scope.reloadDashboard= function (){
            $rootScope.template = "Templates/initialFrame.html";
            setTimeout(function (){
                $rootScope.template="Templates/dashboard.html";
                $scope.dashboardOn=true;
            }, 1);

        };
        $scope.externalChange = function (fragment) {
                swal({  title: $scope.labels.rallyName,
                        type: "input",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        animation: "slide-from-top",
                        inputPlaceholder: $scope.labels.rallyName },
                        function(inputValue){
                            if (inputValue === false)
                                return false;
                            if (inputValue === "") {
                                swal.showInputError($scope.labels.emptyInput);
                                return false
                            }
                            swal({
                                    title: $scope.labels.createRally,
                                    text: $scope.labels.createRallyQuestion,
                                    type: "info",
                                    showCancelButton: true,
                                    closeOnConfirm: false,
                                    showLoaderOnConfirm: true },
                                function() {
                                    Rally.createRally(inputValue, function (data) {
                                        $rootScope.rallySelected=data;
                                        $rootScope.needToFillUp=true;
                                        swal({
                                                title: $scope.labels.fillUpRally,
                                                type: "success",
                                                showCancelButton: false,
                                                confirmButtonText: "Ok"
                                            },
                                            function () {
                                                performChange(fragment);
                                                eventFire(document.getElementById('dashboard'), 'focus');
                                            });

                                    }, function () {
                                    });
                                });
                        });
        };
        $scope.changeRallySelected = function (item) {
            $rootScope.rallySelected = item.id;
        };
        $scope.generatePassword = function (rally){
            Rally.generatePassword(rally.id, function (){

            }, function (){

            })
        };
        $scope.deleteRally = function (item){
            Rally.deleteRally(item.id, function (){
                Rally.getAll( function (response){
                    if(response!="empty\n")
                        $rootScope.rallys=response;
                }, function (){
                });
            }, function (){

            })
        };
        $scope.selectRally = function () {
            performChange($rootScope.afterSelect);
        };
        function performChange(fragment) {
            $rootScope.template = "";
            $rootScope.editingRally = false;
            $rootScope.canDelete=false;
            $scope.dashboardOn=false;
            switch (fragment) {
                case $rootScope.CREATE_RALLY:
                    $rootScope.template = "Templates/createRally.html";
                    break;
                case $rootScope.CREATE_NEW_RALLY:
                    $rootScope.template = "Templates/createNewRally.html";
                    break;
                case $rootScope.START_RALLY:
                    $rootScope.template = "Templates/startRally.html";
                    var rally;
                    Rally.getRally($rootScope.rallySelected, function(data){
                        $scope.labels.code=data.password;
                    }, function (){

                    });
                    break;
                case $rootScope.HOMEPAGE:
                    $rootScope.template = "Templates/initialFrame.html";
                    break;
                case $rootScope.SELECT_RALLY_TO_START:
                    caseSelectRally("Templates/selectRallyToStart.html", $rootScope.START_RALLY);
                    break;
                case $rootScope.SELECT_RALLY_TO_EDIT:
                    caseSelectRally("Templates/selectRally.html", $rootScope.EDIT_RALLY);
                    $rootScope.canDelete=true;
                    break;
                case $rootScope.EDIT_RALLY:
                    $rootScope.template = "Templates/createNewRally.html";
                    $rootScope.needToFillUp = true;
                    $rootScope.editingRally = true;
                    break;
                case $rootScope.SELECT_RALLY_TO_DASHBOARD:
                    caseSelectRally("Templates/selectRallyToDashboard.html", $rootScope.DASHBOARD);

                    //$rootScope.template="Templates/dashboard.html";
                    break;
                case $rootScope.SELECT_RALLY_TO_CHAT:
                    caseSelectRally("Templates/selectRally.html", $rootScope.CHATS);
                    break;
                case $rootScope.SELECT_RALLY_TO_BUY:
                    caseSelectRally("Templates/selectRally.html", $rootScope.PURCHASES);
                    break;
                case $rootScope.DASHBOARD:
                    $rootScope.template="Templates/dashboard.html";
                    $scope.dashboardOn=true;
                    break;
                case $rootScope.LOGOUT:
                    location.href="logout.php";
                    break;
            }
        }
        function caseSelectRally(template, afterSelect) {
            if ($rootScope.rallys.rally[0] != null) {
                $rootScope.template = template;
                $rootScope.afterSelect = afterSelect;
            }
            else
                createRallySwal();
        }
        function createRallySwal() {
            swal({
                    title: "Error",
                    text: "You need to create a rally first.",
                    type: "error",
                    showCancelButton: true,
                    confirmButtonText: "Lets do it!",
                    closeOnConfirm: true
                }, function (isConfirm) {
                    $(".sideNav-button").removeClass("active");
                    $("#createRally").addClass("active");
                    performChange($rootScope.CREATE_RALLY);
                }
            );
        }
        function debounce(func, wait, context) {
            var timer;
            return function debounced() {
                var context = $scope,
                    args = Array.prototype.slice.call(arguments);
                $timeout.cancel(timer);
                timer = $timeout(function () {
                    timer = undefined;
                    func.apply(context, args);
                }, wait || 10);
            };
        }
        function buildDelayedToggler(navID) {
            return debounce(function () {
                // Component lookup should always be available since we are not using `ng-if`
                $mdSidenav(navID)
                    .toggle()
                    .then(function () {
                        $log.debug("toggle " + navID + " is done");
                    });
            }, 200);
        }
        function buildToggler(navID) {
            return function () {
                // Component lookup should always be available since we are not using `ng-if`
                $mdSidenav(navID)
                    .toggle()
                    .then(function () {
                        $log.debug("toggle " + navID + " is done");
                    });
            }
        }
        $rootScope.endDate = '';
        $scope.startRally = function () {
            var date=document.getElementById("min-date").value;
            if (date != "") {
                swal({
                    title: $scope.labels.startRally,
                    text: $scope.labels.startRallyQuestion,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: $scope.labels.letsGo,
                    closeOnConfirm: true
                }, function () {
                    try {
                        Rally.startRally(date,function (){
                            $rootScope.afterSelect = $rootScope.DASHBOARD;
                            $("#dashboard").click();
                            $(".sideNav-button").removeClass("active");
                            $.each($rootScope.rallys.rally, function (index, value) {
                                if (value.id == $rootScope.rallySelected) {
                                    $rootScope.rallys.rally[index].started = true;
                                    $rootScope.rallys.rally[index].endDate = date;
                                }
                            });
                        }, function (){

                        });

                    }
                    catch (e){
                        alert(e);
                    }
                });
            }
        };
        function eventFire(el, etype){
            if (el.fireEvent) {
                el.fireEvent('on' + etype);
            } else {
                var evObj = document.createEvent('Events');
                evObj.initEvent(etype, true, false);
                el.dispatchEvent(evObj);
            }
        }
    })
    .controller('LeftCtrl', function ($scope, $timeout, $mdSidenav, $log) {
        $scope.close = function () {
            // Component lookup should always be available since we are not using `ng-if`
            $mdSidenav('left').close()
                .then(function () {
                    $log.debug("close LEFT is done");
                });

        };
    })
    .controller('StartRally',function ($scope, $mdDialog, $mdMedia, $rootScope, $mdToast, $controller) {

        $rootScope.endDate = '';
        $scope.startRally = function () {
            var date=document.getElementById("min-date").value;
            if (date != "") {
                swal({
                    title: $scope.labels.startRally,
                    text: $scope.labels.startRallyQuestion,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: $scope.labels.letsGo,
                    closeOnConfirm: true
                }, function () {
                    $("#dashboard").click();
                    $("#dashboard").click();
                    $("#dashboard").click();
                    $("#dashboard").click();
                    $("#dashboard").click();
                    $("#dashboard").click();
                    $("#dashboard").click();
                    var c =$controller("AppCtrl");
                    c.chageFrameC("Templates/selectRallyToDashboard.html");
                    $rootScope.afterSelect=$rootScope.DASHBOARD;
                    $("#dashboard").click();
                    $(".sideNav-button").removeClass("active");
                    $.each($rootScope.rallys.rally, function (index, value){
                        if(value.id==$rootScope.rallySelected){
                            $rootScope.rallys.rally[index].started=true;
                            $rootScope.rallys.rally[index].endDate=date;
                        }
                    });
                });
            }
        };
    })
    .controller('CreateRally', function ($scope, $mdDialog, $mdMedia, $rootScope, $mdToast, Rally) {
        $scope.selectedIndex = 0;
        $scope.challengeType = Rally.getChallengesType();
        $scope.teamsCansplit = '';
        $scope.multipleOptionAnswers = Rally.getMultipleOptionAnswers();
        $scope.geographicAnswer = Rally.getGeographicAnswer();
        $scope.rallyInfo = Rally.getRallyInfo();
        $scope.answerType = "";
        $scope.challenges = Rally.getChallenges();
        $scope.challengeOptions = Rally.getChallengeOptions();
        $scope.challengeZone = 'Select';
        $scope.challengeDescription='';
        Rally.getZones(function (data){
            $scope.zones=data;
        }, function (err){
            console.log(err);
        });
        $scope.challengeToView = 0;
        $scope.editing = false;
        $scope.readyToStart = false;
        function fillUpRally() {
            if ($rootScope.needToFillUp) {
                var id = $rootScope.rallySelected;
                updateRally();
            }
            else{
                setTimeout(resetAll, 500);
            }
        }
        function updateRally(){
            var rally = '';
            Rally.getRally($rootScope.rallySelected, function(data){
                rally = data;
                $scope.rallyInfo.rallyName = rally.name;
                $scope.rallyInfo.numberOfTeams = rally.numberOfTeams / 1;
                $scope.rallyInfo.companyName = rally.companyName;
                $scope.rallyInfo.description = rally.description;
                $scope.teamsCanSplit = rally.teamsCanSplit;
                $scope.zones = rally.zones;
                $scope.challenges = rally.challenges;
                $.each ($scope.challenges.challenge, function (index, value ){
                    try{
                        var res = value.answer.replace(/&quot;/g, '"');
                        $scope.challenges.challenge[index].answer=res;
                    }
                    catch(e){
                        console.log(e);
                    }
                });
                $rootScope.needToFillUp = false;
            }, function (){

            });
        }
        $scope.closeDialog = function () {
            $mdDialog.hide();
        };
        $scope.addChallenge = function (e) {
            var challengeName = document.getElementById('challengeName').value;
            var type = $scope.answerType;
            var points = document.getElementById('challengePoints').value;
            var zone = $scope.challengeZone;
            var description = $scope.challengeDescription;
            var answer = "";
            if ($scope.challengeType.photo == $scope.answerType)
                answer = document.getElementById("answer_photo").value;
            else if ($scope.challengeType.geographic == $scope.answerType)
                answer = $scope.geographicAnswer;
            else if ($scope.challengeType.multipleOption == $scope.answerType)
                answer = $scope.multipleOptionAnswers;
            else if ($scope.challengeType.openAnswer == $scope.answerType)
                answer = document.getElementById("answer_open").value;
            if (notEmpty(challengeName) &&
                notEmpty(type) &&
                notEmpty(zone) && zone != 0 &&
                notEmpty(description) &&
                answer != null &&
                notEmpty(points)) {
     /*           Rally.setNewChallenge({
                    name: challengeName,
                    type: type,
                    points: points,
                    zone: zone,
                    answer: answer,
                    id: Rally.getLastId_challenge() + 1
                });
                Rally.setNewChallengeToZone();
                $scope.challenges = Rally.getChallenges();
                resetChallengeInput();*/
                Rally.setNewChallenge(challengeName, type, points, zone, answer, description,  function(){
                    updateRally();
                    saveRally();

                    // Rally.setNewChallengeToZone();
                    // $scope.challenges = Rally.getChallenges();
                     resetChallengeInput();
                }, function (){

                });

            }
        };
        $scope.showChangeZoneName = function (ev, item) {
            // Appending dialog to document.body to cover sidenav in docs app
            var confirm = $mdDialog.prompt()
                .title($scope.labels.editZoneHeader)
                .textContent($scope.labels.zoneName)
                .placeholder($scope.labels.zoneName)
                .initialValue(item.name)
                .targetEvent(ev)
                .ok('Okay!')
                .cancel('Cancel');

            $mdDialog.show(confirm).then(function (result) {
                Rally.changeName_zone(item.id, result, function (){
                    Rally.getZones(function (data){
                        $scope.zones=data;
                    }, function (err){
                        console.log(err);
                    });
                }, function (){

                });
            }, function () {
            });
        };
        $scope.showDeleteZone = function (ev, item) {
            var confirm = $mdDialog.confirm()
                .title($scope.labels.deleteZone)
                .ok('Okay!')
                .cancel('Cancel');

            $mdDialog.show(confirm).then(function () {
                Rally.deleteZone(item.id, function () {
                    Rally.getZones(function (data){
                        $scope.zones=data;
                    }, function (err){
                        console.log(err);
                    });
                }, function () {

                });
            }, function () {
            });
        };
        $scope.deleteChallenge = function (ev, item) {
            Rally.deleteChallenge(item.id, function (){
                resetChallengeInput();
                updateRally();
            }, function (){

            });
        };
        $scope.addZoneChip = function () {
            saveRally();
            var zones = document.getElementById("addZone-input");
            var zone = zones.value;
            if (checkZone(zone))
                // Rally.addZone({name: zone, challenges: 0/*, id: Rally.getLastId_zones() + 1*/});
                Rally.addZone(zone, function (){
                    Rally.getZones(function (data){
                        $scope.zones=data;
                    }, function (){

                    });
                }, function (){

                });

            zones.value = "";
        };
        $scope.openAddAnswer = function (ev) {
            var type = "";
            if ($scope.challengeType.photo == $scope.answerType)
                type = "photo";
            else if ($scope.challengeType.geographic == $scope.answerType)
                type = "geographic";
            else if ($scope.challengeType.multipleOption == $scope.answerType)
                type = "multipleOption";
            else if ($scope.challengeType.openAnswer == $scope.answerType)
                type = 'openAnswer';
            if (type != "") {
                $mdDialog.show({
                    controller: DialogController,
                    contentElement: '#addAnswer_' + type,
                    parent: angular.element(document.body),
                    targetEvent: ev,
                    clickOutsideToClose: true
                });
            }
        };
        $scope.submitGeographicAnswer = function (e) {
            var fromLongitude = document.getElementById("fromLongitude");
            var toLongitude = document.getElementById("toLongitude");
            var fromLatitude = document.getElementById("fromLatitude");
            var toLatitude = document.getElementById("toLatitude");
            var geographicCaption = document.getElementById("geographicCaption");
            if (notEmpty(fromLatitude.value) ||
                notEmpty(toLatitude.value) ||
                notEmpty(fromLongitude.value) ||
                notEmpty(toLongitude.value)) {
                Rally.setGeographicAnswer({
                    fromLatitude: fromLatitude.value,
                    toLatitude: toLatitude.value,
                    fromLongitude: fromLongitude.value,
                    toLongitude: toLongitude.value,
                    caption: geographicCaption.value
                });
                fromLatitude.value = "";
                fromLongitude.value = "";
                fromLongitude.value = "";
                toLongitude.value = "";
                geographicCaption.value = "";
                $mdDialog.hide();
                $scope.geographicAnswer = Rally.getGeographicAnswer();
            }
        };
        $scope.deleteAnswer = function (e, item){
            Rally.deleteMultipleOptionAnswer(item.id, function (){
                console.log($scope.multipleOptionAnswers);
                $scope.multipleOptionAnswers= Rally.getMultipleOptionAnswers();
                console.log($scope.multipleOptionAnswers);

            }, function(){

            });
        };
        $scope.showDeleteChallenge = function (e, item) {
            var confirm = $mdDialog.confirm()
                .title($scope.labels.deleteChallenge)
                .ok('Okay!')
                .cancel('Cancel');
            $mdDialog.show(confirm).then(function () {
                Rally.deleteChallenge(item.id, function () {
                    updateRally();
                }, function (){

                });
            }, function () {
            });
        };
        $scope.showChallengeInfo = function (ev, item) {
            $scope.challengeToView = item.id;
            $mdDialog.show({
                controller: DialogController,
                contentElement: '#myDialog',
                parent: angular.element(document.body),
                targetEvent: ev,
                clickOutsideToClose: true
            });
        };
        $scope.getChallengeName = function () {
            var name = "";
            $.each($scope.challenges.challenge, function (index, value) {
                if ($scope.challengeToView == value.id) {
                    name = value.name;
                }
            });
            return name;
        };
        $scope.getChallengePoints = function () {
            var points = '';
            $.each($scope.challenges.challenge, function (index, value) {
                if ($scope.challengeToView == value.id) {
                    points = value.points;
                }
            });
            return points;
        };
        $scope.getChallengeZone = function () {
            var zone = '';
            var zoneName = '';
            $.each($scope.challenges.challenge, function (index, value) {
                if ($scope.challengeToView == value.id) {
                    zone = value.zone;
                }
            });
            $.each($scope.zones.zone, function (index, value) {
                if (zone == value.id) {
                    zoneName = value.name;
                }
            });
            return zoneName;
        };
        $scope.getChallengeType = function () {
            var type = '';
            $.each($scope.challenges.challenge, function (index, value) {
                if ($scope.challengeToView == value.id) {
                    type = value.type;
                }
            });
            if (type == $scope.challengeType.geographic)
                type = "Geographic";
            else if (type == $scope.challengeType.photo)
                type = "Photo";
            else if (type == $scope.challengeType.multipleOption)
                type = "Multiple option";
            else if (type == $scope.challengeType.openAnswer)
                type = "Open answer";
            return type;

        };
        $scope.getChallengeAnswer = function () {
            $.each($scope.challenges.challenge, function (index, value) {
                if ($scope.challengeToView == value.id) {
                    return value.answer;
                }
            });
        };
        $scope.editChallengeInfo = function (e, item) {
            $mdToast.show(
                $mdToast.simple()
                    .textContent($scope.labels.editing)
                    .position('top right')
                    .hideDelay(3000)
            );
            if (item != null)
                $scope.challengeToView = item.id;
            resetChallengeInput();
            setChallengeInfo();
            $scope.editing = true;
        };
        $scope.completeEditing = function () {
            var challengeName = document.getElementById('challengeName').value;
            var type = $scope.answerType;
            var points = document.getElementById('challengePoints').value;
            var zone = $scope.challengeZone;
            var answer = "";
            var previousZone = '';
            var description = $scope.challengeDescription;
            if ($scope.challengeType.photo == $scope.answerType)
                answer = document.getElementById("answer_photo").value;
            else if ($scope.challengeType.geographic == $scope.answerType){
                answer = $scope.geographicAnswer;
            }
            else if ($scope.challengeType.multipleOption == $scope.answerType)
                answer = $scope.multipleOptionAnswers;
            else if ($scope.challengeType.openAnswer == $scope.answerType)
                answer = document.getElementById("answer_open").value;
            if (notEmpty(challengeName) &&
                notEmpty(type) &&
                notEmpty(zone) &&
                notEmpty(description) &&
                answer != null &&
                notEmpty(points)) {
                previousZone = Rally.editChallenge($scope.challengeToView, challengeName, type, points, zone, answer, description,  function (){
                    updateRally();
                    resetChallengeInput();
                }, function (){

                });
                resetChallengeInput();
                $scope.editing = false;
                if (previousZone != zone) {
                    Rally.changeChallenges_perZone(zone, previousZone);
                }
            }
        };
        $scope.someTestFunction = function (someText) {
            return someText;
        };
        $scope.submitAnswer = function (ev) {
            $mdDialog.hide();
            var type = "";
            if ($scope.challengeType.photo == $scope.answerType)
                type = "photo";
            else if ($scope.challengeType.geographic == $scope.answerType)
                type = "geographic";
            else if ($scope.challengeType.multipleOption == $scope.answerType)
                type = "multipleOption";
            else if ($scope.challengeType.openAnswer == $scope.answerType)
                type = 'openAnswer';
            if (type != "") {

            }
        };
        $scope.submitMultipleOptionAnswer = function (ev) {
            var answer = document.getElementById("multipleOptionAnswer-input");
            if (notEmpty(answer.value)) {
                Rally.addMultipleOptionAnswer({
                    description: answer.value,
                    correct: false,
                    id: Rally.getLastId_multipleOptionAnswers() + 1
                }, function (){
                    $scope.multipleOptionAnswers=Rally.getMultipleOptionAnswers();
                }, function (){

                });
                answer.value = null;
            }
        };
        $scope.nextTab = function () {
            saveRally();
            if ($scope.selectedIndex == 2) {
                $scope.selectedIndex++;
                $scope.readyToStart = true;
            }
            else {
                $scope.readyToStart = false;
                $scope.selectedIndex++;
            }

        };
        $scope.saveRally = function () {
            var name = document.getElementById("rallyName");
            var numberOfTeams = document.getElementById("numberOfTeams");
            var companyName = document.getElementById("companyName");
            var description = document.getElementById("description");
            if (notEmpty(name.value) &&
                notEmpty(numberOfTeams.value) &&
                notEmpty(companyName.value) &&
                notEmpty(description.value) &&
                notEmpty($scope.zones.zone[0].name) &&
                notEmpty($scope.challenges.challenge[0].name)) {
                Rally.addRally({
                    // id: Rally.getLastId_rally() + 1,
                    name: name.value,
                    teamsCanSplit: $scope.teamsCanSplit,
                    numberOfTeams: numberOfTeams.value,
                    companyName: name.value,
                    description: description.value,
                    // challenges: $scope.challenges,
                    // zones: $scope.zones,
                    //endDate: "",
                    started: false
                });
                $scope.selectedIndex = 0;
                resetAll();
                //showSuccessAlert($scope.labels.success, $scope.labels.rallySaved);
            }
        };
        $scope.editRally = function () {
            var name = document.getElementById("rallyName");
            var numberOfTeams = document.getElementById("numberOfTeams");
            var companyName = document.getElementById("companyName");
            var description = document.getElementById("description");
            if (notEmpty(name.value) &&
                notEmpty(numberOfTeams.value) &&
                notEmpty(companyName.value) &&
                notEmpty(description.value) &&
                notEmpty($scope.zones.zone[0].name) &&
                notEmpty($scope.challenges.challenge[0].name)) {
                Rally.editRally($rootScope.rallySelected, {
                    name: name.value,
                    teamsCanSplit: $scope.teamsCanSplit,
                    numberOfTeams: numberOfTeams.value,
                    companyName: name.value,
                    description: description.value,
                    challenges: $scope.challenges,
                    zones: $scope.zones
                });
                $scope.selectedIndex = 0;
                $rootScope.rallySelected = 0;
                resetAll();
                showSuccessAlert($scope.labels.success, $scope.labels.rallySaved);
            }
        };
        function saveRally(){
            $mdToast.show(
                $mdToast.simple()
                    .textContent($scope.labels.rallySaved)
                    .position('top right')
                    .hideDelay(3000)
            );
            var name = document.getElementById("rallyName");
            var numberOfTeams = document.getElementById("numberOfTeams");
            var companyName = document.getElementById("companyName");
            var description = document.getElementById("description");
            if (notEmpty(name.value) &&
                notEmpty(numberOfTeams.value) &&
                notEmpty(companyName.value) &&
                notEmpty(description.value)) {
                Rally.saveRally(name.value, $scope.teamsCanSplit, numberOfTeams.value, companyName.value, description.value, function (){
                    //showSuccessAlert($scope.labels.success, $scope.labels.rallySaved);
                    Rally.getAll( function (response){
                        if(response!="empty\n")
                            $rootScope.rallys=response;
                    }, function (){
                    });
                }, function(){});

            }
        }
        function setChallengeInfo() {
            var challengeId = $scope.challengeToView;
            var challenge = "";
            $.each($scope.challenges.challenge, function (index, value) {
                if ($scope.challengeToView == value.id)
                    challenge = value;
            });
            document.getElementById('challengeName').value = challenge.name;
            $scope.answerType = challenge.type;
            document.getElementById('challengePoints').value = challenge.points;
            $scope.challengeZone = challenge.zone;
            if ($scope.challengeType.photo == challenge.type)
                document.getElementById("answer_photo").value = challenge.answer;
            else if ($scope.challengeType.geographic == challenge.type) {
                document.getElementById("fromLongitude").value = challenge.answer.fromLongitude;
                document.getElementById("toLongitude").value = challenge.answer.toLongitude;
                document.getElementById("fromLatitude").value = challenge.answer.fromLatitude;
                document.getElementById("toLatitude").value = challenge.answer.toLatitude;
                document.getElementById("geographicCaption").value = challenge.answer.caption;
            }
            else if ($scope.challengeType.multipleOption == challenge.type){
                Rally.setMultipleAnswers(challenge.answer);
                $scope.multipleOptionAnswers=Rally.getMultipleOptionAnswers();
            }
            else if ($scope.challengeType.openAnswer == challenge.type)
                document.getElementById("answer_photo").value = challenge.answer;
            $scope.challengeDescription=challenge.description;

        }
        function checkZone(zone) {
            var result = /[a-z|A-Z|0-9]/.exec(zone);
            if (result != null && zone!=0)
                return true;
            else
                return false;
        }
            function notEmpty(content) {
                var result = /[a-z|A-Z|0-9]/.exec(content);
                if (result != null)
                    return true;
                else
                    return false;
            }
        function DialogController($scope, $mdDialog) {
            $scope.hide = function () {
                $mdDialog.hide();
            };

            $scope.cancel = function () {
                $mdDialog.cancel();
            };

            $scope.answer = function (answer) {
                $mdDialog.hide(answer);
            };
        }
        function resetChallengeInput() {
            document.getElementById('challengeName').value = '';
            $scope.answerType = 0;
            document.getElementById('challengePoints').value = '';
            document.getElementById("answer_open").value ='';
            $scope.challengeZone = 0;
            $scope.challengeDescription='';
            document.getElementById("answer_photo").value = '';
            document.getElementById("answer_photo").value = '';
            Rally.resetMultipleOption();
            Rally.resetMultipleOption();
            $scope.multipleOptionAnswers = Rally.getMultipleOptionAnswers();
        }
        function resetZones() {
            document.getElementById("addZone-input").value = '';


        }
        function resetInfo() {
            document.getElementById("rallyName").value = '';
            document.getElementById("numberOfTeams").value = '';
            document.getElementById("companyName").value = '';
            document.getElementById("description").value = '';
            $scope.teamsCanSplit = false;
        }
        function resetAll() {
            resetZones();
            resetChallengeInput();
            resetInfo();
            Rally.resetAll();

        }
        function showSuccessAlert(title, message) {
            swal({
                title: title,
                text: message,
                type: "success",
                timer: 2000,
                showConfirmButton: false
            });
        }
        fillUpRally();
    })
    .controller('SelectRally', function ($scope, $mdDialog, $mdMedia, $rootScope, $mdToast) {
        $scope.setCountdown = function (rally) {
            var date = new Date(rally.endDate);
            var now = new Date();
            var diff = (date.getTime() / 1000) - (now.getTime() / 1000);
            if(diff>=0){
                var clock = $('.clock'+rally.id).FlipClock(diff, {
                    clockFace: 'HourlyCounter',
                    countdown: true
                });
                clock.start();
            }
        };
    })
    .controller('Dashboard', function ($scope, $mdDialog, $mdMedia, $rootScope, $mdToast, DashboardService){
        $rootScope.done=false;
        var rendered=0;
        var doneColors=false;
        var backgrouds=[];
        $scope.message={
            title:"",
            body:"",
            points:0
        };
        $rootScope.dashboardInfo="";
        DashboardService.getDashboard($rootScope.rallySelected, function (data){
            $rootScope.dashboardInfo=data;
            var date;
            date = new Date(data.endDate);
            var now = new Date();
            var diff = (date.getTime() / 1000) - (now.getTime() / 1000);
            if(diff>=0){
                var clock = $('.clock').FlipClock(diff, {
                    clockFace: 'HourlyCounter',
                    countdown: true
                });
                clock.start();
            }
            else {
                var clock = $('.clock').FlipClock(0, {
                    clockFace: 'HourlyCounter',
                    countdown: true
                });
                clock.start();
            }
        }, function (){

        });
        $scope.buildCanvas_zones = function (id, team, maxPoints){
            if(rendered>=5)
                $rootScope.done=true;

            if(!$rootScope.done){

                var data=[];

                var labels=[];
                var maxValue=0;
                var background;
                var done=false;
                var canvas=document.getElementById(id);
                $.each($scope.dashboardInfo.topTeams, function(index, value){
                    if(value.id == team && !done){
                        $.each(value.zones, function (a, b){
                            data.push(b.points);
                            labels.push(b.zone_description);
                            if(!doneColors){
                                background='#' + (function co(lor){   return (lor +=
                                        [0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f'][Math.floor(Math.random()*16)])
                                    && (lor.length == 6) ?  lor : co(lor); })('');
                                backgrouds.push(background);
                            }


                            done=true;

                        });
                    }
                });
                doneColors=true;
                var zonesProgress_chart = new Chart(canvas, {
                    data: {
                        datasets: [{
                            data:data,
                            backgroundColor:backgrouds,
                            label: 'Zones' // for legend
                        }],
                        labels: labels
                    },

                    type: 'bar',
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    max: 150,
                                    min: 0,
                                    stepSize: 20
                                }
                            }]
                        }
                    }
                });
            }

            rendered++;
        };
        $scope.disableTeam = function (ev, id){

        };
        $scope.setPunishment = function (ev){
            var points=document.getElementById("points");
            var p=points.value;
            if(notEmpty($scope.message.title) && notEmpty($scope.message.body) && notEmpty(p) ){

                points.value=0;
                $mdDialog.hide();
                DashboardService.setPunishment_service(p, $scope.teamToSendMessage, $scope.message.title, $scope.message.body, function (){
                    $scope.message={
                        title:"",
                        body:"",
                        points:0
                    };
                    $mdToast.show(
                        $mdToast.simple()
                            .textContent("Your message has been sent")
                            .position('top right')
                            .hideDelay(3000)
                    );
                }, function (){
                    $mdToast.show(
                        $mdToast.simple()
                            .textContent("Check your internet connection and try again later")
                            .position('top right')
                            .hideDelay(3000)
                    );
                });
            }
        };
        $scope.setExtraPoints = function (ev){
            var points=document.getElementById("points");
            var p=points.value;
            if(notEmpty($scope.message.title) && notEmpty($scope.message.body) && notEmpty(p) ){

                points.value=0;
                $mdDialog.hide();
                DashboardService.setExtraPoints_service(p, $scope.teamToSendMessage, $scope.message.title, $scope.message.body, function (){
                    $scope.message={
                        title:"",
                        body:"",
                        points:0
                    };
                    $mdToast.show(
                        $mdToast.simple()
                            .textContent("Your message has been sent")
                            .position('top right')
                            .hideDelay(3000)
                    );
                }, function (){
                    $mdToast.show(
                        $mdToast.simple()
                            .textContent("Check your internet connection and try again later")
                            .position('top right')
                            .hideDelay(3000)
                    );
                });
            }
        };
        $scope.buildMessage = function (ev, id, add){
            $scope.extraPoints=add;
            $scope.teamToSendMessage = id;

            $mdDialog.show({
                contentElement: '#buildMessage',
                parent: angular.element(document.body),
                targetEvent: ev,
                clickOutsideToClose: true
            });
        };
        $scope.closeDialog = function () {
            $mdDialog.hide();
        };
        $scope.setCountdown = function () {
            var date;
            $.each($rootScope.rallys.rally, function (index,value){
                if(value.id==$rootScope.rallySelected){
                    date= $rootScope.rallys.rally[index].endDate;

                }
            });
        };
        function notEmpty(content) {
            var result = /[a-z|A-Z|0-9]/.exec(content);
            if (result != null)
                return true;
            else
                return false;
        }
    });
