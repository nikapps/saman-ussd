<?php
namespace Nikapps\SamanUssd\Contracts;

use Nikapps\SamanUssd\Responses\CallSaleResponse;
use Nikapps\SamanUssd\Responses\CheckStatusResponse;
use Nikapps\SamanUssd\Responses\ExecuteSaleResponse;
use Nikapps\SamanUssd\Responses\ProductInfoResponse;

interface SamanUssdListener
{
    /**
     * When `GetProductInfo` is called on soap server
     *
     * @param array|string[] $codes
     * @param string $language
     * @return SamanUssdResponse|ProductInfoResponse;
     */
    public function onProductInfo(array $codes, $language);

    /**
     * When `CallSaleProvider` is called on soap server
     *
     * @param array|string[] $codes
     * @param integer $amount
     * @param string $phone Mobile/Call number
     * @param integer $sepId Unique number provided by saman724
     * @param string $language
     * @return SamanUssdResponse|CallSaleResponse
     */
    public function onCallSale(array $codes, $amount, $phone, $sepId, $language);

    /**
     * When `ExecSaleProvider` is called on soap server
     *
     * @param string $providerId
     * @return SamanUssdResponse|ExecuteSaleResponse
     */
    public function onExecuteSale($providerId);

    /**
     * When `CheckStatus` is called on soap server
     *
     * @param string $providerId
     * @return SamanUssdResponse|CheckStatusResponse
     */
    public function onCheckStatus($providerId);
}
