<?php

namespace PaynetEasy\Paynet\Queries;

use PaynetEasy\Paynet\Data\Order;
use PaynetEasy\Paynet\Data\Customer;
use PaynetEasy\Paynet\Data\CreditCard;
use PaynetEasy\Paynet\Data\RecurrentCard;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-06-12 at 15:13:43.
 */
class SaleQueryTest extends QueryTestPrototype
{
    const RECURRENT_CARD_ID = '5588943';

    /**
     * @var SaleQuery
     */
    protected $object;

    /**
     * @var \PaynetEasy\Paynet\Data\Order
     */
    protected $order;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new SaleQuery($this->getConfig());
    }

    /**
     * @dataProvider testCreateRequestProvider
     */
    public function testCreateRequest($controlCode, $cardType = null)
    {
        $order = $this->getOrder($cardType);

        $request = $this->object->createRequest($order);

        $this->assertInstanceOf('PaynetEasy\Paynet\Transport\Request', $request);
        $this->assertNotNull($request['control']);
        $this->assertEquals($controlCode, $request['control']);
        $this->assertFalse($order->hasErrors());
    }

    public function testCreateRequestProvider()
    {
        return array(
        // test for credit card
        array
        (
            sha1
            (
                self::END_POINT .
                self::CLIENT_ORDER_ID .
                '99' .                          // amount
                'vass.pupkin@example.com' .     // customer email
                self::SIGN_KEY
            ),
            'credit'
        ),
        // test for recurrent card
        array
        (
            sha1
            (
                self::END_POINT .
                self::CLIENT_ORDER_ID .
                '99' .                          // amount
                self::RECURRENT_CARD_ID .
                self::SIGN_KEY
            ),
            'recurrent'
        ));
    }

    public function testProcessResponseFilteredProvider()
    {
        return array(array(array
        (
            'type'              => 'async-response',
            'status'            => 'filtered',
            'paynet-order-id'   =>  self::PAYNET_ORDER_ID,
            'merchant-order-id' =>  self::CLIENT_ORDER_ID,
            'serial-number'     =>  md5(time()),
            'error-message'     => 'test filtered message',
            'error-code'        => '8876'
        )));
    }

    public function testProcessResponseProcessingProvider()
    {
        return array(array(array
        (
            'type'              => 'async-response',
            'status'            => 'processing',
            'paynet-order-id'   =>  self::PAYNET_ORDER_ID,
            'merchant-order-id' =>  self::CLIENT_ORDER_ID,
            'serial-number'     => md5(time())
        )));
    }

    public function testProcessResponseErrorProvider()
    {
        return array(array(
        array
        (
            'type'              => 'async-response',
            'status'            => 'error',
            'paynet-order-id'   =>  self::PAYNET_ORDER_ID,
            'merchant-order-id' =>  self::CLIENT_ORDER_ID,
            'serial-number'     =>  md5(time()),
            'error-message'     => 'test error message',
            'error-code'        => '2'
        ),
        array
        (
            'type'              => 'validation-error',
            'serial-number'     =>  md5(time()),
            'error-message'     => 'validation-error message',
            'error-code'        => '1000'
        ),
        array
        (
            'type'              => 'error',
            'error_message'     => 'test type error message',
            'error_code'        => '5'
        )));
    }

    protected function getOrder($cardType = null)
    {
        $order = new Order(array
        (
            'paynet_order_id'           =>  self::PAYNET_ORDER_ID,
            'order_code'                =>  self::CLIENT_ORDER_ID,
            'desc'                      => 'This is test order',
            'amount'                    =>  0.99,
            'currency'                  => 'USD',
            'ipaddress'                 => '127.0.0.1',
            'site_url'                  => 'http://example.com'
        ));

        $order->setCustomer(new Customer(array
        (
            'first_name'    => 'Vasya',
            'last_name'     => 'Pupkin',
            'email'         => 'vass.pupkin@example.com',
            'address'       => '2704 Colonial Drive',
            'birthday'      => '112681',
            'city'          => 'Houston',
            'state'         => 'TX',
            'zip_code'      => '1235',
            'country'       => 'US',
            'phone'         => '660-485-6353',
            'cell_phone'    => '660-485-6353'
        )));

        if ($cardType == 'credit')
        {
            $order->setCreditCard(new CreditCard(array
            (
                'card_printed_name'         => 'Vasya Pupkin',
                'credit_card_number'        => '4485 9408 2237 9130',
                'expire_month'              => '12',
                'expire_year'               => '14',
                'cvv2'                      => '084'
            )));
        }
        elseif ($cardType == 'recurrent')
        {
            $order->setRecurrentCard(new RecurrentCard(self::RECURRENT_CARD_ID));
        }

        return $order;
    }
}
