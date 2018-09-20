[obsolete] Saman USSD 
===================
**Obsolete package - payment with USSD is forbidden by Iranian central bank ([more info](http://khabaronline.ir/%28X%281%29S%28dja55emvz22zzic1fn5qleqc%29%29/detail/464534/Economy/macroeconomics))**

[![Travis (.org) branch](https://img.shields.io/travis/:user/:repo/:branch.svg?style=flat-square)](https://github.com/nikapps/saman-ussd/) [![Latest Stable Version](https://poser.pugx.org/nikapps/saman-ussd/v/stable)](https://packagist.org/packages/nikapps/saman-ussd) [![License](https://poser.pugx.org/nikapps/saman-ussd/license)](https://packagist.org/packages/nikapps/saman-ussd)
<img title="Saman *724*" alt="Saman *724*" src="http://www.724sep.ir/Content/image/mobile.png" height="200">

A php package for connecting to [Saman *724#](http://www.724sep.ir/) payment gateway.

**Table of contents:**

- [Installation](#installation)  
- [Usage](#usage)  
- [Listener](#listener)  
- [Responses](#responses)  
- [Customization](#customization)  
- [Testing](#testing)  
- [Dependencies](#dependencies)  
- [Official documentation](#official-documentation) 
- [Contribute](#contribute)
- [License](#license)
- [Donation](#donation)


## Installation

Using [Composer](https://getcomposer.org/), install this [package](https://packagist.org/packages/nikapps/saman-ussd) by running this command:

~~~
composer require nikapps/saman-ussd
~~~

## Usage

~~~php
<?php

use Nikapps\SamanUssd\SamanUssd;

$samanUssd = new SamanUssd();

// Set api endpoint
$samanUssd->endpoint('http://example.com/webservice.php');

// TODO: Set listener or callbacks

$samanUssd->handle();
~~~

## Listener
You need a listener for incoming soap calls. You have two options:

#### 1. Listener Class:
You can setup your listener by implementing interface `Nikapps\SamanUssd\Contracts\SamanUssdListener`:

~~~php
<?php

use Nikapps\SamanUssd\Contracts\SamanUssdListener;

class Listener implements SamanUssdListener{

    /**
     * When `GetProductInfo` is called
     *
     * @param string[] $codes
     * @param string $language
     * 
     * @return \Nikapps\SamanUssd\Responses\ProductInfoResponse;
     */
    public function onProductInfo(array $codes, $language)
    {
        // TODO: response
    }

    /**
     * When `CallSaleProvider` is called
     *
     * @param string[] $codes
     * @param integer $amount
     * @param string $phone Mobile/Call number
     * @param long $sepId Unique number provided by saman724
     * @param string $language
     * 
     * @return \Nikapps\SamanUssd\Responses\CallSaleResponse
     */
    public function onCallSale(array $codes, $amount, $phone, $sepId, $language)
    {
        // TODO: return response
    }

    /**
     * When `ExecSaleProvider` is called
     *
     * @param string $providerId
     * 
     * @return \Nikapps\SamanUssd\Responses\ExecuteSaleResponse
     */
    public function onExecuteSale($providerId)
    {
        // TODO: return response
    }

    /**
     * When `CheckStatus` is called
     *
     * @param string $providerId
     * 
     * @return \Nikapps\SamanUssd\Responses\CheckStatusResponse
     */
    public function onCheckStatus($providerId)
    {
        // TODO: return response
    }
}
~~~

Then set your listener:

~~~php
$samanUssd->setListener(new Listener());
~~~

#### 2. Callbacks:
Also you can pass a closure for each soap call:

~~~php
$samanUssd->onProductInfo(function (array $codes, $language) {
    
    // TODO: return response
    
});

$samanUssd->onCallSale(function (array $codes, $amount, $phone, $sepId, $language) {
    
    // TODO: return response

});

$samanUssd->onExecuteSale(function ($providerId) {
    
    // TODO: return response

});

$samanUssd->onCheckStatus(function ($providerId) {

    // TODO: return response

});
~~~

## Responses
For each api call, you should return its response object:


### onProductInfo
When method `GetProductInfo` is called on your soap server, `onProductInfo` will be called on your listener and you should return an instance of `Nikapps\SamanUssd\Responses\ProductInfoResponse`:

~~~php
public function onProductInfo(array $codes, $language)
{

    // TODO check codes

    return (new ProductInfoResponse)
        ->successful()
        ->amount(1000)
        ->description('Success!');
}
~~~

If the given codes are incorrect:

~~~php
return (new ProductInfoResponse())
    ->failed()
    ->reason('Failed!');
~~~

If you want to set `Terminal` and `Wage`:

~~~php
return (new ProductInfoResponse)
    ->successful()
    ->amount(1000)
    ->description('Success!')
    ->terminal(12345)
    ->wage(200);
~~~

- ***Notice*** : `description` and `amount`, together or `reason` should be less than or equal to `40` characters. 

#### Alias methods:

* `correct()` alias of `successful()`
* `incorrect()` alias of `failed()`
* `error($error)` alias of `reason($reason)`

---

### onCallSale
When method `CallSaleProvider` is called on your soap server, `onCallSale` will be called on your listener and you should return  an instance of `Nikapps\SamanUssd\Responses\CallSaleResponse`:

~~~php
public function onCallSale(array $codes, $amount, $phone, $sepId, $language)
{

    // Todo check sale

    return (new CallSaleResponse)
        ->successful()
        ->providerId('provider_id');
}
~~~

If something goes wrong:

~~~php
return (new CallSaleResponse)
    ->failed()
    ->reason('Failed!');
~~~

- ***Notice*** : `reason` should be less than or equal to `40` characters. 


#### Alias methods:

* `error($error)` alias of `reason($reason)`
* `id($providerId)` alias of `providerId($providerId)`

---

### onExecuteSale
When method `ExecSaleProvider` is called on your soap server, `onExecuteSale ` will be called on your listener and you should return  an instance of `Nikapps\SamanUssd\Responses\ExecuteSaleResponse`:

~~~php
public function onExecuteSale($providerId)
{
    return (new ExecuteSaleResponse)
        ->successful()
        ->description('Success!'); 
}
~~~

If something goes wrong:

~~~php
return (new ExecuteSaleResponse)
    ->failed()
    ->reason('Failed!');
~~~

- ***Notice*** : `description` or `reason` should be less than or equal to `40` characters. 


#### Alias method:

* `error($error)` alias of `reason($reason)`

---

### onCheckStatus
When method `CheckStatus` is called on your soap server, `onCheckStatus ` will be called on your listener and you should return  an instance of `Nikapps\SamanUssd\Responses\CheckStatusResponse`:

~~~php
public function onCheckStatus($providerId)
{
    // Todo check provider id

    return (new CheckStatusResponse)
        ->found()
        ->successful();
}
~~~

When `provider_id` is not found:

~~~php
return (new CheckStatusResponse)
    ->notFound()
    ->failed();
~~~

When `provider_id` is found, but transaction was failed:

~~~php
return (new CheckStatusResponse)
    ->found()
    ->failed();
}
~~~

#### Alias methods:

* `failedTransaction()` alias of `failed()`
* `successfulTransaction()` alias of `successful()`
* `failedResult()` alias of `notFound()`
* `successfulResult()` alias of `found()`

---

## Customization

#### 1. Set Namespace
If you want to set your custom xml namespace: 

~~~php
$samanUssd->setNamespace('http://my-web-site.com');
~~~

#### 2. Set custom soap options:

If you want to set custom soap options for [SoapServer](http://php.net/manual/en/soapserver.soapserver.php) or override default options:

~~~php
$samanUssd->setOptions([
    'soap_version' => SOAP_1_2
]);
~~~

#### 3. Set custom WSDL query string
By default, if you append `?wsdl` to your endpoint uri, you can see wsdl specification. If you want to set custom query string for that:

~~~php
$samanUssd->setWsdlQueryString('WSDL');
~~~

## Testing

#### Unit test

Run:

~~~
vendor/bin/phpspec run
~~~

#### Api test

Run:

~~~bash
docker-compose -f docker-compose.testing.yaml up -d

vendor/bin/codecept run
~~~

## Dependencies

- `php >= 7.1`
- `piotrooo/wsdl-creator`

Dev dependencies:

- `phpspec/phpspec`
- `codeception/codeception`

## Official documentation

Download: [Technical Documentation Version 1.8](https://dl.dropboxusercontent.com/u/29141199/saman/saman_ussd_724_documentation_v1.8.pdf)

## Contribute

Wanna contribute? simply fork this project and make a pull request!


## License
This project released under the [MIT License](http://opensource.org/licenses/mit-license.php).

```
/*
 * Copyright (C) 2015 NikApps Team.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * 1- The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * 2- THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */
```

## Donation

[![Donate via Paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=G3WRCRDXJD6A8)





