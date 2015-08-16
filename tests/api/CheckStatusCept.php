<?php

$I = new ApiTester($scenario);

$I->am('Saman IT employee!');
$I->wantTo('test CheckStatus method on this webservice');

$I->haveHttpHeader('Content-Type', 'text/xml');
$I->sendPOST(
    '/webservice.php',
    '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
    <Body>
        <CheckStatus xmlns="http://example.com/nikapps/samanussd/soap/samansoapserver">
            <ProviderID>p-123-123</ProviderID>
        </CheckStatus>
    </Body>
</Envelope>'
);


$I->seeResponseIsXml();
$I->seeResponseContains('<Result>1;1</Result>');

