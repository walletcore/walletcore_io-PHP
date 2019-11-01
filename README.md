![alt text](https://walletcore.io/img/walletcore_logo.png "WalletCore.io")

# WalletCore.io PHP 
===================

**Current Release**: 1.0

PHP wrapper for [WalletCore.io](https://walletcore.io/) for use with [Dogecoin](http://dogecoin.com/), [Bitcoin](http://bitcoin.org/), and [Litecoin](http://litecoin.org). API key validation on instantiation, simple abstraction layer on top of existing API interfaces, and automatic JSON decoding on response.

### Usage

First, sign up for an account at [WalletCore.io](https://walletcore.io/) and take note of your API key under API Generator > API Keys > Generate New API key.

Download and include the walletcore.inc.php class:

```php
require_once 'path/to/walletcore.inc.php';
```

Instantiate the class and set your API key. If the API key is valid the set function will return true otherwise false.

```php
$private_key = "YOUR  API Private Key";
$public_key = "YOUR  API Private Key"; 

$walletcore = new WalletCoreAPI();
$walletcore->Setup($private_key,$public_key);

$ipn_url = "www.yourwebsite.com/ipn.php";

try {
    $result = $walletcore->GetCallbackAddress("BTC",$ipn_url);
     if ($result['status'] == '200') {
         print_r($result);
     }else{
         print_r($result);
     }
} catch (Exception $e) {
   echo 'Message: ' .$e->getMessage();
} 
```
Please see Example Work for details on available calls.