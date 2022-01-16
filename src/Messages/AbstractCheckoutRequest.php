<?php

namespace DigiTickets\Stripe\Messages;

use Omnipay\Common\Message\AbstractRequest;

abstract class AbstractCheckoutRequest extends AbstractRequest
{
    /**
     * Get the gateway API Key (the "secret key").
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->getParameter('apiKey');
    }

    /**
     * Set the gateway API Key.
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setApiKey($value): AbstractRequest
    {
        return $this->setParameter('apiKey', $value);
    }

    /**
     * Get webhook secret
     *
     * @return string
     */
    public function getWebhookSecretKey(): string
    {
        return $this->getParameter('webhookSecretKey');
    }

    /**
     * Set webhook secret
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setWebhookSecretKey($value): AbstractRequest
    {
        return $this->setParameter('webhookSecretKey', $value);
    }

    /**
     * Get custom params
     *
     * @return string
     */
    public function getMetadata(): array
    {
        return $this->getParameter('metadata');
    }

    /**
     * Set custom params
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setMetadata($value): AbstractRequest
    {
        return $this->setParameter('metadata', $value);
    }

    /**
     * Check the hash against the data.
     */
    public function isValid()
    {
        $payload = @file_get_contents('php://input');
        $sigHeader = $this->httpRequest->headers->get('stripe-signature');
        $endPointSecret = $this->getWebhookSecretKey();

//        try {
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sigHeader, $endPointSecret
        );
//        } catch(\UnexpectedValueException $e) {
//            // Invalid payload
////            http_response_code(400);
////            exit();
//            die($e->getMessage());
////            return false;
//        } catch(\Stripe\Exception\SignatureVerificationException $e) {
//            // Invalid signature
////            http_response_code(400);
////            exit();
//            die($e->getMessage());
////            return false;
//        }

        return true;
    }
}
