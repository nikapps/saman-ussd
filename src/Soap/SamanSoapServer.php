<?php
namespace Nikapps\SamanUssd\Soap;

use Nikapps\SamanUssd\Contracts\SamanSoapApi;
use Nikapps\SamanUssd\Contracts\SamanUssdListener;

/**
 * @WSDL\Annotation\WebService(
 *     name="ussd-webservice",
 *     targetNamespace="http://my-web-site.com",
 *     ns="http://my-web-site.com/types",
 *     location="http://127.0.0.1:8000/testing/test.php"
 * )
 * @WSDL\Annotation\BindingType SOAP_12
 */
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
    public function __construct(SamanUssdListener $listener = null, array $callbacks = [])
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
     * @WSDL\Annotation\WebMethod
     *
     * @WSDL\Annotation\WebParam(
     *     param="string $productCode"
     * )
     * @param string $productCode
     *
     * @WSDL\Annotation\WebParam(
     *      param="string $languageCode"
     * )
     * @param string $languageCode
     *
     * @WSDL\Annotation\WebResult(
     *     param="string $Result"
     * )
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
     * @WSDL\Annotation\WebMethod
     *
     * @WSDL\Annotation\WebParam(
     *      param="string $productCode"
     * )
     * @param string $productCode
     *
     * @WSDL\Annotation\WebParam(
     *      param="int $Amount"
     * )
     * @param int $Amount
     *
     * @WSDL\Annotation\WebParam(
     *      param="string $CellNumber"
     * )
     * @param string $CellNumber
     *
     * @WSDL\Annotation\WebParam(
     *      param="long $SEPId"
     * )
     * @param int $SEPId
     *
     * @WSDL\Annotation\WebParam(
     *      param="string $languageCode"
     * )
     * @param string $languageCode
     *
     * @WSDL\Annotation\WebResult(
     *     param="string $Result"
     * )
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
     * @WSDL\Annotation\WebMethod
     *
     * @WSDL\Annotation\WebParam(
     *      param="string $ProviderID"
     * )
     * @param string $ProviderID
     *
     * @WSDL\Annotation\WebResult(
     *     param="string $Result"
     * )
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
     * @WSDL\Annotation\WebMethod
     *
     * @WSDL\Annotation\WebParam(
     *      param="string $ProviderID"
     * )
     * @param string $ProviderID
     *
     * @WSDL\Annotation\WebResult(
     *     param="string $Result"
     * )
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
