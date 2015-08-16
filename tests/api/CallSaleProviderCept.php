<?php

$I = new ApiTester($scenario);

$I->am('Saman IT employee!');
$I->wantTo('test CallSaleProvider method on this webservice');

$I->haveHttpHeader('Content-Type', 'text/xml');
$I->sendPOST(
    '/webservice.php',
    '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
    <Body>
        <CallSaleProvider xmlns="http://example.com/nikapps/samanussd/soap/samansoapserver">
            <productCode>12345*4444*123</productCode>
            <Amount>4444</Amount>
            <CellNumber>09123456789</CellNumber>
            <SEPId>4324642342342</SEPId>
            <languageCode>Fa</languageCode>
        </CallSaleProvider>
    </Body>
</Envelope>'
);


$I->seeResponseIsXml();
$I->seeResponseContains('<Result>1;p-123-123</Result>');

