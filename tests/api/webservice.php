<?php

use Nikapps\SamanUssd\Responses\CallSaleResponse;
use Nikapps\SamanUssd\Responses\CheckStatusResponse;
use Nikapps\SamanUssd\Responses\ExecuteSaleResponse;
use Nikapps\SamanUssd\Responses\ProductInfoResponse;
use Nikapps\SamanUssd\SamanUssd;

require_once __DIR__ . '/../../vendor/autoload.php';

$samanUssd = new SamanUssd();

// Setup callbacks:
$samanUssd->onProductInfo(function (array $codes, $language) {
    return (new ProductInfoResponse)
        ->successful()
        ->amount(1000)
        ->description('Ok!');
});

$samanUssd->onCallSale(function (array $codes, $amount, $phone, $sepId, $language) {
    return (new CallSaleResponse)
        ->successful()
        ->providerId('p-123-123');
});

$samanUssd->onExecuteSale(function ($providerId) {
    return (new ExecuteSaleResponse)
        ->successful()
        ->description('Ok!');
});

$samanUssd->onCheckStatus(function ($providerId) {
    return (new CheckStatusResponse)
        ->found()
        ->successful();
});

$endpoint = 'http://web/tests/api/webservice.php';

$samanUssd->endpoint($endpoint);
$samanUssd->handle();