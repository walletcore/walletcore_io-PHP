<?php

require_once 'walletcore.inc.php';

$private_key = "YOUR  API Private Key";
$public_key = "YOUR  API Private Key"; 

$walletcore = new WalletCoreAPI();
$walletcore->Setup($private_key,$public_key);
 

try {

    $amount     = 0.001;
    $currency   = "BTC"; // Here you can user wallet allowed coin id as like LTC,DOGE,ETH,TRX,RDD
    $address    = "Your Bitcoin Receive address";
    $ipn_url    = "www.yourwebsite.com/ipn.php";


    $result = $walletcore->CreateWithdrawal($amount,$currency,$address,$ipn_url);
     if ($result['status'] == '200') {
         print_r($result);
     }else{
         print_r($result);
     }
} catch (Exception $e) {
   echo 'Message: ' .$e->getMessage();
}  

?>
