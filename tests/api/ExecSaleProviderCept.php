<?php

$I = new ApiTester($scenario);

$I->am('Saman IT employee!');
$I->wantTo('test ExecSaleProvider method on this webservice');

$I->haveHttpHeader('Content-Type', 'text/xml');
$I->sendPOST(
    '/webservice.php',
    '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
    <Body>
        <ExecSaleProvider xmlns="http://example.com/nikapps/samanussd/soap/samansoapserver">
            <ProviderID>p-123-123</ProviderID>
        </ExecSaleProvider>
    </Body>
</Envelope>'
);


$I->seeResponseIsXml();
$I->seeResponseContains('<Result>1;Ok!</Result>');

