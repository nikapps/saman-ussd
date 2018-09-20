<?php
namespace Nikapps\SamanUssd;

use Nikapps\SamanUssd\Contracts\SamanSoapApi;
use Nikapps\SamanUssd\Contracts\SamanUssdListener;
use Nikapps\SamanUssd\Soap\SamanSoapServer;
use SoapServer;
use WSDL\Annotation\BindingType;
use WSDL\Annotation\SoapBinding;
use WSDL\Builder\AnnotationWSDLBuilder;
use WSDL\Builder\Method;
use WSDL\Builder\Parameter;
use WSDL\Builder\WSDLBuilder;
use WSDL\Lexer\Tokenizer;
use WSDL\WSDL;

class SamanUssd
{
    /**
     * If disabled, annotations in soapApiClass are ignored.
     * @var bool
     */
    public $useAnnotions = false;

    /**
     * @var string
     */
    protected $soapApiClass = '\Nikapps\SamanUssd\Soap\SamanSoapServer';

    /**
     * Ignored if useAnnotions = true
     * @var string
     */
    protected $endpoint = 'http://example.com/webservice';

    /**
     * Ignored if useAnnotions = true
     * @var string
     */
    protected $namespace = 'http://example.com';

    /**
     * Ignored if useAnnotions = true
     * @var string
     */
    protected $targetNamespace = 'http://example.com/types';

    /**
     * Ignored if useAnnotions = true
     * @var string
     */
    protected $name = 'saman-ussd';

    /**
     * @var string
     */
    protected $wsdlQueryString = 'wsdl';

    /**
     * Soap options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Set callbacks
     *
     * @var array
     */
    protected $callbacks = [];

    /**
     * @var SamanUssdListener
     */
    protected $listener;


    /**
     * Handle soap server
     */
    public function handle()
    {

        if ($this->useAnnotions) {
            $builder =(new AnnotationWSDLBuilder($this->soapApiClass))->build()->getBuilder();
        } else {
            $builder = WSDLBuilder::instance()
                ->setName($this->name)
                ->setTargetNamespace($this->targetNamespace)
                ->setNs($this->namespace)
                ->setLocation($this->endpoint)
                ->setStyle(SoapBinding::RPC)
                ->setUse(SoapBinding::LITERAL)
                ->setSoapVersion(BindingType::SOAP_11)
                ->setMethods($this->getMethods());
        }

        $wsdl = WSDL::fromBuilder($builder);

        if (isset($_GET[$this->wsdlQueryString]))
        {
            echo $wsdl->create();
        }  else
        {
            $this->setupSoapServer(
                $builder->getNs() . strtolower(ltrim(str_replace('\\', '/', $this->soapApiClass), '/')),
                $builder->getLocation()
            );
        }
    }

    /**
     * Method definion (if not using annotations)
     *
     * @return array
     * @throws \Exception
     */
    private function getMethods() {
        $tokenizer = new Tokenizer();

        $meths=[];
        /**
         * public function GetProductInfo
         */
        $parameters1 = [
            Parameter::fromTokens($tokenizer->lex('string $productCode')),
            Parameter::fromTokens($tokenizer->lex('string $languageCode'))
        ];
        $return1 = Parameter::fromTokens($tokenizer->lex('string $Result'));
        $meths[] = new Method('GetProductInfo', $parameters1, $return1);
        /**
         * public function CallSaleProvider
         *
         */

        $parameters2 = [
            Parameter::fromTokens($tokenizer->lex('string $productCode')),
            Parameter::fromTokens($tokenizer->lex('int $Amount')),
            Parameter::fromTokens($tokenizer->lex('string $CellNumber')),
            Parameter::fromTokens($tokenizer->lex('long $SEPId')),
            Parameter::fromTokens($tokenizer->lex('string $languageCode'))
        ];
        $return2 = Parameter::fromTokens($tokenizer->lex('string $Result'));
        $meths[] = new Method('CallSaleProvider', $parameters2, $return2);
        /*
         * public function ExecSaleProvider
         */

        $parameters3 = [Parameter::fromTokens($tokenizer->lex('string $ProviderID'))];
        $return3 = Parameter::fromTokens($tokenizer->lex('string $Result'));
        $meths[] = new Method('ExecSaleProvider', $parameters3, $return3);

        /**
         * public function CheckStatus
         */
         $parameters4 = [Parameter::fromTokens($tokenizer->lex('string $ProviderID'))];
         $return4 = Parameter::fromTokens($tokenizer->lex('string $Result'));
         $meths[] = new Method('CheckStatus', $parameters4, $return4);

        return $meths;
    }
    /**
     * Set soap api class.
     *
     * IF $this->useAnnotations = true, THEN annotations in $soapApiClass WILL override
     * $this->name, $this->targetNamespace, $this->namespace and $this->endpoint.
     *
     * To ignore annotation in $soapApiClass, set $this->useAnnotations = false.
     *
     * @param string $soapApiClass
     * @return $this
     */
    public function setSoapApiClass($soapApiClass)
    {
        $this->soapApiClass = $soapApiClass;

        return $this;
    }

    /**
     * Set api endpoint (if not using annotations)
     *
     * @param string $endpoint
     * @return $this
     */
    public function endpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * Set namespace (if not using annotations)
     *
     * @param string $namespace
     * @return $this
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * Set target-namespace (if not using annotations)
     *
     * @param string $namespace
     * @return $this
     */
    public function setTargetNamespace($namespace)
    {
        $this->targetNamespace = $namespace;

        return $this;
    }


    /**
     * Set wsdl query string
     *
     * @param string $wsdlQueryString
     * @return $this
     */
    public function setWsdlQueryString($wsdlQueryString)
    {
        $this->wsdlQueryString = $wsdlQueryString;

        return $this;
    }

    /**
     * Set soap options
     *
     * @param array $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param SamanUssdListener $listener
     * @return $this
     */
    public function setListener($listener)
    {
        $this->listener = $listener;

        return $this;
    }

    /**
     * Set onProductInfo callback
     *
     * @param callable $callback
     * @return $this
     */
    public function onProductInfo(\Closure $callback)
    {
        $this->callbacks['product_info'] = $callback;

        return $this;
    }

    /**
     * Set onCallSale callback
     *
     * @param callable $callback
     * @return $this
     */
    public function onCallSale(\Closure $callback)
    {
        $this->callbacks['call_sale'] = $callback;

        return $this;
    }

    /**
     * Set onExecuteSale callback
     *
     * @param callable $callback
     * @return $this
     */
    public function onExecuteSale(\Closure $callback)
    {
        $this->callbacks['execute_sale'] = $callback;

        return $this;
    }

    /**
     * Set onCheckStatus callback
     *
     * @param callable $callback
     * @return $this
     */
    public function onCheckStatus(\Closure $callback)
    {
        $this->callbacks['check_status'] = $callback;

        return $this;
    }

    /**
     * @param string $uri
     * @param string $location
     */
    protected function setupSoapServer(string $uri, string $location)
    {
        $request = file_get_contents('php://input');

        $server = new SoapServer(
            $location . '?' . $this->wsdlQueryString,
            array_merge([
                'uri'        => $uri,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'location'   => $location,
                'style'      => SOAP_RPC,
                'use'        => SOAP_LITERAL
            ], $this->options)
        );

        $server->setClass($this->soapApiClass, $this->listener, $this->callbacks);
        $server->handle($request);
    }
}
