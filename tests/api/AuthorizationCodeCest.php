<?php 

class AuthorizationCodeCest
{
    const CLIENT_ID = '83c44d2a7b80fff51591478a4936fa7d';
    const REDIRECT_URI = 'https://awesome.scot/fake-client-callback';

    /**
     * @param ApiTester $I
     * @throws Exception
     */
    public function tryToGetAnAccessTokenWithAnAuthCodeGrant(ApiTester $I)
    {
        $I->wantTo('Call the /auth endpoint and get an access token using authorization code grant');
        $I->sendPOST('/oauth2/auth', [
            'grant_type' => 'code',
            'client_id' => self::CLIENT_ID,
            'redirect_uri' => self::REDIRECT_URI,
            'scope' => 'test_scope',
            'state' => 'xyz',
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType([
            "code" => 'string',
            "state" => "integer",
        ]);
        $code = $I->grabDataFromResponseByJsonPath('$.code');

        $I->sendPOST('/oauth2/access-token', [
            'grant_type' => 'authorization_code',
            'client_id' => self::CLIENT_ID,
            'redirect_uri' => self::REDIRECT_URI,
            'scope' => 'test_scope',
            'code' => $code[0],
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType([
            "token_type" => 'string',
            "expires_in" => "integer",
            "access_token" => "string",
            "refresh_token" => "string",
        ]);
    }

}
