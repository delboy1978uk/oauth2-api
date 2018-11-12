<?php

namespace OAuth\Command;

use Del\Service\UserService;
use OAuth\Client;
use OAuth\Service\ClientService;
use OAuth\OAuthUser as User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class ClientCommand
 * @package OAuth\Command
 */
class ClientCommand extends Command
{
    /**
     * @var ClientService $clientService
     */
    private $clientService;

    /**
     * @var UserService $userService
     */
    private $userService;

    public function __construct(ClientService $clientService, UserService $userService, ?string $name = 'client:create')
    {
        $this->clientService = $clientService;
        $this->userService = $userService;
        parent::__construct($name);
    }

    /**
     * configure options
     */
    protected function configure()
    {
        $this->setDescription('Creates a new client.');
        $this->setHelp('Create a new OAuth2 client application');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Bone API client creator');
        $helper = $this->getHelper('question');

        $question = new Question('Enter the email of the account: ', false);
        $email = $helper->ask($input, $output, $question);

        $this->userService->setUserClass(User::class);
        /** @var User $user */
        $user = $this->userService->findUserByEmail($email);

        if (!$user) {
            $output->writeln('User not found. Exiting.');
            return null;
        }

        $question = new Question('Give a name for this application: ', false);
        $name = $helper->ask($input, $output, $question);

        $question = new Question('Give a description: ', false);
        $description = $helper->ask($input, $output, $question);

        $question = new Question('Give an icon URL: ', false);
        $icon = $helper->ask($input, $output, $question);

        $question = new ChoiceQuestion('Select the Authorisation Grant type: ',[
            'authcode', 'implicit', 'password', 'clientcreds'
        ]);
        $authGrant = $helper->ask($input, $output, $question);

        $question = new Question('Give a redirect URI: ', '');
        $uri = $helper->ask($input, $output, $question);

        $question = new ConfirmationQuestion('Is this a phone app or JS client ? ', false);
        $public = $helper->ask($input, $output, $question);


        $client = new Client();
        $client->setName($name);
        $client->setDescription($description);
        $client->setIcon($icon);
        $client->setGrantType($authGrant);
        $client->setIdentifier(md5($name));
        $client->setRedirectUri($uri);
        $client->setConfidential($public);
        $client->setUser($user);

        if ($public === false) {
            $this->clientService->generateSecret($client);
        }

        $this->clientService->getClientRepository()->create($client);

        $output->writeln('Client created.');
    }
}