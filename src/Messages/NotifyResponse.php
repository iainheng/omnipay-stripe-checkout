<?php

namespace DigiTickets\Stripe\Messages;

/**
 * Acknowledge the incoming Transaction Status message.
 */

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\RequestInterface;

/**
 * @method AbstractCheckoutRequest getRequest()
 */
class NotifyResponse extends AbstractResponse
{
    protected $responseMessage = 'OK';

    /**
     * Whether to exit immediately on responding.
     */
    protected $exitOnResponse = true;

    /**
     * @var string|null
     */
    private $message = null;

    /**
     * @var bool
     */
    private $status = false;

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        try {
            if (!$this->getRequest()->isValid()) {
                $this->message = 'Invalid signature.';
                $this->status = false;
                return;
            } else {
                $this->message = 'Payment successful';
                $this->status = true;
            }
        } catch(\Exception $e) {
            $this->message = $e->getMessage();
            $this->status = false;
        }
    }

    /**
     * @param bool $exit
     */
    public function acknowledge($exit = true)
    {
        // Only send the OK message if the hash has been successfuly verified.
        if ($this->isSuccessful()) {
            echo $this->responseMessage;
        }

        if ($exit) {
            exit;
        }
    }

    public function isSuccessful()
    {
        return $this->status && $this->getRequest()->getTransactionStatus() === NotifyRequest::STATUS_COMPLETED;
    }

    public function accept()
    {
        $this->acknowledge($this->exitOnResponse);
    }

    public function reject()
    {
        // Don't output anything - just exit.
        if ($this->exitOnResponse) {
            exit;
        }
    }

    /**
     * Set or reset flag to exit immediately on responding.
     * Switch auto-exit off if you have further processing to do.
     *
     * @param boolean true to exit; false to not exit.
     */
    public function setExitOnResponse($value)
    {
        $this->exitOnResponse = (bool)$value;
    }

    /**
     * Alias of acknowledge as a more consistent OmniPay lexicon.
     */
    public function send($exit = true)
    {
        $this->acknowledge($exit);
    }

    public function getTransactionReference()
    {
        return $this->data['data']['object']['payment_intent'];
    }

    public function getMessage()
    {
        return $this->message;
    }
}
