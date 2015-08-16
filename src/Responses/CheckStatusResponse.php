<?php
namespace Nikapps\SamanUssd\Responses;

use Nikapps\SamanUssd\Contracts\SamanUssdResponse;

class CheckStatusResponse implements SamanUssdResponse
{

    /**
     * Is transaction found
     *
     * @var boolean
     */
    protected $found;

    /**
     * Is successful?
     *
     * @var boolean
     */
    protected $successful = false;


    /**
     * Make semi-colon separated string response
     *
     * @return string
     */
    public function make()
    {
        return implode(';', [
            intval($this->found),
            intval($this->successful)
        ]);
    }

    /**
     * Set transaction is found
     *
     * @return $this
     */
    public function found()
    {
        $this->found = true;

        return $this;
    }

    /**
     * Alias of found
     *
     * @see $this::found()
     * @return $this
     */
    public function successfulResult()
    {
        return $this->found();
    }


    /**
     * Set transaction is not found
     *
     * @return $this
     */
    public function notFound()
    {
        $this->found = false;

        return $this;
    }

    /**
     * Alias of notFound
     *
     * @see $this::notFound()
     * @return $this
     */
    public function failedResult()
    {
        return $this->notFound();
    }

    /**
     * Set it's successful transaction
     *
     * @return $this
     */
    public function successful()
    {
        $this->successful = true;

        return $this;
    }

    /**
     * Alias of successful
     *
     * @see $this::successful()
     * @return $this
     */
    public function successfulTransaction()
    {
        return $this->successful();
    }

    /**
     * Set it's failed transaction
     *
     * @return $this
     */
    public function failed()
    {
        $this->successful = false;

        return $this;
    }

    /**
     * Alias of failed
     *
     * @see $this::failed()
     * @return $this
     */
    public function failedTransaction()
    {
        return $this->failed();
    }

}