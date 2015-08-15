<?php
namespace Nikapps\SamanUssd\Contracts;

interface SamanUssdListener
{

    /**
     * @param array $codes
     * @param $language
     * @return mixed
     */
    public function onProductInfo(array $codes, $language);

    public function onCallSale(array $codes, $amount, $phone, $sepId, $language);

    public function onExecuteSale($providerId);

    public function onCheckStatus($providerId);
} 