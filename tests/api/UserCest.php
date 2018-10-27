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
        // successful registration request
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

        // failed activation
        $I->sendGET('/user/activate/' . $email . '/wrongtoken');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(404);
        $I->seeResponseMatchesJsonType([
            "success" => 'boolean',
            "error" => 'string',
        ]);

        // resend activation
        $I->sendGET('/user/activate/resend/' . $email);
        $response = $I->grabResponse();
        var_dump($response); exit;
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            "token" => 'string',
        ]);
        $token = $I->grabDataFromResponseByJsonPath('$.token');

        // successful activation
        $I->sendGET('/user/activate/' . $email . '/' . $token[0]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            "success" => 'boolean',
        ]);

        //lost password email link
        $I->sendGET('/user/lost-password/' . $email );
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType([
            "token" => 'string',
        ]);

        // reset email
        $token = $I->grabDataFromResponseByJsonPath('$.token');
        $I->sendGET('/user/lost-password/' . $email );
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType([
            "token" => 'string',
        ]);
    }


}
