<?php
namespace Nikapps\SamanUssd\Responses;

use Nikapps\SamanUssd\Contracts\SamanUssdResponse;

class ProductInfoResponse implements SamanUssdResponse
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
     * Amount
     *
     * @var integer
     */
    protected $amount;

    /**
     * Description, when transaction is successful
     *
     * @var string
     */
    protected $description;

    /**
     * Terminal [optional]
     *
     * @var integer
     */
    protected $terminal;

    /**
     * Wage [optional]
     *
     * @var integer
     */
    protected $wage;


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
        $response = [
            intval($this->successful),
            $this->amount,
            $this->description
        ];

        if (!is_null($this->terminal)) {
            $data[] = $this->terminal;
        }

        if (!is_null($this->wage)) {
            $data[] = $this->wage;
        }

        return implode(';', $response);
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
     * Set amount in rial
     *
     * @param integer $amount
     * @return $this
     */
    public function amount($amount)
    {
        $this->amount = $amount;

        return $this;
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
     * Alias of successful
     *
     * @see $this::successful()
     * @return $this
     */
    public function correct()
    {
        return $this->successful();
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
     * Alias of failed
     *
     * @see $this::failed()
     * @return $this
     */
    public function incorrect()
    {
        return $this->failed();
    }

    /**
     * Set terminal
     *
     * @param integer $terminal
     * @return $this
     */
    public function terminal($terminal)
    {
        $this->terminal = $terminal;

        return $this;
    }

    /**
     * Set wage
     *
     * @param integer $wage
     * @return $this
     */
    public function wage($wage)
    {
        $this->wage = $wage;

        return $this;
    }


}