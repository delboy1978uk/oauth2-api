<?php

$I = new AcceptanceTester($scenario);
$I->wantTo("Check homepage works");
$I->amOnPage('/');
$I->see('Bone MVC Framework');
