<?php 

// Fill these in with the information from your WalletCore account. 
$ipn_secret = "TESTIPNSECRET"; // Same as WalletCore
$raw_data = file_get_contents("php://input"); // Post Josn Data
$request = json_decode($raw_data,true); // Decode Json Data


if ($request === FALSE || empty($request)) { 
    die("Data not Found!");
    exit();
} 

    // Data form WalletCore
    $address        = $request['address'];
    $recvied        = $request['amount'];
    $tnxid          = $request['tnxid']; 
    $IPNSECRET      = $request['IPNSECRET']; 
    $confirmations  = $request['confirmations']; 
    

    if ($confirmations < 3) {    
        die("Need Minimum 3 Confirmation");
        exit();
    }

    if ($IPNSECRET == $ipn_secret) {

        // Here do your code as you want now transction are valid and successfuly get 3 Confirmaton



    }else{
        die("IPN Secret Not Match");
        exit();
    } 
?>