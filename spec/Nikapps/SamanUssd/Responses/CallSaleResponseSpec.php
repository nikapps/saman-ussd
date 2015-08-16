<?php

namespace spec\Nikapps\SamanUssd\Responses;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CallSaleResponseSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Nikapps\SamanUssd\Responses\CallSaleResponse');
    }

    function it_should_return_successful_response()
    {
        $this->successful();
        $this->providerId('p-123-123');

        $this->make()->shouldBe('1;p-123-123');
    }

    function it_should_return_failed_response()
    {
        $this->failed();
        $this->reason('Failed!');

        $this->make()->shouldBe('0;Failed!');
    }
}
