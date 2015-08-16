<?php
namespace Nikapps\SamanUssd\Contracts;

interface SamanSoapApi
{

    /**
     * Constructor
     *
     * @param SamanUssdListener $listener
     * @param array|\Closure[] $callbacks
     */
    function __construct(SamanUssdListener $listener = null, array $callbacks = []);

    /**
     * Get product info
     *
     * @param string $productCode
     * @param string $languageCode
     * @return string
     */
    public function GetProductInfo($productCode, $languageCode);

    /**
     * Notify sale provider
     *
     * @param string $productCode
     * @param integer $Amount
     * @param string $CellNumber
     * @param integer $SEPId
     * @param string $languageCode
     * @return string
     */
    public function CallSaleProvider(
        $productCode,
        $Amount,
        $CellNumber,
        $SEPId,
        $languageCode
    );

    /**
     * Confirm payment
     *
     * @param string $ProviderID
     * @return string
     */
    public function ExecSaleProvider($ProviderID);

    /**
     * Check status of transaction
     *
     * @param string $ProviderID
     * @return string
     */
    public function CheckStatus($ProviderID);
} 