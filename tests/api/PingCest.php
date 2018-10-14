<?php

use Codeception\Util\HttpCode;

class PingCest
{
    const DATETIME_REGEX = 'string:regex(#\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}#)';

    public function tryToPingEndPoint(ApiTester $I)
    {
        $I->sendGET('/ping');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(['pong' => self::DATETIME_REGEX]);
    }
}
