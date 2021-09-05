<?php

namespace App\Command;

use App\EmailAdapterCollection;
use App\EmailTransformerCollection;
use App\Entity\Email;
use App\Entity\EmailProvider;
use App\Repository\EmailProviderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EmailImportCommand extends Command
{
    protected static $defaultName = 'app:email:import';
    protected static $defaultDescription = 'Import emails';
    private EntityManagerInterface $em;
    private EmailAdapterCollection $adapters;
    private EmailTransformerCollection $transformers;

    public function __construct(
        EmailAdapterCollection $adapters,
        EmailTransformerCollection $transformers,
        EntityManagerInterface $em
    ) {
        $this->adapters = $adapters;
        $this->transformers = $transformers;
        $this->em = $em;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('batch-size', null, InputArgument::OPTIONAL, 'Maximum amount of items in RAM', 500)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $batchSize = $input->getOption('batch-size');

        /** @var EmailProviderRepository $providerRepo */
        $providerRepo = $this->em->getRepository(EmailProvider::class);
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

                $this->em->persist($email);

                if (0 === ($itemsAdded % $batchSize)) {
                    $this->em->flush();
                    $this->em->clear();
                }

                $itemsAdded++;
            }

            $provider->setExecutedAt(new \DateTime());
            $this->em->persist($provider);

            $this->em->flush();
            $this->em->clear();
        }

        $io->info('DONE!');
        return Command::SUCCESS;
    }
}
