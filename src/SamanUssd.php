<?php
namespace Nikapps\SamanUssd;

use Nikapps\SamanUssd\Contracts\SamanSoapApi;
use Nikapps\SamanUssd\Contracts\SamanUssdListener;
use Nikapps\SamanUssd\Soap\SamanSoapServer;
use SoapServer;
use WSDL\WSDLCreator;

class SamanUssd
{
    /**
     * @var string
     */
    protected $soapApiClass = '\Nikapps\SamanUssd\Soap\SamanSoapServer';

    /**
     * @var string
     */
    protected $endpoint = 'http://example.com/webservice';

    /**
     * @var string
     */
    protected $namespace = 'http://example.com';

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
        $wsdl = new WSDLCreator($this->soapApiClass, $this->endpoint);
        $wsdl->setNamespace($this->namespace);

        isset($_GET[$this->wsdlQueryString])
            ? $wsdl->renderWSDL()
            : $this->setupSoapServer($wsdl);
    }

    /**
     * Set soap api class
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
     * Set api endpoint
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
     * Set namespace
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
     * Setup soap server
     *
     * @param WSDLCreator $wsdl
     */
    protected function setupSoapServer(WSDLCreator $wsdl)
    {
        $request = file_get_contents('php://input');

        $server = new SoapServer(
            $this->endpoint . '?' . $this->wsdlQueryString,
            array_merge([
                'uri'        => $wsdl->getNamespaceWithSanitizedClass(),
                'cache_wsdl' => WSDL_CACHE_NONE,
                'location'   => $wsdl->getLocation(),
                'style'      => SOAP_RPC,
                'use'        => SOAP_LITERAL
            ], $this->options)
        );

        $server->setClass($this->soapApiClass, $this->listener, $this->callbacks);
        $server->handle($request);
    }
}
