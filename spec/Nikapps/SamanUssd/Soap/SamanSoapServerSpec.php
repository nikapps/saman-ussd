<?php

namespace spec\Nikapps\SamanUssd\Soap;

use Nikapps\SamanUssd\Contracts\SamanUssdListener;
use Nikapps\SamanUssd\Responses\CallSaleResponse;
use Nikapps\SamanUssd\Responses\CheckStatusResponse;
use Nikapps\SamanUssd\Responses\ExecuteSaleResponse;
use Nikapps\SamanUssd\Responses\ProductInfoResponse;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SamanSoapServerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Nikapps\SamanUssd\Soap\SamanSoapServer');
    }

    function it_should_call_method_product_info_on_given_listener(
        SamanUssdListener $listener,
        ProductInfoResponse $response
    ) {
        $this->setListener($listener);

        $response->make()->willReturn('1;12345;Success!');
        $listener->onProductInfo(['123456', '123123', '5555'], 'Fa')->willReturn($response);

        $this->GetProductInfo('123456*123123*5555', 'Fa')->shouldBe('1;12345;Success!');
    }

    function it_should_call_method_call_sale_on_given_listener(
        SamanUssdListener $listener,
        CallSaleResponse $response
    ) {
        $this->setListener($listener);

        $response->make()->willReturn('1;p-123-123');
        $listener->onCallSale(
            ['123456', '123123', '5555'],
            123123,
            '09123456789',
            'sep-id-123',
            'Fa'
        )->willReturn($response);

        $this->CallSaleProvider(
            '123456*123123*5555',
            123123,
            '09123456789',
            'sep-id-123',
            'Fa'
        )->shouldBe('1;p-123-123');
    }

    function it_should_call_method_exec_sale_on_given_listener(
        SamanUssdListener $listener,
        ExecuteSaleResponse $response
    ) {
        $this->setListener($listener);

        $response->make()->willReturn('1;Success!');
        $listener->onExecuteSale('p-123-123')->willReturn($response);

        $this->ExecSaleProvider('p-123-123')->shouldBe('1;Success!');
    }

    function it_should_call_method_check_status_on_given_listener(
        SamanUssdListener $listener,
        CheckStatusResponse $response
    ) {
        $this->setListener($listener);

        $response->make()->willReturn('1;1');
        $listener->onCheckStatus('p-123-123')->willReturn($response);

        $this->CheckStatus('p-123-123')->shouldBe('1;1');
    }

    function it_should_call_given_callback_for_method_get_product_info()
    {

        $this->setCallbacks([
            'product_info' => function () {
                return (new ProductInfoResponse)
                    ->successful()
                    ->amount(12345)
                    ->description('Success!');
            }
        ]);

        $this->GetProductInfo('123456*123123*5555', 'Fa')->shouldBe('1;12345;Success!');
    }

    function it_should_call_given_callback_for_method_call_sale_provider()
    {

        $this->setCallbacks([
            'call_sale' => function () {
                return (new CallSaleResponse)
                    ->successful()// failed()
                    ->providerId('p-123-123');
            }
        ]);

        $this->CallSaleProvider(
            '123456*123123*5555',
            123123,
            '09123456789',
            'sep-id-123',
            'Fa'
        )->shouldBe('1;p-123-123');
    }

    function it_should_call_given_callback_for_method_check_status()
    {

        $this->setCallbacks([
            'check_status' => function () {
                return (new CheckStatusResponse)
                    ->found()
                    ->successful();
            }
        ]);

        $this->CheckStatus('p-123-123')->shouldBe('1;1');
    }

    function it_should_call_given_callback_for_method_execute_sale_provider()
    {

        $this->setCallbacks([
            'execute_sale' => function () {
                return (new ExecuteSaleResponse)
                    ->successful()
                    ->description('Success!');
            }
        ]);

        $this->ExecSaleProvider('p-123-123')->shouldBe('1;Success!');
    }
}
