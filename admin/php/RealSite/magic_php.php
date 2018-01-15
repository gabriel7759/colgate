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
        $result = getRecommendations($request->user);
    }
    if($toDo == "getTransactionRecord"){
        $result = getTransactionRecord($request->user);
    }
    if($toDo == "getCategories"){
        $result = getCategories();
    }
    if($toDo == "getProducts"){
        $result = getProducts($request->id);
    }
    if($toDo == "getTradedProducts"){
        $result = getTradedProducts($request->user);
    }
    if($toDo == "tradeProduct"){
        $result = tradeProduct($request->id, $request->user);
    }
    if($toDo == "updateUser"){
        $result = updateUser($request->info);
    }
    if($toDo == "login"){
        $result = login($request->email, $request ->password);
    }

}

$result=html_entity_decode($result,ENT_NOQUOTES, "UTF-8");
$result=preg_replace( "/\r|\n/", "\\n", $result );
$result = utf8_decode($result);
echo $result;
?>