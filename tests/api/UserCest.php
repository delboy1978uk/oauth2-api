<?php 

class UserCest
{
    /**
     * @param ApiTester $I
     * @throws Exception
     */
    public function tryToRegister(ApiTester $I)
    {
        // successful registration request
        $email = uniqid() . '@' . uniqid() . '.net';
        $I->sendPOST('/en_GB/user/register', [
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
    }

    /**
     * @param ApiTester $I
     * @throws Exception
     */
    public function tryToActivateWithWrongToken(ApiTester $I)
    {
        $email = uniqid() . '@' . uniqid() . '.net';
        $I->sendPOST('/en_GB/user/register', [
            'email' => $email,
            'password' => 'nothing',
            'confirm' => 'nothing'
        ]);
        $I->sendGET('/user/activate/' . $email . '/wrongtoken');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(404);
        $I->seeResponseMatchesJsonType([
            "success" => 'boolean',
            "error" => 'string',
        ]);
    }

    /**
     * @param ApiTester $I
     * @throws Exception
     */
    public function tryToResendActivationMail(ApiTester $I)
    {
        $email = uniqid() . '@' . uniqid() . '.net';
        $I->sendPOST('/en_GB/user/register', [
            'email' => $email,
            'password' => 'nothing',
            'confirm' => 'nothing'
        ]);
        $I->sendGET('/en_GB/user/activate/resend/' . $email);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            "token" => 'string',
        ]);
    }

    /**
     * @param ApiTester $I
     * @throws Exception
     */
    public function tryToResendActivationMailWithActivatedUser(ApiTester $I)
    {
        $I->sendGET('/en_GB/user/activate/resend/delboy1978uk@gmail.com');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            "error" => 'string',
        ]);
    }

    /**
     * @param ApiTester $I
     * @throws Exception
     */
    public function tryToActivate(ApiTester $I)
    {
        $email = uniqid() . '@' . uniqid() . '.net';
        $I->sendPOST('/en_GB/user/register', [
            'email' => $email,
            'password' => 'nothing',
            'confirm' => 'nothing'
        ]);
        $token = $I->grabDataFromResponseByJsonPath('$.token');
        $I->sendGET('/user/activate/' . $email . '/' . $token[0]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            "success" => 'boolean',
        ]);
    }

    /**
     * @param ApiTester $I
     * @throws Exception
     */
    public function tryToSendLostPasswordEmail(ApiTester $I)
    {
        $email = uniqid() . '@' . uniqid() . '.net';
        $I->sendPOST('/en_GB/user/register', [
            'email' => $email,
            'password' => 'nothing',
            'confirm' => 'nothing'
        ]);
        $token = $I->grabDataFromResponseByJsonPath('$.token');
        $I->sendGET('/user/activate/' . $email . '/' . $token[0]);
        $I->sendGET('/user/lost-password/' . $email );
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType([
            "token" => 'string',
        ]);
    }
}
