<?php
namespace Nikapps\SamanUssd\Soap;

use Nikapps\SamanUssd\Contracts\SamanSoapApi;
use Nikapps\SamanUssd\Contracts\SamanUssdListener;

class SamanSoapServer implements SamanSoapApi
{

    /**
     * @var SamanUssdListener
     */
    private $listener;
    /**
     * @var array
     */
    private $callbacks;

    /**
     * @param SamanUssdListener $listener
     * @param array $callbacks
     */
    function __construct(SamanUssdListener $listener = null, array $callbacks = [])
    {
        $this->listener = $listener;
        $this->callbacks = $callbacks;
    }

    /**
     * @param array $callbacks
     */
    public function setCallbacks($callbacks)
    {
        $this->callbacks = $callbacks;
    }

    /**
     * @param SamanUssdListener $listener
     */
    public function setListener($listener)
    {
        $this->listener = $listener;
    }

    /**
     * Get product info
     *
     * @WebMethod
     * @param string $productCode
     * @param string $languageCode
     * @return string $Result
     */
    public function GetProductInfo($productCode, $languageCode)
    {
        $codes = explode('*', $productCode);

        if (isset($this->callbacks['product_info'])) {

            return $this->callbacks['product_info']($codes, $languageCode)
                ->make();
        }

        return $this->listener->onProductInfo($codes, $languageCode)
            ->make();

    }

    /**
     * Notify sale provider
     *
     * @WebMethod
     * @param string $productCode
     * @param int $Amount
     * @param string $CellNumber
     * @param int $SEPId
     * @param string $languageCode
     * @return string $Result
     */
    public function CallSaleProvider(
        $productCode,
        $Amount,
        $CellNumber,
        $SEPId,
        $languageCode
    ) {
        $codes = explode('*', $productCode);

        if (isset($this->callbacks['call_sale'])) {
            return $this->callbacks['call_sale'](
                $codes,
                $Amount,
                $CellNumber,
                $SEPId,
                $languageCode
            )->make();
        }

        return $this->listener->onCallSale($codes,
            $Amount,
            $CellNumber,
            $SEPId,
            $languageCode
        )->make();

    }

    /**
     * Confirm payment
     *
     * @WebMethod
     * @param string $ProviderID
     * @return string $Result
     */
    public function ExecSaleProvider($ProviderID)
    {

        if (isset($this->callbacks['execute_sale'])) {
            return $this->callbacks['execute_sale']($ProviderID)->make();
        }

        return $this->listener->onExecuteSale($ProviderID)->make();
    }

    /**
     * Check status of transaction
     *
     * @WebMethod
     * @param string $ProviderID
     * @return string $Result
     */
    public function CheckStatus($ProviderID)
    {
        if (isset($this->callbacks['check_status'])) {

            return $this->callbacks['check_status']($ProviderID)->make();
        }

        return $this->listener->onCheckStatus($ProviderID)->make();
    }
}