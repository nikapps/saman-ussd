<?php

namespace spec\Nikapps\SamanUssd\Responses;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProductInfoResponseSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Nikapps\SamanUssd\Responses\ProductInfoResponse');
    }

    function it_should_return_successful_response()
    {
        $this->successful();
        $this->amount(1000);
        $this->description('Product 1');
        $this->terminal(123456);
        $this->wage(2000);

        $this->make()->shouldBe('1;1000;Product 1;123456;2000');
    }

    function it_should_return_failed_response()
    {
        $this->failed();
        $this->reason('Error 1');

        $this->make()->shouldBe('0;Error 1');
    }

    function it_should_ignore_wage_and_terminal_if_terminal_is_not_provided()
    {
        $this->successful();
        $this->amount(1000);
        $this->description('Product 1');
        $this->wage(2000);

        $this->make()->shouldBe('1;1000;Product 1');
    }

    function it_should_ignore_wage_if_wage_is_not_provided()
    {
        $this->successful();
        $this->amount(1000);
        $this->description('Product 1');
        $this->terminal(123456);

        $this->make()->shouldBe('1;1000;Product 1;123456');
    }
}
