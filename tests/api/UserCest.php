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
        $I->sendGET('/en_GB/user/activate/' . $email . '/wrongtoken');
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
        $I->sendGET('/en_GB/user/activate/' . $email . '/' . $token[0]);
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
    public function tryToActivateWithExpiredToken(ApiTester $I)
    {
        $email = uniqid() . '@' . uniqid() . '.net';
        $I->sendPOST('/en_GB/user/register', [
            'email' => $email,
            'password' => 'nothing',
            'confirm' => 'nothing'
        ]);
        $token = $I->grabDataFromResponseByJsonPath('$.token');

        $env = new \Bone\Server\Environment([]);
        $config = $env->fetchConfig( 'config', getenv('APPLICATION_ENV'));

        $dbname = $config['db']['database'];
        $user = $config['db']['user'];
        $pass = $config['db']['pass'];
        $host = $config['db']['host'];

        $credentials = new \Del\Common\Config\DbCredentials();
        $credentials->setUser($user)
            ->setPassword($pass)
            ->setDatabase($dbname)
            ->setHost($host);

        $svc = \Del\Common\ContainerService::getInstance();
        $svc->setDbCredentials($credentials);
        $svc->setProxyPath(realpath('data/proxies'));

        $package = new \OAuth\OAuthPackage();
        $svc->registerToContainer($package);

        $container = $svc->getContainer();
        $em = $container['doctrine.entity_manager'];
        /** @var \Del\Repository\EmailLink $repository */
        $repository = $em->getRepository('Del\Entity\EmailLink');
        /** @var \Del\Entity\EmailLink $link */
        $link = $repository->findOneBy(['token' => $token[0]]);
        $link->setExpiryDate(DateTime::createFromFormat('Y-m-d', '2000-01-01'));
        $em->flush($link);

        $I->sendGET('/en_GB/user/activate/' . $email . '/' . $token[0]);
        $I->seeResponseCodeIs(403);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'error' => 'string',
        ]);
    }



    /**
     * @param ApiTester $I
     * @throws Exception
     */
    public function tryToActivateWithNonsenseToken(ApiTester $I)
    {
        $I->sendGET('/en_GB/user/activate/delboy1978uk@gmail.com/ifthisworksitsamiracle');
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
    }



    /**
     * @param ApiTester $I
     * @throws Exception
     */
    public function tryToActivateWithBullshitNonRegisteredUser(ApiTester $I)
    {
        $I->sendGET('/en_GB/user/activate/bullshit@user.com/ifthisworksitsamiracle');
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
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
        $I->sendGET('/en_GB/user/activate/' . $email . '/' . $token[0]);
        $I->sendGET('/user/lost-password/' . $email );
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType([
            'token' => 'string',
        ]);
    }


    public function tryToGetUser(ApiTester $I)
    {
        $I->sendGET('/user/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'email' => 'string',
            'password' => 'string',
        ]);
    }
}
