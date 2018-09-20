<?php

namespace Nikapps\SamanUssd;

use Nikapps\SamanUssd\Contracts\SamanUssdListener;
use SoapServer;
use WSDL\Builder\AnnotationWSDLBuilder;
use WSDL\Builder\WSDLBuilder;
use WSDL\WSDL;

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
     * @var string
     */
    protected $name = 'ussd-webservice';


    /**
     * Handle soap server
     * @throws \Exception
     */
    public function handle()
    {
        isset($_GET[$this->wsdlQueryString])
            ? $this->renderWsdl()
            : $this->setupSoapServer();
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
     * Set webservice name
     *
     * @param string $name
     * @return SamanUssd
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * @throws \WSDL\Builder\AnnotationBuilderException
     */
    protected function setupSoapServer()
    {
        $request = file_get_contents('php://input');

        $builder = $this->generateWsdlBuilder();

        $server = new SoapServer(
            $this->endpoint . '?' . $this->wsdlQueryString,
            array_merge([
                'uri' => $builder->getTargetNamespace(),
                'cache_wsdl' => WSDL_CACHE_NONE,
                'location' => $builder->getLocation(),
                'style' => $builder->getStyle(),
                'use' => $builder->getUse(),
                'soap_version' => $builder->getSoapVersion(),
            ], $this->options)
        );

        $server->setClass($this->soapApiClass, $this->listener, $this->callbacks);
        $server->handle($request);
    }

    /**
     * Generate wsdl
     *
     * @return string wsdl in xml format
     * @throws \WSDL\Builder\AnnotationBuilderException
     */
    public function wsdl()
    {
        return WSDL::fromBuilder($this->generateWsdlBuilder())->create();
    }

    /**
     * Render WSDL
     * @throws \WSDL\Builder\AnnotationBuilderException
     */
    protected function renderWsdl()
    {
        echo $this->wsdl();
    }

    /**
     * Generate wsdl builder
     *
     * @return WSDLBuilder
     * @throws \WSDL\Builder\AnnotationBuilderException
     */
    protected function generateWsdlBuilder() {
        return (new AnnotationWSDLBuilder($this->soapApiClass))
            ->build()
            ->getBuilder()
            ->setLocation($this->endpoint)
            ->setTargetNamespace($this->namespace)
            ->setNs($this->namespace . '/types')
            ->setName($this->name);
    }
}
