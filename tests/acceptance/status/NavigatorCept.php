<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo("See if I can ping the server");
$I->amOnPage('/ping');
$I->seeResponseCodeIs(200);
$I->haveHttpHeader('Content-Type', 'application/json');
$I->see('pong');
