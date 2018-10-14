<?php 

class UserCest
{
    public function _before(ApiTester $I)
    {
    }

    /**
     * @param ApiTester $I
     * @throws Exception
     */
    public function tryToRegisterAndActivate(ApiTester $I)
    {
        $email = uniqid() . '@' . uniqid() . '.net';
        $I->sendPOST('/user/register', [
            'email' => $email,
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

        $I->sendGET('/user/activate/' . $email . '/wrongtoken');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(404);
        $I->seeResponseMatchesJsonType([
            "success" => 'boolean',
            "error" => 'string',
        ]);

        $token = $I->grabDataFromResponseByJsonPath('$.token');
        $I->sendGET('/user/activate/' . $email . '/' . $token[0]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType([
            "success" => 'boolean',
        ]);
    }
}
