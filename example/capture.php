<?php

use PaynetEasy\PaynetEasyApi\OrderData\Order;
use PaynetEasy\PaynetEasyApi\OrderProcessor;

require_once './common/autoload.php';
require_once './common/functions.php';

session_start();

/**
 * Если заказ был сохранен - получим его сохраненную версию, иначе создадим новый
 *
 * @see http://wiki.payneteasy.com/index.php/PnE:Preauth/Capture_Transactions#Capture_Request_Parameters
 * @see \PaynetEasy\PaynetEasyApi\Query\CaptureQuery::$requestFieldsDefinition
 * @see \PaynetEasy\PaynetEasyApi\OrderData\Order
 */
$order = $loadOrder() ?: new Order(array
(
    'client_orderid'            => 'CLIENT-112244',
    'paynet_order_id'           =>  1969596
));

/**
 * Создадим обработчик платежей и передадим ему URL для доступа к платежному шлюзу
 *
 * @see \PaynetEasy\PaynetEasyApi\Transport\GatewayClient::$gatewayUrl
 */
$orderProcessor = new OrderProcessor('https://payment.domain.com/paynet/api/v2/');

/**
 * Назначим обработчики для разных событий, происходящих при обработке платежа
 *
 * @see ./common/functions.php
 * @see PaynetEasy\PaynetEasyApi\OrderProcessor::executeWorkflow()
 * @see PaynetEasy\PaynetEasyApi\OrderProcessor::callHandler()
 */
$orderProcessor->setHandlers(array
(
    OrderProcessor::HANDLER_SAVE_ORDER          => $saveOrder,
    OrderProcessor::HANDLER_STATUS_UPDATE       => $displayWaitPage,
    OrderProcessor::HANDLER_SHOW_HTML           => $displayResponseHtml,
    OrderProcessor::HANDLER_FINISH_PROCESSING   => $displayEndedOrder
));

/**
 * Каждый вызов этого метода выполняет определенный запрос к API Paynet,
 * выбор запроса зависит от этапа обработки заказа
 *
 * @see \PaynetEasy\PaynetEasyApi\OrderData\Order::$processingStage
 * @see \PaynetEasy\PaynetEasyApi\OrderProcessor::executeWorkflow()
 * @see \PaynetEasy\PaynetEasyApi\Workflow\AbstractWorkflow::processOrder()
 */
$orderProcessor->executeWorkflow('capture', $getConfig(), $order, $_REQUEST);
