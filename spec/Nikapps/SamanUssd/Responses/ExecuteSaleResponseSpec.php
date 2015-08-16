<?php

namespace spec\Nikapps\SamanUssd\Responses;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExecuteSaleResponseSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Nikapps\SamanUssd\Responses\ExecuteSaleResponse');
    }

    function it_should_return_successful_response()
    {
        $this->successful();
        $this->description('Success!');

        $this->make()->shouldBe('1;Success!');
    }

    function it_should_return_failed_response()
    {
        $this->failed();
        $this->reason('Failed!');

        $this->make()->shouldBe('0;Failed!');
    }

}
