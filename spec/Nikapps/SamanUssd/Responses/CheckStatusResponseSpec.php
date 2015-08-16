<?php

namespace spec\Nikapps\SamanUssd\Responses;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CheckStatusResponseSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Nikapps\SamanUssd\Responses\CheckStatusResponse');
    }

    function it_should_return_transaction_is_found_and_was_successful()
    {
        $this->found();
        $this->successful();

        $this->make()->shouldBe('1;1');
    }

    function it_should_return_transaction_is_not_found_and_its_failed()
    {
        $this->notFound();
        $this->failed();

        $this->make()->shouldBe('0;0');
    }

    function it_should_return_transaction_is_found_but_its_failed()
    {
        $this->found();
        $this->failed();

        $this->make()->shouldBe('1;0');
    }

}
