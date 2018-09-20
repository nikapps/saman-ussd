<?php

use Nikapps\SamanUssd\Responses\CallSaleResponse;
use Nikapps\SamanUssd\Responses\CheckStatusResponse;
use Nikapps\SamanUssd\Responses\ExecuteSaleResponse;
use Nikapps\SamanUssd\Responses\ProductInfoResponse;
use Nikapps\SamanUssd\SamanUssd;

require_once __DIR__ . '/../vendor/autoload.php';

$samanUssd = new SamanUssd();

$samanUssd->onProductInfo(function (array $codes, $language) {
    return (new ProductInfoResponse)
        ->successful()// correct() // failed() // incorrect()
        ->amount(1000)// reason() // error()
        ->description('Ok!');
});

$samanUssd->onCallSale(function (array $codes, $amount, $phone, $sepId, $language) {
    return (new CallSaleResponse)
        ->successful()// failed()
        ->providerId('provider_id'); // id() // reason() // error()

});

$samanUssd->onExecuteSale(function ($providerId) {
    return (new ExecuteSaleResponse)
        ->successful()// failed()
        ->description('Ok!'); // reason(); error();
});

$samanUssd->onCheckStatus(function ($providerId) {

    return (new CheckStatusResponse)
        ->found()// notFound(); // successfulResult() // failedResult()
        ->successful(); //failed() // successfulTransaction() // failedTransaction()

});

$samanUssd->setName('ussd-webservice');
$samanUssd->SetNamespace('nikapps.dev');
$samanUssd->endpoint('http://127.0.0.1:8000/test.php');
$samanUssd->setWsdlQueryString('WSDL');
try {
    $samanUssd->handle();
} catch (Exception $e) {
    var_dump($e);
    return;
}

// php -S 127.0.0.1:8000
// curl "http://127.0.0.1:8000/test.php?WSDL"

