# Omnipay - PowerTranz 2.4

**PowerTranz 2.4 Commerce gateway for the Omnipay PHP payment processing library**

![Packagist License](https://img.shields.io/packagist/l/cloudcogsio/omnipay-firstatlanticcommerce-gateway) ![Packagist Version](https://img.shields.io/packagist/v/cloudcogsio/omnipay-firstatlanticcommerce-gateway) ![Packagist PHP Version Support (specify version)](https://img.shields.io/packagist/php-v/cloudcogsio/omnipay-firstatlanticcommerce-gateway/dev-master) ![GitHub issues](https://img.shields.io/github/issues/cloudcogsio/omnipay-firstatlanticcommerce-gateway) ![GitHub last commit](https://img.shields.io/github/last-commit/cloudcogsio/omnipay-firstatlanticcommerce-gateway)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements PowerTranz 2.4 support for Omnipay.

## Installation
Via Composer

``` bash
$ composer require vincsis/omnipay-powertranz
```
## Gateway Operation Defaults
This gateway driver operates in 3DS mode by default and requires a callback URL to be provided via the '**setMerchantResponseURL**' method. The return URL must then implement the '**acceptNotification**' method to capture the transaction response from ,<b>PowerTranz<b>.

## Usage
For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay) repository.


### 3DS Transactions (Direct Integration)
'**MerchantResponseURL**' required. URL must be **https://**
``` php

use Omnipay\Omnipay;
try {
    $gateway = Omnipay::create('PowerTranz_PWT');
    $gateway
        ->setTestMode(true)  // false to use productions links  , true to use test links 
        ->setPWTId('xxxxxxxx') 
        ->setPWTPwd('xxxxxxxx')
        // **Required and must be https://
        ->setMerchantResponseURL('https://localhost/accept-notification.php')
        // *** Autogen an order number  UUID V4
        ->setOrderNumberAutoGen(true);

    $cardData = [
        'number' => '4111111111111111', //Mandatory
        'expiryMonth' => '01', //Mandatory
        'expiryYear' => '2025',  ///Mandatory
        'cvv' => '123',   //Mandatory
        'firstName' => 'Jonh', //Mandatory
        'LastName' => 'Doe',   //Mandatory
        'email' => "johDoe@gmail.com", //optional
        'Address1' => 'main Avenue', // optional
        'Address2' => 'Main Avenue', // optional
        'City' => 'Guatemala', // Mandatory
        'State' => 'GT',   //Mandatory
        'Postcode' => '',  //Optional
        'Country' => 'GTQ',   //Mandatory GTQ
        'Phone' => '',  // Optional
    ];

    $transactionData = [
        'card' => $cardData,
        'currency' => 'GTQ',  // Mandatory  GTQ
        'amount' => '1.00',   // Mandatory
        ///'TransactionId' => '2100001',  // is mandatory is setOrderNumberAutoGen is false
        "AddressMatch" => "false"   //Optional  
    ];

    $response = $gateway->authorize($transactionData)->send();

    if($response->isRedirect())
    {
	    // Redirect to continue 3DS verification
        $response->redirect();
    }
    else 
    {
	    // 3DS transaction failed setup, show error reason.
        echo $response->getMessage();
    }
} catch (Exception $e){
    $e->getMessage();
}
```
***accept-notification.php***
Accept transaction response from PowerTranz.
```php
$gateway  = Omnipay::create('PowerTranz_PWT');
$gateway    
    // Password is required to perform response signature verification
    ->setPWTId('xxxxxxxx')
    ->setPWTPwd('xxxxxxxx')
    
// Signature verification is performed implicitly once the gateway was initialized with the password.
$response = $gateway->acceptNotification($_POST)->send();

if($response->isSuccessful())
{       
    // authorize was succussful, continue purchase the payment    
     $paymentResponse = $gateway->purchase($response->getSpiToken())->send();
    
    //return a JSON with response    //Aproveed = true means payment successfull 
    print_r($paymentResponse->getData());
    
}
else 
{
    // Transaction failed
    echo $response->getMessage();
}
```

## Support

If you are having general issues with Omnipay, we suggest posting on [Stack Overflow](http://stackoverflow.com/). Be sure to add the [omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/edgarvicentesuc/PowerTranz.git/issues), or better yet, fork the library and submit a pull request.