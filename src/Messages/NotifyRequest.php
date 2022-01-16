<?php

namespace DigiTickets\Stripe\Messages;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\NotificationInterface;

/**
 * Capture webhook event checkout.session.completed data from stripe
 */
class NotifyRequest extends AbstractCheckoutRequest implements NotificationInterface
{
    protected $data;

    public function getData()
    {
//        if (isset($this->data)) {
//            return $this->data;
//        }

        $data = $this->httpRequest->toArray();

        return $this->data = $data;
    }

    /**
     * Send an acknowledgement that we have successfully got the data.
     * Here we would also check any hashes of the data sent and raise appropriate
     * exceptions if the data does not look right.
     */
    public function sendData($data)
    {
        return $this->createResponse($data);
    }

    /**
     * The response is a very simple message for returning an acknowledgement to Payone.
     */
    protected function createResponse($data)
    {
        return $this->response = new NotifyResponse($this, $data);
    }

    public function getTransactionStatus()
    {
        return ($this->data['data']['object']['payment_status'] === 'paid') ? static::STATUS_COMPLETED : static::STATUS_FAILED;
    }

    public function getMessage()
    {
        return null;
    }
}
