<?php
namespace Nikapps\SamanUssd\Responses;

use Nikapps\SamanUssd\Contracts\SamanUssdResponse;

class CallSaleResponse implements SamanUssdResponse
{

    /**
     * Is successful?
     *
     * @var boolean
     */
    protected $successful;

    /**
     * Error/Failure reason
     *
     * @var string
     */
    protected $reason;

    /**
     * Provider id , when transaction is successful
     *
     * @var string
     */
    protected $providerId;


    /**
     * Make semi-colon separated string response
     *
     * @return string
     */
    public function make()
    {
        return $this->successful
            ? $this->makeSuccessfulResponse()
            : $this->makeFailedResponse();
    }

    /**
     * Make successful response
     *
     * @return string
     */
    public function makeSuccessfulResponse()
    {
        return implode(';', [
            intval($this->successful),
            $this->providerId
        ]);
    }

    /**
     * Make failed response
     *
     * @return string
     */
    public function makeFailedResponse()
    {
        return implode(';', [
            intval($this->successful),
            $this->reason
        ]);
    }


    /**
     * Set error reason
     *
     * @param string $reason
     * @return $this
     */
    public function reason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Alias of reason
     *
     * @see $this::reason()
     * @param string $error
     * @return $this
     */
    public function error($error)
    {
        return $this->reason($error);
    }

    /**
     * Set it's successful
     *
     * @return $this
     */
    public function successful()
    {
        $this->successful = true;

        return $this;
    }

    /**
     * Set it's failed
     *
     * @return $this
     */
    public function failed()
    {
        $this->successful = false;

        return $this;
    }


    /**
     * Set provider id
     *
     * @param string $providerId
     * @return $this
     */
    public function providerId($providerId)
    {
        $this->providerId = $providerId;

        return $this;
    }

    /**
     * Alias of providerId
     *
     * @see $this::providerId()
     * @param string $providerId
     * @return $this
     */
    public function id($providerId)
    {
        return $this->providerId($providerId);
    }

}