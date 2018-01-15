<?php
header('Content-Type: text/html; charset=ISO-8859-1');
header("Access-Control-Allow-Origin: *");
$result ='fail10';
$what ='';
$toDo = '' ;
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$what = $request->what;
$toDo = $request->toDo;

$result=html_entity_decode($result,ENT_NOQUOTES, "UTF-8");
$result=preg_replace( "/\r|\n/", "\\n", $result );
$result = utf8_decode($result);

if($what == "admin"){
    include_once ("admin.php");
    if($toDo == "createStore"){
        $result = createStore($request->info);
    }
    if($toDo == "deleteStore"){
        $result = deleteStore($request->store);
    }
    if($toDo == "getStores"){
        $result = getStores();
    }
    if($toDo == "getStore"){
        $result = getStore($request->store);
    }
    if ($toDo == "updateStore"){
        $result = updateStore($request->info);
    }



    if($toDo == "createUser"){
        $result = createUser($request->info);
    }
    if($toDo == "createUsersFromFile"){
        $result = createUsersFromFile($request->info);
    }
    if($toDo == "setPointsFromFile"){
        $result = setPointsFromFile($request->info);
    }
    if($toDo == "deleteUser"){
        $result = deleteUser($request->user);
    }
    if($toDo == "getUsers"){
        $result = getUsers();
    }
    if($toDo == "getUser"){
        $result = getUser($request->user);
    }
    if ($toDo == "updateUser"){
        $result = updateUser($request->info);
    }




    if($toDo == "createAdmin"){
        $result = createAdmin($request->info);
    }
    if($toDo == "deleteAdmin"){
        $result = deleteAdmin($request->user);
    }
    if($toDo == "getAdmins"){
        $result = getAdmins();
    }
    if($toDo == "getAdmin"){
        $result = getAdmin($request->user);
    }
    if ($toDo == "updateAdmin"){
        $result = updateAdmin($request->info);
    }



    if($toDo == "createCategory"){
        $result = createCategory($request->info);
    }
    if($toDo == "deleteCategory"){
        $result = deleteCategory($request->category);
    }
    if($toDo == "getCategories"){
        $result = getCategories();
    }
    if($toDo == "getCategory"){
        $result = getCategory($request->category);
    }
    if ($toDo == "updateCategory"){
        $result = updateCategory($request->info);
    }



    if($toDo == "createProduct"){
        $result = createProduct($request->info);
    }
    if($toDo == "deleteProduct"){
        $result = deleteProduct($request->product);
    }
    if($toDo == "getProducts"){
        $result = getProducts();
    }
    if($toDo == "getProduct"){
        $result = getProduct($request->product);
    }
    if ($toDo == "updateProduct"){
        $result = updateProduct($request->info);
    }
    if($toDo == "makeTransaction"){
        $result = addPoints($request->info);
    }
    if($toDo == "getTradedProducts"){
        $result = getTradedProducts($request->user);
    }
    if($toDo == "changeStatus"){
        $result = changeStatus($request->info);
    }

    if($toDo == "getUsersByStore"){
        $result = getUsersByStore($request->store);
    }
    if($toDo == "getUsersByCity"){
        $result = getUsersByCity($request->store);
    }
    if($toDo == "getUsersByRoute"){
        $result = getUsersByRoute($request->route, $request->store);
    }
    if($toDo == "getTradedProductsReport"){
        $result = getTradedProductsReport($request->status);
    }
    if($toDo == "setMessage"){
        $result = setMessage($request->message);
    }
    if($toDo == "setTradeDate"){
        $result = setTradeDate($request->info);
    }
    if($toDo == "getInfo"){
        $result = getInfo();
    }


}
if($what == "user"){
    include_once ("user.php");
    if($toDo == "getUserInfo"){
        $result = getUserInfo ($request->user);
    }
    if($toDo == "getUserPoints"){
        $result = getUserPoints ($request->user);
    }
    if($toDo == "getRecommendations"){
        $result = getRecommendations($request->user, $request->level);
    }
    if($toDo == "getTransactionRecord"){
        $result = getTransactionRecord($request->user);
    }
    if($toDo == "getCategories"){
        $result = getCategories();
    }
    if($toDo == "getProducts"){
        $result = getProducts($request->id, $request->level);
    }
    if($toDo == "getTradedProducts"){
        $result = getTradedProducts($request->user);
    }
    if($toDo == "tradeProduct"){
        $result = tradeProduct($request->id, $request->user);
    }
    if($toDo == "sendMessage"){
        $result = sendMessage($request->user, $request->message);
    }
    if($toDo == "tradeRecharge"){
        $result = tradeRecharge($request->info, $request->user);
    }
    if($toDo == "updateUser"){
        $result = updateUser($request->info);
    }
    if($toDo == "changePassword"){
        $result = changePassword($request->info);
    }
    if($toDo == "forgotPassword"){
        forgotPassword($request->info);
    }
    if($toDo == "login"){
        $result = login($request->email, $request ->password);
    }
    if($toDo == "getInfo"){
        $result = getInfo();
    }


}

$result=html_entity_decode($result,ENT_NOQUOTES, "UTF-8");
$result=preg_replace( "/\r|\n/", "\\n", $result );
$result = utf8_decode($result);
echo $result;
?>