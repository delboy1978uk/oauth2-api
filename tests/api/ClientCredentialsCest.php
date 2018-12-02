<?php 

class ClientCredentialsCest
{
    const CLIENT_ID = 'ceac682a9a4808bf910ad49134230e0e';
    const CLIENT_SECRET = 'JDJ5JDEwJGNEd1J1VEdOY0YxS3QvL0pWQzMxay52';

    /**
     * @param ApiTester $I
     * @throws Exception
     */
    public function tryToGetAnAccessToken(ApiTester $I)
    {
        $I->wantTo('Call the /auth endpoint and get an access token using client_credentials grant');
        $I->sendPOST('/oauth2/access-token', [
            'grant_type' => 'client_credentials',
            'client_id' => self::CLIENT_ID,
            'client_secret' => self::CLIENT_SECRET,
            'scope' => 'admin',
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType([
            "token_type" => 'string',
            "expires_in" => "integer",
            "access_token" => "string",
        ]);
    }

    /**
     * @param ApiTester $I
     * @throws Exception
     */
    public function tryToGetAnAccessTokenWithBadDetails(ApiTester $I)
    {
        $I->wantTo('Call the /auth endpoint with a fake client id');
        $I->sendPOST('/oauth2/access-token', [
            'grant_type' => 'client_credentials',
            'client_id' => 'if this works then you get sacked',
            'client_secret' => 'seriously',
            'scope' => 'admin',
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(401);
        $I->seeResponseMatchesJsonType([
            "error" => 'string',
            "message" => "string",
        ]);
    }

    /**
     * @param ApiTester $I
     * @throws Exception
     */
    public function tryToGetAnAccessTokenWithWrongSecret(ApiTester $I)
    {
        $I->wantTo('Call the /auth endpoint with a bad secret');
        $I->sendPOST('/oauth2/access-token', [
            'grant_type' => 'client_credentials',
            'client_id' => self::CLIENT_ID,
            'client_secret' => 'oops',
            'scope' => 'admin',
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(401);
        $I->seeResponseMatchesJsonType([
            "error" => 'string',
            "message" => "string",
        ]);
    }

    /**
     * @param ApiTester $I
     * @throws Exception
     */
    public function tryToGetAnAccessTokenWithWrongScope(ApiTester $I)
    {
        $I->wantTo('Call the /auth endpoint with a strange scope');
        $I->sendPOST('/oauth2/access-token', [
            'grant_type' => 'client_credentials',
            'client_id' => self::CLIENT_ID,
            'client_secret' => self::CLIENT_SECRET,
            'scope' => 'superhuman_strength',
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(400);
        $I->seeResponseMatchesJsonType([
            "error" => 'string',
            "message" => "string",
        ]);
    }

    /**
     * @param ApiTester $I
     * @throws Exception
     */
    public function tryToGetAnAccessTokenOutwithScope(ApiTester $I)
    {
        $I->wantTo('Ask for a scope the client doesn\'t have');
        $I->sendPOST('/oauth2/access-token', [
            'grant_type' => 'client_credentials',
            'client_id' => self::CLIENT_ID,
            'client_secret' => self::CLIENT_SECRET,
            'scope' => 'test_scope',
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(403);
        $I->seeResponseMatchesJsonType([
            "error" => 'integer',
            "message" => "string",
        ]);
    }

    /**
     * @param ApiTester $I
     * @throws Exception
     */
    public function tryToGetAccessProtectedResources(ApiTester $I)
    {
        $I->wantTo('Call the /client endpoint and get a json list of clients');
        $I->sendPOST('/oauth2/access-token', [
            'grant_type' => 'client_credentials',
            'client_id' => self::CLIENT_ID,
            'client_secret' => self::CLIENT_SECRET,
            'scope' => 'admin',
        ]);
        $token = $I->grabDataFromResponseByJsonPath('$.access_token');
        $I->haveHttpHeader('Authorization', 'Bearer ' . $token[0]);
        $I->sendGET('/client');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
    }

    /**
     * @param ApiTester $I
     * @throws Exception
     */
    public function tryToGetAccessProtectedResourcesWithoutAToken(ApiTester $I)
    {
        $I->wantTo('Call the /client endpoint and get refused');
        $I->sendGET('/client');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(401);
    }


    public function tryToCallAPIWithClientCredentialsGrant(ApiTester $I)
    {
        $I->wantTo('Call the /website/client-credentials-example endpoint and get served');
        $I->sendGET('/website/client-credentials-example');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
    }


}
