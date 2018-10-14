<?php

use Codeception\Util\HttpCode;

class LostCest
{

    public function tryToTest(ApiTester $I)
    {
        $I->sendGET('/where-am-i');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();


        $I->sendGET('/no-idea');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }
}
