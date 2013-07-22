<?php

namespace PaynetEasy\PaynetEasyApi\Query;

use PaynetEasy\PaynetEasyApi\Transport\Response;
use PaynetEasy\PaynetEasyApi\PaymentData\Payment;
use PaynetEasy\PaynetEasyApi\PaymentData\BillingAddress;
use PaynetEasy\PaynetEasyApi\PaymentData\Customer;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-06-18 at 18:17:58.
 */
class FormQueryTest extends QueryTestPrototype
{
    /**
     * @var FormQuery
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new FormQuery('_');
    }

    public function testCreateRequestProvider()
    {
        return array(array
        (
            sha1
            (
                self::END_POINT .
                self::CLIENT_PAYMENT_ID .
                9910 .
               'vass.pupkin@example.com' .
                self::SIGNING_KEY
            )
        ));
    }

    public function testProcessResponseDeclinedProvider()
    {
        return array(array(array
        (
            'type'              => 'async-form-response',
            'status'            => 'filtered',
            'merchant-order-id' =>  self::CLIENT_PAYMENT_ID,
            'serial-number'     =>  md5(time()),
            'error-message'     => 'test filtered message',
            'error-code'        =>  8876
        )));
    }

    /**
     * @dataProvider testProcessResponseProcessingProvider
     */
    public function testProcessResponseProcessing(array $response)
    {
        $payment = $this->getPayment();

        $this->object->processResponse($payment, new Response($response));

        $this->assertPaymentStates($payment, Payment::STAGE_REDIRECTED, Payment::STATUS_PROCESSING);
        $this->assertFalse($payment->hasErrors());
    }

    public function testProcessResponseProcessingProvider()
    {
        return array(array(array
        (
            'type'              => 'async-form-response',
            'status'            => 'processing',
            'merchant-order-id' =>  self::CLIENT_PAYMENT_ID,
            'paynet-order-id'   =>  self::PAYNET_PAYMENT_ID,
            'serial-number'     =>  md5(time()),
            'redirect-url'      => 'http://redirect-url.com'
        )));
    }

    public function testProcessResponseErrorProvider()
    {
        return array(
        // Payment error after check
        array(array
        (
            'type'              => 'async-form-response',
            'status'            => 'error',
            'merchant-order-id' =>  self::CLIENT_PAYMENT_ID,
            'serial-number'     =>  md5(time()),
            'error-message'     => 'status error message',
            'error-code'        =>  2
        )),
        // Validation error
        array(array
        (
            'type'              => 'validation-error',
            'error-message'     => 'validation error message',
            'error-code'        =>  1
        )),
        // Immediate payment error
        array(array
        (
            'type'              => 'error',
            'error-message'     => 'immediate error message',
            'error-code'        =>  1
        )));
    }

    protected function getPayment()
    {
        return new Payment(array
        (
            'client_payment_id'     =>  self::CLIENT_PAYMENT_ID,
            'description'           => 'This is test payment',
            'amount'                =>  99.1,
            'currency'              => 'USD',
            'customer'              => new Customer(array
            (
                'first_name'            => 'Vasya',
                'last_name'             => 'Pupkin',
                'email'                 => 'vass.pupkin@example.com',
                'ip_address'            => '127.0.0.1',
                'birthday'              => '112681'
            )),
            'billing_address'       => new BillingAddress(array
            (
                'country'               => 'US',
                'state'                 => 'TX',
                'city'                  => 'Houston',
                'first_line'            => '2704 Colonial Drive',
                'zip_code'              => '1235',
                'phone'                 => '660-485-6353',
                'cell_phone'            => '660-485-6353'
            )),
            'query_config'          => $this->getConfig()
        ));
    }
}
