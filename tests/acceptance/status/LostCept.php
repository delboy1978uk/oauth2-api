<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo("ensure we get 404 messages");

$I->amOnPage('/shiver/me/timbers');

$I->seeResponseCodeIs(404);
$I->haveHttpHeader('Content-Type', 'application/json');
$I->see('Resource not found');

$I->amOnPage('/kraken');
$I->seeResponseCodeIs(404);
$I->haveHttpHeader('Content-Type', 'application/json');
$I->see('Resource not found');

$I->amOnPage('/davie/jones');
$I->seeResponseCodeIs(404);
$I->haveHttpHeader('Content-Type', 'application/json');
$I->see('Resource not found');