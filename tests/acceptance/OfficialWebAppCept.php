<?php

$I = new AcceptanceTester($scenario);
$I->wantTo("Check client_credentials client can call the API and fetch data");
$I->amOnPage('/website/client-credentials-example');
$I->seeResponseCodeIs(200);
$I->see('BoneMVC App');
$I->see('Web App');
$I->see('Android App');
