<?php
namespace Nikapps\SamanUssd\Contracts;

interface SamanUssdResponse
{

    /**
     * Make semi-colon separated string response
     *
     * @return string
     */
    public function make();
} 