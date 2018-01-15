angular.module('MyApp.services', [])
    .factory('Rallys', function () {
        var rallys={
            lastId: 0,
            rally: []
        };
        return {
            getAll: function (){
                return rallys;
            }
        }
    })
    .factory('SideNav', function (){
        var HOMEPAGE = 1;
        var CREATE_RALLY = 2;
        var TRASH = 3;
        var CHATS = 4;
        var PURCHASES = 5;
        var CREATE_NEW_RALLY = 6;
        var START_RALLY = 7;
        var SELECT_RALLY_TO_START = 8;
        var SELECT_RALLY = 9;
        var SELECT_RALLY_TO_EDIT = 10;
        var EDIT_RALLY = 11;
        var DASHBOARD = 12;
        var SELECT_RALLY_TO_DASHBOARD = 13;
        var SELECT_RALLY_TO_CHAT = 14;
        var SELECT_RALLY_TO_BUY = 14;
        var LOGOUT = 15;
        var SideNavButtons={
            buttons: [
                {
                    title: 'Homepage',
                    icon: 'icofont-home',
                    tooltip: 'Homepage',
                    class: '',
                    id: 'homePage',
                    action: HOMEPAGE
                },
                {
                    title: 'Dashboard',
                    icon: 'icofont-dashboard',
                    tooltip: 'Manage your active rallys',
                    class: '',
                    id: 'dashboard',
                    action: SELECT_RALLY_TO_DASHBOARD
                },
                {
                    title: 'Create Rally',
                    icon: 'icofont-plus',
                    tooltip: 'Create rally',
                    class: '',
                    id: 'createRally',
                    action: CREATE_RALLY
                },
                {
                    title: 'Start rally',
                    icon: '  icofont-racing-flag-alt',
                    tooltip: 'Start rally!',
                    class: '',
                    id: 'starRally',
                    action: SELECT_RALLY_TO_START
                },
                {
                    title: 'Edit rally',
                    icon: 'icofont-pencil-alt-5',
                    tooltip: 'Edit rally',
                    class: '',
                    id: 'editRally',
                    action: SELECT_RALLY_TO_EDIT
                },
                {
                    title: 'Chats',
                    icon: 'icofont-chat',
                    tooltip: 'Send messages to your teams',
                    class: '',
                    id: 'chats',
                    action:SELECT_RALLY_TO_CHAT
                },
                {
                    title: 'Purchases',
                    icon: 'icofont-shopping-cart ',
                    tooltip: 'Your purchases',
                    class: '',
                    id: 'purchases',
                    action: SELECT_RALLY_TO_BUY
                }
                ,
                {
                    title: 'Logout',
                    icon: ' icofont-sign-out ',
                    tooltip: 'Logout',
                    class: '',
                    id: 'logout',
                    action: LOGOUT
                }
            ]
        };
        return{
            getAll:function (){
                return SideNavButtons;
            }
        }
    })
    .factory('Rally', function($http, $log, $rootScope){
        var rallys={
            lastId: 0,
            rally: []
        };
        var challengeType =  {
            nothing: 0,
            multipleOption: 1,
            geographic: 2,
            photo: 3,
            openAnswer: 4
        };
        var multipleOptionAnswers = {
            lastId: 0,
            answer: []
        };
        var rallyInfo ={
            rallyName: "",
                numberOfTeams: "",
                companyName: "",
                description: ""
        };
        var challenges = {
            lastId: 0,
            challenge: []
        };
        var challengeOptions = {
            challengeOption: [
                {
                    description: 'Select Type',
                    id: 0
                },
                {
                    description: 'Geographic',
                    id: challengeType.geographic
                },
                {
                    description: 'Multiple option',
                    id: challengeType.multipleOption
                },
                {
                    description: 'Photo',
                    id: challengeType.photo
                },
                {
                    description: 'Open answer',
                    id: challengeType.openAnswer
                }
            ]
        };
        var zones = {
            lastId: 0,
            zone: []
        };
        var geographicAnswer = {};
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
            },
            register: function (info, success, error){
                $http({
                    method : "POST",
                    url :  $rootScope.mainURL+"php/magic_php.php",
                    data : {what:"user", toDo:"register", email:info.email, password:info.password, name:info.name, country:info.country, found:info.found},
                    headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
                }).then(function (response) {
                    console.log(response);
                    if(response.data.status=="win"){
                        success();
                    }
                    else{
                        error();
                    }

                }, function () {
                    error();
                });
            },
            getAll: function (success, error){
                $rootScope.company=getCookie("company");
                $http({
                    method : "POST",
                    url :  $rootScope.mainURL+"php/magic_php.php",
                    data : {what:"rallys", toDo: "get", company:$rootScope.company},
                    headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
                }).then(function (response) {
                    if(response.data=="empty\n"){
                        response.data=rallys;
                    }
                    success(response.data);
                }, function (response) {
                    success(labels);
                });
                return rallys;
            },
            getChallengesType:function (){
                return challengeType;
            },
            getRally:function (id, success, error){
                $http({
                    method : "POST",
                    url :  $rootScope.mainURL+"/php/magic_php.php",
                    data : {what:"rallys", toDo: "getRally", rally:id},
                    headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}

                }).then(function (response) {
                    console.log(response.data);
                    success(response.data);
                }, function (response) {

                });
            },
            getMultipleOptionAnswers: function (){
                return multipleOptionAnswers;
            },
            getRallyInfo : function (){
                return rallyInfo;
            },
            getChallenges : function (){
                return challenges
            },
            getChallengeOptions : function (){
                return challengeOptions;
            },
            deleteMultipleOptionAnswer : function (item, success, error){
                $.each(multipleOptionAnswers.answer, function (index, value) {
                    console.log(value.id);
                    console.log(item);
                    if (value.id == item)
                        multipleOptionAnswers.answer.splice(index, 1);
                });
                success();
            },
            getZones : function (success, error){
                $http({
                    method : "POST",
                    url :  $rootScope.mainURL+"/php/magic_php.php",
                    headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'},
                    data : {what:"zones", toDo: "get", rally: $rootScope.rallySelected}
                }).then(function (response) {
                    console.log(response.data);
                    success(response.data);
                }, function (response) {
                    success(labels);
                });
            },
            getLastId_challenge : function (){
                return challenges.lastId;
            },
            getLastId_zones : function (){
                return zones.lastId;
            },
            setNewChallenge : function (name, type, points, zone, answer, description, success, error){
                answer=JSON.stringify(answer);
                $http({
                    method : "POST",
                    url :  $rootScope.mainURL+"/php/magic_php.php",
                    headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'},
                    data : {what:"challenges", toDo: "add",  rally: $rootScope.rallySelected, name:name, type:type, points:points, zone:zone, answer:answer, description:description}
                }).then(function (response) {
                    if(response.data=="win\n")
                        success();

                    success(response.data);
                }, function (response) {
                    console.log(response);
                });
            },
            setNewChallengeToZone : function (zone){
                $.each(zones.zone, function (index, value) {
                    if (value.id == zone)
                        zones.zone[index].challenges += 1;
                });
            },
            deleteZone : function (zone, success, error){
                $http({
                    method : "POST",
                    url :  $rootScope.mainURL+"/php/magic_php.php",
                    headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'},
                    data : {what:"zones", toDo: "delete", zone:zone}
                }).then(function (response) {
                    if(response.data=="win\n"){
                        success();
                    }
                    success(response.data);
                }, function (response) {
                    console.log(response);
                });
/*                $.each(zones.zone, function (index, value) {
                    if (value.id == zone)
                        zones.zone.splice(index, 1);
                });*/
            },
            changeName_zone :  function (zone, name, success, error){
                $http({
                    method : "POST",
                    url :  $rootScope.mainURL+"/php/magic_php.php",
                    headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'},
                    data : {what:"zones", toDo: "edit", name:name, zone:zone}
                }).then(function (response) {
                    if(response.data=="win\n"){
                        success();
                    }
                    success(response.data);
                }, function (response) {
                    console.log(response);
                });
            },
            deleteChallenge : function (challenge, success, err){
                $http({
                    method : "POST",
                    url :  $rootScope.mainURL+"/php/magic_php.php",
                    headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'},
                    data : {what:"challenges", toDo: "delete", challenge:challenge}
                }).then(function (response) {
                    success(response.data);
                }, function (response) {
                    $log.error(response);
                });
            },
            generatePassword : function (rally, success, error){
                $http({
                    method : "POST",
                    url :  $rootScope.mainURL+"/php/magic_php.php",
                    headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'},
                    data : {what:"rallys", toDo: "generatePassword", rally:rally}
                }).then(function (response) {
                    success(response.data);
                }, function (response) {
                    $log.error(response);
                });
            },
            addZone : function (name, success, error){
                $http({
                    method : "POST",
                    url :  $rootScope.mainURL+"/php/magic_php.php",
                    data : {what:"zones", toDo: "add", name:name, rally: $rootScope.rallySelected},
                    headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}

                }).then(function (response) {
                    if(response.data=="win\n"){
                        success();
                    }
                    success(response.data);
                }, function (response) {
                    success(labels);
                });
            },
            getGeographicAnswer : function (){
                return geographicAnswer;
            },
            setGeographicAnswer: function (data){
                geographicAnswer=data;
                console.log(geographicAnswer);
            },
            setMultipleAnswers: function (data){
                multipleOptionAnswers=data;
            },
            editChallenge : function (challenge, name, type, points, zone, answer, description, success, error){
                answer=JSON.stringify(answer);
                $http({
                    method : "POST",
                    url :  $rootScope.mainURL+"/php/magic_php.php",
                    headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'},
                    data : {what:"challenges", toDo: "edit",  challenge: challenge, name:name, type:type, points:points, zone:zone, answer:answer, description:description}
                }).then(function (response) {
                    if(response.data=="win\n"){
                        success();
                    }
                    success(response.data);
                }, function (response) {

                    console.log(response);
                });
            },
            createRally : function (name, success, error){
                $http({
                    method : "POST",
                    url :  $rootScope.mainURL+"/php/magic_php.php",
                    headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'},
                    data : {what:"rallys", toDo: "create", name:name, company:$rootScope.company}
                }).then(function (response) {
                    success(response.data);
                }, function (response) {
                   $log.error(response);
                });
            },
            changeChallenges_perZone : function (actual, previous){
                $.each(zones.zone, function (index, value) {
                    if (value.id == actual) {
                        value.challenges++;
                    }
                    if (value.id == previous) {
                        value.challenges--;
                    }
                });
            },
            getLastId_multipleOptionAnswers : function (){
                return multipleOptionAnswers.lastId;
            },
            addMultipleOptionAnswer : function (data, success, error){
                console.log(multipleOptionAnswers);
                multipleOptionAnswers.answer.push(data);
                console.log(multipleOptionAnswers);
                multipleOptionAnswers.lastId++;
                success();
            },
            getLastId_rally : function (){
                return rallys.lastId;
            },
            addRally : function (data){
                rallys.rally.push(data);
                rallys.lastId++;
            },
            saveRally : function (name, split, teams, company, description, success, error){
                $http({
                    method : "POST",
                    url :  $rootScope.mainURL+"/php/magic_php.php",
                    headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'},
                    data : {what:"rallys", toDo: "save", rally:$rootScope.rallySelected, name:name, split:split, teams:teams, company:company, description:description}
                }).then(function (response) {
                    success(response.data);
                }, function (response) {
                    $log.error(response);
                });
            },
            editRally : function (rally, data){
                $.each(rallys.rally, function (index, value){
                    if(rally==value.id){
                        rallys.rally[index].name=data.name;
                        rallys.rally[index].teamsCanSplit=data.teamsCanSplit;
                        rallys.rally[index].numberOfTeams=data.numberOfTeams;
                        rallys.rally[index].companyName=data.companyName;
                        rallys.rally[index].description=data.description;
                        rallys.rally[index].challenges=data.challenges;
                        rallys.rally[index].zones=data.zones;
                    }
                });
            },
            deleteRally : function (rally, success, error){
                $http({
                    method : "POST",
                    url :  $rootScope.mainURL+"/php/magic_php.php",
                    headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'},
                    data : {what:"rallys", toDo: "delete", rally:rally}
                }).then(function (response) {
                    if(response.data=="win\n")
                        success();
                    else error();
                }, function (response) {
                    $log.error(response);
                });
            },
            resetMultipleOption : function (){
                multipleOptionAnswers = {
                    lastId: 0,
                    answer: []
                };
            },
            resetAll : function (){
                challenges = {
                    lastId: 0,
                    challenge: []
                };
                zones = {
                    lastId: 0,
                    zone: []
                };
                rallyInfo ={
                    rallyName: "",
                    numberOfTeams: "",
                    companyName: "",
                    description: ""
                };
                multipleOptionAnswers = {
                    lastId: 0,
                    answer: []
                };
            },
            startRally : function (date, success, error){
                $http({
                    method : "POST",
                    url :  $rootScope.mainURL+"/php/magic_php.php",
                    headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'},
                    data : {what:"rallys", toDo: "start",  rally:$rootScope.rallySelected, date:date}
                }).then(function (response) {
                    if(response.data=="win\n")
                        success();
                    else error();
                }, function (response) {
                    $log.error(response);
                });
            }
            /*getAll: function (){
                return rallys;
            },
            getChallengesType:function (){
                return challengeType;
            },
            getMultipleOptionAnswers: function (){
                return multipleOptionAnswers;
            },
            getRallyInfo : function (){
                return rallyInfo;
            },
            getChallenges : function (){
                return challenges
            },
            getChallengeOptions : function (){
                return challengeOptions;
            },
            getZones : function (){
                return zones;
            },
            getLastId_challenge : function (){
                return challenges.lastId;
            },
            getLastId_zones : function (){
                return zones.lastId;
            },
            setNewChallenge : function (data){
                challenges.challenge.push(data);
                challenges.lastId++;
                $.each(zones.zone, function (index, value){
                    if(value.id ==  data.zone){
                        zones.zone[index].challenges++;
                    }
                })
            },
            setNewChallengeToZone : function (zone){
                $.each(zones.zone, function (index, value) {
                    if (value.id == zone)
                       zones.zone[index].challenges += 1;
                });
            },
            deleteZone : function (zone){
                $.each(zones.zone, function (index, value) {
                    if (value.id == zone)
                        zones.zone.splice(index, 1);
                });
            },
            deleteChallenge: function (challenge){
                $.each(challenges.challenge, function (index, value) {
                    if (value.id == challenge)
                        challenges.challenge.splice(index, 1);
                });
            },
            changeName_zone :  function (zone, name){
                $.each(zones.zone, function (index, value) {
                    if (value.id == zone)
                        zones.zone[index].name = name;
                });
            },
            deleteChallenge : function (challenge){
                $.each(challenges.challenge, function (index, value) {
                    if (value != null) {
                        if (value.id == challenge)
                            challenges.challenge.splice(index, 1);
                    }
                });
            },
            addZone : function (data){
                console.log(data);
                zones.zone.push(data);
                zones.lastId++;
                console.log(zones);
            },
            getGeographicAnswer : function (){
                return geographicAnswer;
            },
            setGeographicAnser: function (data){
                geographicAnswer=data;
            },
            editChallenge : function (id, data){
                var previousZone=0;
                $.each(challenges.challenge, function (index, value) {
                    if (id == value.id) {
                        previousZone = value.zone;
                        challenges.challenge[index].name = data.name ;
                        challenges.challenge[index].type = data.type ;
                        challenges.challenge[index].points = data.points ;
                        challenges.challenge[index].zone = data.zone ;
                        challenges.challenge[index].answer = data.answer ;
                    }
                });
                return previousZone;
            },
            changeChallenges_perZone : function (actual, previous){
                $.each(zones.zone, function (index, value) {
                    if (value.id == actual) {
                        value.challenges++;
                    }
                    if (value.id == previous) {
                        value.challenges--;
                    }
                });
            },
            getLastId_multipleOptionAnswers : function (){
                return multipleOptionAnswers.lastId;
            },
            addMultipleOptionAnswer : function (data){
                multipleOptionAnswers.answer.push(data);
                multipleOptionAnswers.lastId++;
            },
            getLastId_rally : function (){
                return rallys.lastId;
            },
            addRally : function (data){
                rallys.rally.push(data);
                rallys.lastId++;
            },
            editRally : function (rally, data){
                $.each(rallys.rally, function (index, value){
                    if(rally==value.id){
                        rallys.rally[index].name=data.name;
                        rallys.rally[index].teamsCanSplit=data.teamsCanSplit;
                        rallys.rally[index].numberOfTeams=data.numberOfTeams;
                        rallys.rally[index].companyName=data.companyName;
                        rallys.rally[index].description=data.description;
                        rallys.rally[index].challenges=data.challenges;
                        rallys.rally[index].zones=data.zones;
                    }
                });
            },
            resetAll : function (){
                challenges = {
                    lastId: 0,
                    challenge: []
                };
                zones = {
                    lastId: 0,
                    zone: []
                };
                rallyInfo ={
                    rallyName: "",
                    numberOfTeams: "",
                    companyName: "",
                    description: ""
                };
                multipleOptionAnswers = {
                    lastId: 0,
                    answer: []
                };
            }*/
        }
    })
    .factory('DashboardService', function($http){
/*       function getDashboard(rally, success, error){
            $http({
                method : "POST",
                url :  $rootScope.mainURL+"/php/magic_php.php",
                data : {what:"dashboard", toDo: "get", rally:rally}
            }).then(function (response) {
                success(response.data);
            }, function (response) {

            });
        }
        function setPunishment(points, team, title, body, success, error){
            $http({
                method : "POST",
                url :  $rootScope.mainURL+"/php/magic_php.php",
                data : {what:"dashboard", toDo: "setPunishment", team:team, title:title, body:body, points:points}
            }).then(function (response) {
                success(response.data);
            }, function (response) {

            });
        }
        function setExtraPoints(points, team, title, body, success, error){
            $http({
                method : "POST",
                url :  $rootScope.mainURL+"/php/magic_php.php",
                data : {what:"dashboard", toDo: "setExtraPoints", team:team, title:title, body:body, points:points}
            }).then(function (response) {
                success(response.data);
            }, function (response) {

            });
        }*/
        return{
            getDashboard : function (rally, success, error){
                $http({
                    method : "POST",
                    url :  $rootScope.mainURL+"/php/magic_php.php",
                    headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'},
                    data : {what:"dashboard", toDo: "get", rally:rally}
                }).then(function (response) {
                    success(response.data);
                }, function (response) {
                    error();
                });
            },
            setPunishment_service: function(points, team, title, body, success, error){
                $http({
                    method : "POST",
                    url :  $rootScope.mainURL+"/php/magic_php.php",
                    headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'},
                    data : {what:"dashboard", toDo: "setPunishment", team:team, title:title, body:body, points:points}
                }).then(function (response) {
                    success(response.data);
                }, function (response) {
                    error();
                });
            },
            setExtraPoints_service : function (points, team, title, body, success, error){
                $http({
                    method : "POST",
                    url :  $rootScope.mainURL+"/php/magic_php.php",
                    headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'},
                    data : {what:"dashboard", toDo: "setExtraPoints", team:team, title:title, body:body, points:points}
                }).then(function (response) {
                    success(response.data);
                }, function (response) {
                    error();
                });
            }
        };
    })
    .service('Labels', function ($http, $log) {
        var labels = {
            addAnswer: 'Add answer',
            addCaptionAnswer: 'Add some caption to the answer',
            addChallengeButton: 'Add challenge',
            addGeographicAnswerDescription: 'Define the latitude and longitude limits',
            addMultipleOptionAnswerDescription: 'Add all the possible options to your question',
            addOpenAnswerDescription: 'Define your answer',
            addPhotoAnswerDescription: 'Do you want to add some caption required to your answer?',
            addPoints: 'Add points',
            addZonesButton: 'Add zone',
            challenge: 'Challenges',
            challengeName: 'Challenge name',
            challengeType: 'Challenge type',
            close: 'Close',
            code: 'someSampleCode01',
            companyName: 'Company name',
            deleteZone: 'Do you want to delete the Zone?',
            deleteChallenge: 'Do you want to delete the Challenge?',
            edit: 'Edit',
            editing: 'Editing Challenge',
            editChallenge: 'Edit challenge',
            editRally: 'Edit Rally',
            editZoneHeader: 'Do you want to change the Zone name?',
            endDate: 'End Date',
            from: 'From',
            latitude: 'Latitude',
            letsGo: 'Lets go!',
            longitude: 'Longitude',
            numberTeams: 'Number of teams',
            points: 'Points',
            rallyName: 'Rally name',
            rallyDescription: 'Rally description',
            rallySaved: "Rally saved! You can edit later on the Rally's section",
            nextStepButton: 'Next Step',
            save: 'Save',
            saveRally: 'Save Rally',
            select: 'Select',
            selectChallengeType: 'Select Type',
            selectZone: 'Select zone',
            sameTeamDifferentApp: 'Teams can use the app in different phones',
            startRally: "Start rally!",
            startRallyQuestion: "Are you sure you want to start this rally?",
            success: 'Success!',
            to: 'To',
            type: 'Type',
            zoneName: 'Zone name',
            zonesPlaceholder: 'Enter zone',
            zones: 'Zones'
        };
        return {

            all : function (success, error){
                var a= $http({
                    method : "POST",
                    url :  $rootScope.mainURL+"/php/magic_php.php",
                    headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'},
                    data : {what:"labels", toDo: "get"}
                }).then(function (response) {
                    success(response.data);
                    return response;

                }, function (response) {
                    success(labels);
                });

            }
        };
    });
