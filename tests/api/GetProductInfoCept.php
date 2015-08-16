<?php

$I = new ApiTester($scenario);

$I->am('Saman IT employee!');
$I->wantTo('test GetProductInfo method on this webservice');

$I->haveHttpHeader('Content-Type', 'text/xml');
$I->sendPOST(
    '/webservice.php',
    '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
    <Body>
        <GetProductInfo xmlns="http://example.com/nikapps/samanussd/soap/samansoapserver">
            <productCode>123213</productCode>
            <languageCode>Fa</languageCode>
        </GetProductInfo>
    </Body>
</Envelope>'
);


$I->seeResponseIsXml();
$I->seeResponseContains('<Result>1;1000;Ok!</Result>');

