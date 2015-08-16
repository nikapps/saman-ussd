<?php
namespace Nikapps\SamanUssd\Responses;

use Nikapps\SamanUssd\Contracts\SamanUssdResponse;

class ExecuteSaleResponse implements SamanUssdResponse
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
     * Description, when transaction is successful
     *
     * @var string
     */
    protected $description;


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
            $this->description
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
     * Set description
     *
     * @param string $description
     * @return $this
     */
    public function description($description)
    {
        $this->description = $description;

        return $this;
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
}
