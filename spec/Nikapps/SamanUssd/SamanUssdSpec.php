<?php

namespace spec\Nikapps\SamanUssd;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SamanUssdSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Nikapps\SamanUssd\SamanUssd');
    }

}
