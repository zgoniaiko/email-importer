<?php

namespace App\Command;

use App\EmailAdapterCollection;
use App\EmailTransformerCollection;
use App\Entity\Email;
use App\Entity\EmailProvider;
use App\Repository\EmailProviderRepository;
use Doctrine\ORM\Tools\Console\Command\AbstractEntityManagerCommand;
use Doctrine\ORM\Tools\Console\EntityManagerProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EmailImportCommand extends AbstractEntityManagerCommand
{
    protected static $defaultName = 'app:email:import';
    protected static $defaultDescription = 'Import emails';
    private EmailAdapterCollection $adapters;
    private EmailTransformerCollection $transformers;

    public function __construct(
        EmailAdapterCollection $adapters,
        EmailTransformerCollection $transformers,
        ?EntityManagerProvider $entityManagerProvider = null
    ) {
        $this->adapters = $adapters;
        $this->transformers = $transformers;

        parent::__construct($entityManagerProvider);
    }

    protected function configure(): void
    {
        $this
            ->addOption('batch-size', null, InputArgument::OPTIONAL, 'Maximum amount of items in RAM', 500)
            ->addOption('em', null, InputOption::VALUE_REQUIRED, 'Name of the entity manager to operate on')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $batchSize = $input->getOption('batch-size');

        $em = $this->getEntityManager($input);

        /** @var EmailProviderRepository $providerRepo */
        $providerRepo = $em->getRepository(EmailProvider::class);
        $emailProviders = $providerRepo->getProviderQuery(new \DateTime());

        $itemsAdded = 0;
        foreach ($emailProviders->iterate() as $provider) {
            $platform = $provider->getType();
            $adapter = $this->adapters->getAdapter($platform);

            $items = $adapter->getEmails();
            foreach ($items as $item) {
                $data = $adapter->getEmailDetail($item['id']);
                /** @var Email $email */
                $email = $this->transformers->fromArray($platform, $data);
                $email->setEmailProvider($provider);

                $em->persist($email);

                if (0 === ($itemsAdded % $batchSize)) {
                    $em->flush();
                    $em->clear();
                }

                $itemsAdded++;
            }

            $provider->setExecutedAt(new \DateTime());
            $em->persist($provider);

            $em->flush();
            $em->clear();
        }

        $io->info('DONE!');
        return Command::SUCCESS;
    }
}
