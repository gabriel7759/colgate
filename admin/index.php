<?php
function showPage(){

   ?>
    <!DOCTYPE html>
    <html lang="en" ng-app="BlurAdmin">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ColgateClub</title>

        <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900italic,900&subset=latin,greek,greek-ext,vietnamese,cyrillic-ext,latin-ext,cyrillic' rel='stylesheet' type='text/css'>

        <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon-16x16.png">
        <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon-96x96.png">
        <link rel="stylesheet" href="sass/font-awesome-4.7.0/css/font-awesome.css">
        <link rel="stylesheet" href="http://mkdo.mx/ganamasconcolgate/admin/bower_components/angular-material/angular-material.css">
        <link rel="stylesheet" href="styles.css">


        <link rel="stylesheet" href="styles/vendor-3a06cf5b40.css">

        <link rel="stylesheet" href="styles/app-0dab09fa1f.css">
    </head>
    <body ng-controller="MainController" style="top:0px !important;">
    <div class="body-bg"></div>
    <main ng-if="$pageFinishedLoading " ng-class="{ 'menu-collapsed': $baSidebarService.isMenuCollapsed() }">
        <ba-sidebar></ba-sidebar>
        <page-top></page-top>
        <div class="al-main">
            <div class="al-content">
                <content-top></content-top>
                <div ui-view autoscroll="true" autoscroll-body-top></div>
            </div>
        </div>
        <back-top></back-top>
    </main>

    <div id="preloader" ng-show="!$pageFinishedLoading || showLoader == true">
        <div></div>
    </div>

    <script src="scripts/vendor-fc2c839d3e.js"></script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

    <script src="scripts/app-24511c5190.js"></script>
    <!--<script src="/bower_components/angular/angular.js"></script>
    <script src="/bower_components/angular-aria/angular-aria.js"></script>
    <script src="/bower_components/angular-animate/angular-animate.js"></script>
    <script src="/bower_components/angular-material/angular-material.js"></script>-->
    <script src="http://mkdo.mx/ganamasconcolgate/admin/bower_components/angular/angular.js"></script>
    <script src="http://mkdo.mx/ganamasconcolgate/admin/bower_components/angular-aria/angular-aria.js"></script>
    <script src="http://mkdo.mx/ganamasconcolgate/admin/bower_components/angular-animate/angular-animate.js"></script>
    <script src="http://mkdo.mx/ganamasconcolgate/admin/bower_components/angular-material/angular-material.js"></script>
    </body>
    </html>
    <?

}
session_start();

if ((isset($_SESSION['admin'])) ) {
    showPage();
}
else{
    echo '	<SCRIPT LANGUAGE="javascript">
                                location.href = "login.php";
                </SCRIPT>';
}
