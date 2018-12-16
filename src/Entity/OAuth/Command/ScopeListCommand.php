<?php

namespace OAuth\Command;

use Del\Service\UserService;
use OAuth\Client;
use OAuth\Repository\ScopeRepository;
use OAuth\Scope;
use OAuth\Service\ClientService;
use OAuth\OAuthUser as User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class ScopeListCommand
 * @package OAuth\Command
 */
class ScopeListCommand extends Command
{
    /**
     * @var ScopeRepository $scopeRepository
     */
    private $scopeRepository;

    public function __construct(ScopeRepository $scopeRepository, ?string $name = null)
    {
        $this->scopeRepository = $scopeRepository;
        parent::__construct($name);
    }

    /**
     * configure options
     */
    protected function configure()
    {
        $this->setName('scope:list');
        $this->setDescription('Lists all scopes.');
        $this->setHelp('Lists available access scopes.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Listing scopes...');
        $scopes = $this->scopeRepository->findAll();
        if (!count($scopes)) {
            $output->writeln('No scopes found.');
        }
        /** @var Scope $scope */
        foreach ($scopes as $scope) {
            $output->writeln(' - ' . $scope->getIdentifier());
        }
    }
}