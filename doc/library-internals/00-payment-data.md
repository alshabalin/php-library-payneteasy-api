# Классы для хранения и передачи данных

Семейство классов для хранения данных и обмена данными между библиотекой и CMS мерчанта. Расположены в пространстве имен **[PaynetEasy\PaynetEasyApi\PaymentData](../../source/PaynetEasy/PaynetEasyApi/PaymentData)**. Представлены следующими классами объектов:
* [Payment](#Payment)
* [Customer](#Customer)
* [BillingAddress](#BillingAddress)
* [CreditCard](#CreditCard)
* [RecurrentCard](#RecurrentCard)

Каждый из классов позволяет наполнять объект данными как с помощью массива, переданного в конструктор, так и с помощью сеттеров. При использовании массива в качестве ключа для данных можно использовать как название поля запроса так и underscored название свойства класса.

##### Использование массива и названий полей запроса:

```php
$payment = new Payment(array
(
    'client_orderid'    => 'CLIENT-112233',
    'orderid'           => 'PAYNET-112233',
    'order_desc'        => 'test payment'
));
```
##### Использование массива и underscored названий свойств класса

```php
$payment = new Payment(array
(
    'client_payment_id' => 'CLIENT-112233',
    'paynet_payment_id' => 'PAYNET-112233',
    'description'       => 'test payment'
));
```
##### Использование сеттеров

```php
$payment = (new Payment)
    ->setClientPaymentId('CLIENT-112233')
    ->setPaynetPaymentId('PAYNET-112233')
    ->setDescription('test payment')
;
```

### <a name="Payment"></a> Payment

Центральным объектом для хранения и передачи данных является объект класса **[PaynetEasy\PaynetEasyApi\PaymentData\Payment](../../source/PaynetEasy/PaynetEasyApi/PaymentData/Payment.php)**. Именно он передается из CMS в библиотеку при выполнении любого запроса. Хранит следующие данные:

Свойство класса     |Тип                            |Поле запроса   |Назначение
--------------------|-------------------------------|---------------|-------------------------------------------------------
clientPaymentId     |string                         |client_orderid |Merchant payment identifier
paynetPaymentId     |string                         |orderid        |Unique identifier of transaction assigned by PaynetEasy
description         |string                         |order_desc     |Brief payment description
destination         |string                         |destination    |Destination to where the payment goes
amount              |float                          |amount         |Amount to be charged
currency            |string                         |currency       |Three-letter currency code
siteUrl             |string                         |site_url       |URL the original payment is made from
comment             |string                         |comment        |A short comment for payment
processingStage     |string                         |               |Payment processing stage
status              |string                         |               |Payment status in bank
errors              |array                          |               |Payment processing errors
customer            |[Customer](#Customer)          |               |Payment customer
creditCard          |[CreditCard](#CreditCard)      |               |Payment credit card
recurrentCardFrom   |[RecurrentCard](#RecurrentCard)|               |Payment source recurrent card
recurrentCardTo     |[RecurrentCard](#RecurrentCard)|               |Payment destination recurrent card

### <a name="Customer"></a> Customer

Объект класса **[PaynetEasy\PaynetEasyApi\PaymentData\Customer](../../source/PaynetEasy/PaynetEasyApi/PaymentData/Customer.php)**. Используется при выполнении следующих запросов:
* sale
* preauth
* sale-form
* preauth-form
* transfer-form
* make-rebill
* transfer-by-ref

Объект хранит следующие данные:

Свойство класса     |Тип    |Поле запроса   |Назначение
--------------------|-------|---------------|-------------------------------------------------------
firstName           |string |first_name     |Customer’s first name
lastName            |string |last_name      |Customer’s last name
email               |string |email          |Customer’s email address
ipAddress           |string |ipaddress      |Customer’s IP address
birthday            |string |birthday       |Customer’s date of birth, in the format MMDDYY
ssn                 |string |ssn            |Last four digits of the customer’s social security number

### <a name="BillingAddress"></a> BillingAddress

Объект класса **[PaynetEasy\PaynetEasyApi\PaymentData\BillingAddress](../../source/PaynetEasy/PaynetEasyApi/PaymentData/BillingAddress.php)**. Используется при выполнении следующих запросов:
* sale
* preauth
* sale-form
* preauth-form
* transfer-form

Объект хранит следующие данные:

Свойство класса     |Тип    |Поле запроса   |Назначение
--------------------|-------|---------------|-------------------------------------------------------
country             |string |country        |Customer’s two-letter country code
state               |string |state          |Customer’s two-letter state code
city                |string |city           |Customer’s city
firstLine           |string |address1       |Customer’s address line 1
zipCode             |string |zip_code       |Customer’s ZIP code
phone               |string |phone          |Customer’s full international phone number, including country code
cellPhone           |string |cell_phone     |Customer’s full international cell phone number, including country code

### <a name="CreditCard"></a> CreditCard

Объект класса **[PaynetEasy\PaynetEasyApi\PaymentData\CreditCard](../../source/PaynetEasy/PaynetEasyApi/PaymentData/CreditCard.php)**. Используется при выполнении следующих запросов:
* sale
* preauth

Объект хранит следующие данные:

Свойство класса     |Тип    |Поле запроса       |Назначение
--------------------|-------|-------------------|-------------------------------------------------------
cvv2                |integer|cvv2               |RecurrentCard CVV2
cardPrintedName     |string |card_printed_name  |Card holder name
creditCardNumber    |string |credit_card_number |Credit card number
expireYear          |integer|expire_year        |Card expiration year
expireMonth         |integer|expire_month       |Card expiration month

### <a name="RecurrentCard"></a> RecurrentCard

Объект класса **[PaynetEasy\PaynetEasyApi\PaymentData\RecurrentCard](../../source/PaynetEasy/PaynetEasyApi/PaymentData/RecurrentCard.php)**. Используется при выполнении следующих запросов:
* create-card-ref
* get-card-info
* make-rebill
* transfer-by-ref

Объект хранит следующие данные:

Свойство класса     |Тип    |Поле запроса       |Назначение
--------------------|-------|-------------------|-------------------------------------------------------
cardReferenceId     |integer|cardrefid          |RecurrentCard referense ID
cvv2                |integer|cvv2               |RecurrentCard CVV2
cardPrintedName     |string |                   |Card holder name
expireYear          |integer|                   |Card expiration year
expireMonth         |integer|                   |Card expiration month
bin                 |integer|                   |Bank Identification Number
lastFourDigits      |integer|                   |The last four digits of PAN (card number)