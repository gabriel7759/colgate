!function(){"use strict";angular.module("app",["ngMaterial"])}(),function(){"use strict";function e(e,t,o){return{changePassword:function(e,a,n){t({method:"POST",url:o.mainURL+"php/magic_php.php",data:{what:"user",toDo:"changePassword",info:e},headers:{"Content-Type":"application/x-www-form-urlencoded; charset=UTF-8"}}).then(function(){a()},function(){n()})}}}angular.module("app").service("UserService",["$q","$http","$rootScope",e])}(),function(){function e(e,t,o,a,n,i,l,r,s){var d=new URL(window.location.href),c=d.searchParams.get("foo");l.mainURL="http://mkdo.mx/ganamasconcolgate/admin/",i.user={user:c,password:"",rptPassword:""},i.changePassword=function(){if(""!=i.user.password&&i.user.password==i.user.rptPassword){i.user.password=i.user.password.replace(/\s/g,""),i.user.rptPassword=i.user.rptPassword.replace(/\s/g,"");var e=i.user.password.match(/[0-9]/g).length,t=/[a-z]|[A-Z]/i.test(i.user.password);e>1&&i.user.password.length>5&&t?r.changePassword(i.user,function(){s.show(s.alert().clickOutsideToClose(!0).title("Recuperar contraseña").textContent("La contraseña se modificó correctamente").ariaLabel("Alert Dialog Demo").ok("Ok"))},function(){s.show(s.alert().clickOutsideToClose(!0).title("Error").textContent("Algo salió mal").ariaLabel("Alert Dialog Demo").ok("Ok"))}):s.show(s.alert().clickOutsideToClose(!0).title("Error").textContent("La contraseña debe contener al menos 2 números, caracteres y tener una longitud de 6 caracteres").ariaLabel("Alert Dialog Demo").ok("Ok"))}else s.show(s.alert().clickOutsideToClose(!0).title("Error").textContent("Las contraseñas no coinciden").ariaLabel("Alert Dialog Demo").ok("Ok"))}}angular.module("app").controller("MainController",["$mdBottomSheet","$log","$q","$state","$mdToast","$scope","$rootScope","UserService","$mdDialog",e])}(),angular.module("angularMaterialAdmin",["ngAnimate","ngCookies","ngSanitize","ui.router","ngMaterial","nvd3","app","md.data.table","ngFileUpload"]).config(["$stateProvider","$urlRouterProvider","$mdThemingProvider","$mdIconProvider",function(e,t,o,a){e.state("home",{url:"/login",templateUrl:"app/views/main.html",controller:"MainController",controllerAs:"vm","abstract":!0}),t.otherwise("/login"),o.definePalette("defaultPrimary",{50:"#808080",100:"#fff",200:"#1aab27",300:"#fe0000",400:"#fe0000",500:"#fe0000",600:"#fe0000",700:"#fe0000",800:"#fe0000",900:"#fe0000",A100:"#fe0000",A200:"#fe0000",A400:"#fe0000",A700:"#fe0000",contrastDefaultColor:"dark"}),o.theme("default").primaryPalette("defaultPrimary",{"default":"600","hue-1":"50","hue-2":"100","hue-3":"200"}),a.icon("user","assets/images/user.svg",64)}]),angular.module("angularMaterialAdmin").run(["$templateCache",function(e){e.put("app/views/categories.html",'<div layout="column" ng-cloak="" layout-align="center center" cache-view="false"><h2 class="colorRed">Selecciona una categoría</h2><div layout="row" layout-align="center" layout-wrap="" flex="50"><div ng-repeat="category in categories" flex="50" layout="row" layout-align="center"><md-button href="#!/categorias/{{category.id}}"><img src="{{mainURL + category.image}}" style="width:50%"><h4 class="colorRed">{{category.name}}</h4></md-button></div><div flex="50" layout="row" layout-align="center"><md-button href="#!/categorias/0"><img src="assets/images/allCategories.png" style="width:50%"><h4 class="colorRed">Todos los productos</h4></md-button></div></div></div>'),e.put("app/views/category.html",'<div layout="column" ng-cloak="" layout-xs="column" layout-align="center"><div layout="row" layout-align="end center"><md-input-container flex="50"><label>Filtrar</label><md-select name="filtrer" ng-model="filterType"><md-option ng-repeat="type in filterOptions" value="{{type.id}}">{{type.description}}</md-option></md-select><div class="errors" ng-messages="myForm.favoriteColor.$error"><div ng-message="required">Required</div></div></md-input-container></div><div layout="row" ng-cloak="" layout-xs="column" layout-align="center" flex="90" layout-wrap=""><div layout="row" layout-wrap="" layout-align="center center" ng-repeat="product in products | orderBy:\'name\'" flex="100" flex-gt-sm="25" ng-if="filterType==1"><div layout="row" layout-gt-sm="column" layout-align="center center"><div flex="" layout-align="center center"><img src="{{mainURL + product.image}}" style="width:90%"></div><div flex="" layout="column" layout-align="center center"><h3>{{product.name}}</h3><p hide="" show-gt-sm="">{{product.miniDescription}}</p><md-button ng-click="openDialog(\'descriptionDialog\', product, $event)" layout-align="center center">Ver más...</md-button><div layout="column" layout-gt-sm="row" flex=""><div class="pointsIndicator" flex-gt-sm="55" flex="100" layout="column" layout-gt-sm="row" layout-align="end center"><h5 class="colorRed paddingRight1">{{product.points}} Puntos</h5></div><md-button class="headerButtons colorWhite buyButton" flex-gt-sm="55" flex="100" ng-click="openDialog(\'TradeDialog\', product, $event)">Realizar Canje</md-button></div></div></div></div><div layout="row" layout-wrap="" layout-align="center center" ng-repeat="product in products | orderBy:\'-points\'" flex="25" ng-if="filterType==2"><div><img src="{{mainURL + product.image}}" style="width:90%"><h3>{{product.name}}</h3><p hide="" show-gt-sm="">{{product.miniDescription}}</p><md-button ng-click="openDialog(\'descriptionDialog\', product, $event)">Ver más...</md-button><div layout="column" layout-gt-sm="row"><div class="pointsIndicator" flex-gt-sm="55" flex="" layout="row" layout-align="end center"><h5 class="colorRed paddingRight1">{{product.points}} Puntos</h5></div><md-button class="headerButtons colorWhite buyButton" flex="" ng-click="openDialog(\'TradeDialog\', product, $event)">Realizar Canje</md-button></div></div></div><div layout="row" layout-wrap="" layout-align="center center" ng-repeat="product in products | orderBy:\'points\'" flex="25" ng-if="filterType==3"><div><img src="{{mainURL + product.image}}" style="width:90%"><h3>{{product.name}}</h3><p hide="" show-gt-sm="">{{product.miniDescription}}</p><md-button ng-click="openDialog(\'descriptionDialog\', product, $event)">Ver más...</md-button><div layout="column" layout-gt-sm="row"><div class="pointsIndicator" flex-gt-sm="55" flex="" layout="row" layout-align="end center"><h5 class="colorRed paddingRight1">{{product.points}} Puntos</h5></div><md-button class="headerButtons colorWhite buyButton" flex="" ng-click="openDialog(\'TradeDialog\', product, $event)">Realizar Canje</md-button></div></div></div></div><div style="display: none;"><div class="md-dialog-container" id="descriptionDialog"><md-dialog aria-label="Mango (Fruit)" flex-gt-xs="55"><form ng-cloak=""><md-toolbar class="md-primary md-hue-2"><div class="md-toolbar-tools md-primary md-hue-2"><div layout="row" flex="" layout-align="center center"></div><md-button class="md-icon-button" ng-click="closeDialog()"><md-icon><i class="material-icons">close</i></md-icon></md-button></div></md-toolbar><md-dialog-content><div layout="column" layout-padding="" layout-align="center center"><div layout="row" layout-padding="" layout-align="center center"><img src="{{mainURL + productToView.image}}" style="width:30%"></div><h3>{{productToView.name}}</h3><p>{{productToView.description}}</p></div></md-dialog-content><md-dialog-actions layout="row" layout-align="space-around center" layout-padding=""><div class="pointsIndicator" flex-gt-sm="25" flex="" layout="row" layout-align="end center"><h5 class="colorRed paddingRight1">{{productToView.points}} Puntos</h5></div><md-button class="headerButtons colorWhite buyButton" flex="25" ng-click="openDialog(\'TradeDialog\', null, $event)">Realizar Canje</md-button></md-dialog-actions></form></md-dialog></div><div class="md-dialog-container" id="canTradeDialog"><md-dialog aria-label="Mango (Fruit)" flex-gt-xs="55"><form ng-cloak=""><md-toolbar class="md-primary md-hue-2"><div class="md-toolbar-tools md-primary md-hue-2"><div layout="row" flex="" layout-align="center center"></div><md-button class="md-icon-button" ng-click="closeDialog()"><md-icon><i class="material-icons">close</i></md-icon></md-button></div></md-toolbar><md-dialog-content><div layout="column" layout-padding="" layout-align="center center"><div><h1><strong class="colorRed">Deseas canjear</strong><strong class="colorBlack">{{productToView.name}}</strong> <strong class="colorRed">por</strong> <strong class="colorBlack">{{productToView.points}} puntos.</strong></h1></div><div><h1 class="colorRed">Puntos Actuales: {{points}}</h1><h1 class="colorRed">Puntos a canjear: {{productToView.points}}</h1></div></div></md-dialog-content><md-dialog-actions layout="row" layout-align="center center"><md-button class="md-raised colorGreen" ng-click="tradeProduct($event)"><strong>Canjear</strong></md-button><md-button class="md-raised" ng-click="closeDialog()"><strong>Cancelar</strong></md-button></md-dialog-actions></form></md-dialog></div><div class="md-dialog-container" id="cantTradeDialog"><md-dialog aria-label="Mango (Fruit)" flex-gt-xs="55"><form ng-cloak=""><md-toolbar class="md-primary md-hue-2"><div class="md-toolbar-tools md-primary md-hue-2"><div layout="row" flex="" layout-align="center center"></div><md-button class="md-icon-button" ng-click="closeDialog()"><md-icon><i class="material-icons">close</i></md-icon></md-button></div></md-toolbar><md-dialog-content><div layout="column" layout-padding="" layout-align="center center"><div><h1><strong class="colorBlack">No cuentas con los puntos suficientes<br>para canjear éste artículo.</strong></h1></div></div></md-dialog-content><md-dialog-actions layout="row" layout-align="center center"><md-button class="md-raised" ng-click="closeDialog()"><strong>Cancelar</strong></md-button></md-dialog-actions></form></md-dialog></div><div class="md-dialog-container" id="tradeCompletedDialog"><md-dialog aria-label="Mango (Fruit)" flex-gt-xs="55"><form ng-cloak=""><md-toolbar class="md-primary md-hue-2"><div class="md-toolbar-tools md-primary md-hue-2"><div layout="row" flex="" layout-align="center center"></div><md-button class="md-icon-button" ng-click="closeDialog()"><md-icon><i class="material-icons">close</i></md-icon></md-button></div></md-toolbar><md-dialog-content><div layout="column" layout-padding="" layout-align="center center"><div><h1><strong class="colorRed">¡Felicidades!</strong></h1><h3><strong class="colorRed">Has canjeado</strong> <strong class="colorBlack">{{productToVie.name}}</strong> <strong class="colorRed">por</strong> <strong class="colorBlack">{{productToVie.points}} puntos.</strong></h3><h3><strong class="colorBlack">Puedes ver el Historial de Compras en</strong> <strong class="colorRed">Mi perfil</strong> <strong class="colorBlack">.</strong></h3><h3><strong class="colorBlack">Así mismo, puedes revisar el</strong> <strong class="colorRed">Estado de Pedido</strong> <strong class="colorBlack">(Solicitado, En Camino, Entregado) para cada uno de sus artículos.</strong></h3></div></div></md-dialog-content><md-dialog-actions layout="row" layout-align="center center"><md-button class="md-raised" ng-click="closeDialog()"><strong>Aceptar</strong></md-button></md-dialog-actions></form></md-dialog></div></div></div>'),e.put("app/views/historial.html",'<div layout="column" ng-cloak="" layout-xs="column" layout-align="center center" flex=""><h3 class="colorRed"><i class="fa fa-caret-down" aria-hidden="true"></i> Historial de compras <i class="fa fa-caret-down" aria-hidden="true"></i></h3><div flex="75" layout-align="center center" layout="row" layout-padding="" layout-wrap=""><div ng-repeat="tradeInfo in trades" layout="column" flex="50" flex-gt-sm="25" layout-align="center center"><img ng-src="{{mainURL+tradeInfo.image}}" class="recommendationImage"><h4 class="colorBlack">{{tradeInfo.name}}</h4><h4 class="colorRed">{{tradeInfo.points}} Puntos</h4><h4 class="colorBlack">Estado de pedido</h4><md-progress-linear class="{{tradeInfo.statusColor}}" md-mode="determinate" value="{{tradeInfo.statusPercentage}}"></md-progress-linear><h4 class="colorRed">{{tradeInfo.statusDescription}}</h4></div><div ng-if="trades.length == 0">No has hecho ninguna compra</div></div></div>'),e.put("app/views/home.html",'<div layout="column" ng-cloak="" layout-xs="column" layout-align="center center"><div layout="column" layout-gt-sm="row" flex="" layout-align="center center"><div flex="50" flex-xs="100" layout="column" layout-align="center center"><div layout="column" layout-align="center center"><img ng-src="{{mainURL+userInfo.image}}" class="profileImage" onerror=\'this.src="assets/images/user.png"\'><h1 class="noMargin">Hola {{name}}!</h1><h1 class="noMargin">Tus puntos: {{points}}</h1></div></div><div flex="50" flex-xs="100" layout="column" layout-align="center center"><div layout="column" layout-align="center center"><div><img ng-src="{{mainURL+userInfo.store.image}}" class="storeImage"></div></div></div></div><h3 class="colorRed"><i class="fa fa-caret-down" aria-hidden="true"></i> Recomendaciones <i class="fa fa-caret-down" aria-hidden="true"></i></h3><div layout="column" layout-gt-sm="row" layout-align="center center" flex=""><md-button flex-sm="100" flex="" ng-repeat="category in categories" class="generalContainer recommendationContainer" layout-padding="" layout="row" layout-gt-sm="column" layout-align="center center" href="#!/categorias/{{category.id}}"><div flex="" layout="column" layout-align="center center"><img ng-src="{{mainURL+category.image}}" class="recommendationImage"><div flex="" layout="row" layout-align="center center"><h3 class="colorRed brakeText">{{category.name}}</h3></div></div><div ng-repeat="product in category.products" flex="" layout="column" layout-align="center center"><div layout="column" layout-align="center center"><img ng-src="{{mainURL+product.image}}" class="recommendationImage"></div><h5 class="brakeText">{{product.name}}</h5></div></md-button></div></div>'),e.put("app/views/main.html",'<md-sidenav md-is-locked-open="false" md-component-id="left" class="md-whiteframe-z2 md-sidenav-left"><md-button ng-repeat-start="item in vm.menuItems" layout="column" layout-align="center center" flex="" class="capitalize" ng-click="vm.selectItem(item)" ui-sref-active="md-primary" ui-sref="{{item.sref}}"><div hide-sm="" hide-md="" class="md-tile-content"><i class="material-icons md-36">{{item.icon}}</i></div><div class="md-tile-content">{{item.name}}</div></md-button><md-divider ng-repeat-end=""></md-divider><md-button layout="column" layout-align="center center" flex="" class="capitalize" ui-sref-active="md-primary" href="logout.php"><div hide-sm="" hide-md="" class="md-tile-content"><i class="material-icons">exit_to_app</i></div><div class="md-tile-content">Salir</div></md-button></md-sidenav><div layout="column" flex="" layout-align="end center"><div class="logoContainer" ui-sref=".recommendations"><img src="assets/images/logo.jpg" class="mainLogo"></div><div layout="row" layout-align="center end" ng-if="hideHeader==true"><md-button class="md-raised md-primary md-hue-1 headerButtons colorWhite biggerHeader" ng-click="navigateTo(\'home.profile\'); activateButton(\'profileButton\')">Mi perfil</md-button><md-button class="md-raised md-primary md-hue-1 headerButtons colorWhite biggerHeader" ng-click="navigateTo(\'home.history\'); activateButton(\'statusButton\')">Historial de<br>compras</md-button><md-button class="md-raised md-primary md-hue-1 headerButtons colorWhite biggerHeader" ng-click="navigateTo(\'home.statusPoints\'); activateButton(\'statusButton\')">Status de<br>puntos</md-button><md-button class="md-raised md-primary md-hue-1 headerButtons colorWhite biggerHeader" ng-click="navigateTo(\'home.categories\'); activateButton(\'catalogButton\')">Catalogo de<br>premios</md-button></div><div layout="row" layout-align="center end" ng-if="hideHeader!=true"><md-button class="md-raised md-primary md-hue-1 headerButtons colorWhite" id="profileButton" ng-click="navigateTo(\'home.profile\'); activateButton(\'profileButton\')">Mi perfil</md-button><md-button class="md-raised md-primary md-hue-1 headerButtons colorWhite" id="historyButton" ng-click="navigateTo(\'home.history\'); activateButton(\'statusButton\')">Historial de<br>compras</md-button><md-button class="md-raised md-primary md-hue-1 headerButtons colorWhite" id="statusButton" ng-click="navigateTo(\'home.statusPoints\'); activateButton(\'statusButton\')">Status de<br>puntos</md-button><md-button class="md-raised md-primary md-hue-1 headerButtons colorWhite" id="catalogButton" ng-click="navigateTo(\'home.categories\'); activateButton(\'catalogButton\')">Catalogo de<br>premios</md-button></div><md-toolbar class="headerBar" ng-if="hideHeader!=true"><div class="md-toolbar-tools"><md-button class="md-icon-button" ng-click="vm.toggleItemsList()" aria-label="Settings"><i class="fa fa-bars colorRed menuIcon" aria-hidden="true"></i></md-button><div flex="" layout="row" layout-align="center center" md-truncate=""><div flex="" layout="row" layout-align="center center" md-truncate="" ng-if="categoryInfo!=null"><img src="{{ mainURL + categoryInfo.image}}" style="height: 50px; width: 50px" hide-xs=""><h1 class="colorRed">{{categoryInfo.name}}</h1></div></div><div layout="row" layout-align="end center"><div layout="column" class="colorRed noMargin"><h6 class="colorRed noMargin">Tus puntos</h6><p class="colorRed noMargin fontSize10">Al 10 de Julio 2017</p></div><h1 class="colorRed noMargin"><strong>{{points}}</strong></h1></div></div></md-toolbar><md-content flex="100" class="md-padding page-content" style="width: 100%;"><div ui-view=""></div><div style="margin-top: 80px;">&nbsp;</div></md-content></div>'),e.put("app/views/profile.html",'<div layout="column" ng-cloak=""><div layout="column" layout-align="center center"><h3 class="colorRed noMargin" flex="">Recuerda mantener tus datos actualizados y completados</h3></div><div flex="100" layout-padding="" layout-align="space-around center" layout="column" layout-gt-sm="row"><div layout="column" layout-gt-sm="row" flex="" layout-align="center center"><div flex="50" flex-xs="100" layout="column" layout-align="center center"><div layout="column" layout-align="center center"><img ng-src="{{mainURL+userInfo.image}}" class="profileImage" onerror=\'this.src="assets/images/user.png"\'><div layout="row" layout-align="center center"><md-button class="md-raised md-primary littleActionButton colorWhite" ng-click="openEditProfileDialog($event)">Editar perfil</md-button></div><h1 class="noMargin">{{name}}</h1></div></div><div flex="50" flex-xs="100" layout="column" layout-align="center center"><div layout="column" layout-align="center center"><div><img ng-src="{{mainURL+userInfo.store.image}}" class="storeImage"></div></div></div></div><div layout="column" flex="" layout-align="center"><div layout="row"><div flex="60" flex-xs="100" layout="column" layout-align="start start"><h3 class="noMargin">RFC</h3><p class="colorRed noMargin">{{userInfo.rfc}}</p></div><div flex="40" flex-xs="100" layout="column" layout-align="start start"><h3 class="noMargin">Teléfono</h3><p class="colorRed noMargin">{{userInfo.phone}}</p></div></div><div layout="row"><div flex="60" flex-xs="100" layout="column" layout-align="start start"><h3 class="noMargin">Ciudad</h3><p class="colorRed noMargin">{{userInfo.city}}</p></div><div flex="40" flex-xs="100" layout="column" layout-align="start start"><h3 class="noMargin">Mayorista</h3><p class="colorRed noMargin">{{userInfo.store.name}}</p></div></div><div layout="row"><div flex="60" flex-xs="100" layout="column" layout-align="start start"><h3 class="noMargin">CURP</h3><p class="colorRed noMargin">{{userInfo.curp}}</p></div><div flex="40" flex-xs="100" layout="column" layout-align="start start"><h3 class="noMargin">Ruta</h3><p class="colorRed noMargin">{{userInfo.route}}</p></div></div><h3 class="noMargin">Correo</h3><p class="colorRed noMargin">{{userInfo.email}}</p></div></div></div><div style="display: none;"><div class="md-dialog-container" id="editProfile"><md-dialog aria-label="Mango (Fruit)" flex-gt-xs="75"><form ng-cloak=""><md-toolbar class="md-primary md-hue-2"><div class="md-toolbar-tools md-primary md-hue-2"><div layout="row" flex="" layout-align="center center"><h1 class="colorRed">Editar perfil</h1></div><md-button class="md-icon-button" ng-click="closeDialog()" ng-if="firstTime!=1"><md-icon><i class="material-icons">close</i></md-icon></md-button></div></md-toolbar><md-dialog-content layout-padding=""><form name="userForm"><div layout="" layout-xs="column" layout-align="center"><div><img ng-src="{{mainURL+userInfo.image}}" class="profileImageSquare" onerror=\'this.src="assets/images/user.png"\'></div><div><md-button class="md-primary" type="file" ngf-select="onFileSelect($file)">Seleccionar imagen</md-button><p>{{file.name}}</p></div></div><div layout="" layout-xs="column" layout-align="center"><p class="colorRed">Asegúrate que todos los datos estén correctos y completos</p></div><div layout="" layout-xs="column"><md-input-container flex="" layout-padding="" ng-if="showHints == false"><label>Nombre</label> <input ng-model="$parent.user.name" name="userName"></md-input-container><md-input-container flex-gt-sm="" ng-if="showHints == true"><label>Nombre</label> <input ng-model="$parent.user.name" name="userName" required=""><div ng-messages="userForm.userName.$error"><div ng-message="required">Necesitas un nombre</div></div></md-input-container><md-input-container flex="" layout-padding="" ng-if="showHints == false"><label>Teléfono</label> <input ng-model="user.phone"></md-input-container><md-input-container flex-gt-sm="" ng-if="showHints == true"><label>Teléfono</label> <input ng-model="$parent.user.phone" name="userPhone" required=""><div ng-messages="userForm.userPhone.$error"><div ng-message="required">Necesitas un teléfono</div></div></md-input-container></div><div layout="" layout-xs="column"><md-input-container flex="" layout-padding="" ng-if="showHints == false"><label>RFC</label> <input ng-model="$parent.user.rfc"></md-input-container><md-input-container flex-gt-sm="" ng-if="showHints == true"><label>RFC</label> <input ng-model="$parent.user.rfc" name="userRFC" required=""><div ng-messages="userForm.userRFC.$error"><div ng-message="required">Necesitas un RFC</div></div></md-input-container><md-input-container flex="" layout-padding="" ng-if="showHints == false"><label>Curp</label> <input ng-model="$parent.user.curp"></md-input-container><md-input-container flex-gt-sm="" ng-if="showHints == true"><label>Curp</label> <input ng-model="$parent.user.curp" name="userCurp" required=""><div ng-messages="userForm.userCurp.$error"><div ng-message="required">Necesitas un CURP</div></div></md-input-container></div><div layout="" layout-xs="column"><md-input-container flex="" layout-padding="" ng-if="showHints == false"><label>Calle</label> <input ng-model="$parent.user.street"></md-input-container><md-input-container flex-gt-sm="" ng-if="showHints == true"><label>Calle</label> <input ng-model="$parent.user.street" name="userStreet" required=""><div ng-messages="userForm.userStreet.$error"><div ng-message="required">Necesitas agregar una calle</div></div></md-input-container><md-input-container flex="" layout-padding="" ng-if="showHints == false"><label>Colonia</label> <input ng-model="$parent.user.suburb"></md-input-container><md-input-container flex-gt-sm="" ng-if="showHints == true"><label>Colonia</label> <input ng-model="$parent.user.suburb" name="userSuburb" required=""><div ng-messages="userForm.userSuburb.$error"><div ng-message="required">Necesitas agregar una colonia</div></div></md-input-container><md-input-container flex="" layout-padding="" ng-if="showHints == false"><label>Número</label> <input ng-model="$parent.user.number"></md-input-container><md-input-container flex-gt-sm="" ng-if="showHints == true"><label>Número</label> <input ng-model="$parent.user.number" name="userNumber" required=""><div ng-messages="userForm.userNumber.$error"><div ng-message="required">Necesitas agregar un número</div></div></md-input-container></div><div layout="" layout-xs="column"><md-input-container flex="" layout-padding="" ng-if="showHints == false"><label>Ciudad o municipio</label> <input ng-model="$parent.user.city"></md-input-container><md-input-container flex-gt-sm="" ng-if="showHints == true"><label>Ciudad o municipio</label> <input ng-model="$parent.user.city" name="userCity" required=""><div ng-messages="userForm.userCity.$error"><div ng-message="required">Necesitas agregar una ciudad</div></div></md-input-container><md-input-container flex="" layout-padding="" ng-if="showHints == false"><label>Estado</label> <input ng-model="$parent.user.state"></md-input-container><md-input-container flex-gt-sm="" ng-if="showHints == true"><label>Estado</label> <input ng-model="$parent.user.state" name="userState" required=""><div ng-messages="userForm.userState.$error"><div ng-message="required">Necesitas agregar un estado</div></div></md-input-container><md-input-container flex="" layout-padding="" ng-if="showHints == false"><label>Código postal</label> <input ng-model="$parent.user.cp"></md-input-container><md-input-container flex-gt-sm="" ng-if="showHints == true"><label>Código postal</label> <input ng-model="$parent.user.cp" name="userCP" required=""><div ng-messages="userForm.userCP.$error"><div ng-message="required">Necesitas agregar un código postal</div></div></md-input-container></div><div layout="" layout-xs="column"><md-input-container flex="" layout-padding=""><label>Contraseña</label> <input ng-model="user.password" type="password"></md-input-container><md-input-container flex="" layout-padding=""><label>Repetir contraseña</label> <input ng-model="user.password" type="password"></md-input-container></div><div layout="row" layout-align="center center" ng-if="firstTime==1"><div layout="row" layout-align="center center" style="margin-top: 15px; color:#fff"><md-checkbox ng-model="$parent.user.terms" aria-label="Terms" class="md-primary"></md-checkbox></div><div><p flex="">Acepto tanto los<md-button class="md-primary noMargin" ng-href="{{terms}}" target="_blank"><strong>TERMINOS Y CONDICIONES</strong></md-button>anteriores como el<md-button class="md-primary noMargin" ng-href="{{privacy}}" target="_blank"><strong>AVISO DE PRIVACIDAD</strong></md-button>.</p></div></div></form></md-dialog-content><md-dialog-actions layout="row"><md-button ng-click="closeDialog(\'useful\')" ng-if="firstTime!=1">Cancelar</md-button><md-button ng-click="updateUser(\'not useful\')">Guardar</md-button></md-dialog-actions></form></md-dialog></div></div>'),e.put("app/views/statusPoints.html",'<div layout="row" ng-cloak="" layout-xs="column"><div flex="40" flex-xs="100" layout-padding="" layout-align="center center"><div layout="row"><div flex="100" layout="column" layout-align="center center"><h3 class="noMargin">¡Hola {{name}}!</h3></div></div><div layout="row"><div flex="100" layout="column" layout-align="center center"><div layout="column" layout-align="center center"><img ng-src="{{mainURL+userInfo.image}}" class="profileImage" onerror=\'this.src="assets/images/user.png"\'></div></div></div></div><div flex="60" flex-xs="100" layout-align="center center"><div layout="row" layout-align="center center"><div flex="" class="noMargin"></div><div flex="" class="noMargin" layout="row" layout-align="center center"><h3 class="colorBlack">Puntos<br>Ganados</h3></div><div flex="" class="noMargin" layout="row" layout-align="center center"><h3 class="colorBlack">Puntos<br>Redimidos</h3></div></div><div layout="row" layout-align="center center" ng-repeat="transaction in transactions" class="noMargin"><div flex="" class="noMargin" layout="row" layout-align="center center"><h3 class="colorRed">{{transaction.name}}</h3></div><div flex="" class="noMargin" layout="row" layout-align="center center"><h3 class="colorYellow">{{transaction.added}}</h3></div><div flex="" class="noMargin" layout="row" layout-align="center center"><h3 class="colorYellow">{{transaction.removed}}</h3></div></div></div></div><div flex="100" layout="row" layout-align="center center"><div flex="90" class="redDivider"></div></div><div layout="row" ng-cloak="" layout-xs="column"><div flex="40" flex-xs="100" layout-padding="" layout-align="center center"></div><div flex="60" flex-xs="100" layout-align="center center"><div layout="row" layout-align="center center" class="noMargin"><div flex="" class="noMargin" layout="row" layout-align="center center"><h1 class="colorRed">Total:</h1></div><div flex="" class="noMargin" layout="row" layout-align="center center"><h3 class="colorRed">{{points}} Puntos</h3></div><div flex="" class="noMargin" layout="row" layout-align="center center"><md-button class="md-raised md-primary colorWhite" ng-click="navigateTo(\'home.categories\')">Ir al catálogo</md-button></div></div></div></div><div layout="column" ng-cloak="" layout-align="center center"></div>'),e.put("app/views/partials/beers.porters.view.html",'<div class="row"><h1 class="col-lg-12">IM THE PORTERS BEER PAGE</h1></div><div class="row"><div class="col-lg-12"><ul><li>Founders Porter</li><li>Black Butte Porter</li></ul></div></div>'),e.put("app/views/partials/beers.wheat.view.html",'<div class="row"><h1 class="col-lg-12">IM THE WHEAT BEER PAGE</h1></div><div class="row"><div class="col-lg-12"><ul><li>312 Urban Wheat</li><li>A Little Sumpin\' Sumpin\' Ale</li></ul></div></div>'),e.put("app/views/partials/gettingstarted.view.html","<h1>Get yourself started by clicking on one of the beers within the beer menu item</h1>"),e.put("app/views/partials/menu-link.tmpl.html",'<li ng-repeat="section in vm.menu.sections" class="parent-list-item" ng-class="{\'parentActive\' : vm.isSectionSelected(section)}"><h2 class="menu-heading" ng-if="section.type === \'heading\'" id="heading_{{ section.name | nospace }}">{{section}}</h2><menu-link section="section" ng-if="section.type === \'link\'"></menu-link><menu-toggle section="section" ng-if="section.type === \'toggle\'"></menu-toggle></li>'),e.put("app/views/partials/menu-toggle.tmpl.html",'<md-button ng-class="{\\\'{{section.icon}}\\\' : true}" ui-sref-active="active" ui-sref="{{section.state}}" ng-click="focusSection()">{{section | humanizeDoc}} <span class="md-visually-hidden" ng-if="isSelected()">current page</span></md-button>')}]);