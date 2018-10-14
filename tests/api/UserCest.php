<?php 

class UserCest
{
    public function _before(ApiTester $I)
    {
    }

    public function tryToRegister(ApiTester $I)
    {
        $I->sendPOST('/user/register', [
            'email' => 'man@work.com',
            'password' => 'nothing',
            'confirm' => 'nothing'
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType([
          "id" => 'integer',
          "user" => [
                "id" => 'integer',
            "email" => "string",
            "person" => [
                "id" => 'integer',
            ],
            "password" => "string",
            "state" => 'integer',
            "registration_date" => "string:date",
          ],
          "expiry_date" => "string:date",
          "token" => "string",
        ]);
    }
}
