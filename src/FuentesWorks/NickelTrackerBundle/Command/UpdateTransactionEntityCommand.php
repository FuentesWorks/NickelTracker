<?php

namespace FuentesWorks\NickelTrackerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use FuentesWorks\NickelTrackerBundle\Entity\Transaction;


class UpdateTransactionEntityCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('nickeltracker:update:transactions')
            ->setDescription("Updates Transaction entity types (from *logs to transaction");
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        # Initialize Doctrine
        $doctrine = $this->getContainer()->get('doctrine');
        /* @var \Doctrine\ORM\EntityManager $em */
        $em = $doctrine->getManager();

        ## TRANSACTION LOGS ##
        // Load all available TransactionLogs
        $output->write("Loading previous transaction logs.. ");
        $transactionLogs = $doctrine->getRepository('FuentesWorksNickelTrackerBundle:TransactionLog')
            ->findAll();
        $output->writeln("[OK]");

        // Loop through all of them
        foreach($transactionLogs as $log)
        {
            /** @var TransactionLog $log */
            $output->writeln(" -> Processing Transaction Log: " . $log->getGlobalId());

            $t = new Transaction();
            $t->setDate( $log->getDate() );
            $t->setType( $log->getType() );
            $t->setAmount( $log->getAmount() );
            $t->setDescription( $log->getDescription() );
            $t->setDetails( $log->getDetails() );
            $t->setSourceAccountId( $log->getAccountId() );
            $t->setDestinationAccountId( null );
            $t->setCategoryId( $log->getCategoryId() );

            $em->persist($t);
        }

        // Save all new transactions
        $output->write("Flushing new transactions.. ");
        $em->flush();
        $output->writeln("[OK]");

        ## TRANSFER LOGS ##
        // Load all available TransactionLogs
        $output->write("Loading previous transfer logs.. ");
        $transferLogs = $doctrine->getRepository('FuentesWorksNickelTrackerBundle:TransferLog')
            ->findAll();
        $output->writeln("[OK]");

        // Loop through all of them
        foreach($transferLogs as $log)
        {
            /** @var TransferLog $log */
            $output->writeln(" -> Processing Transfer Log: " . $log->getGlobalId());

            $t = new Transaction();
            $t->setDate( $log->getDate() );
            $t->setType( $log->getType() );
            $t->setAmount( $log->getAmount() );
            $t->setDescription( $log->getDescription() );
            $t->setDetails( $log->getDetails() );
            $t->setSourceAccountId( $log->getSourceId() );
            $t->setDestinationAccountId( $log->getDestinationId() );
            $t->setCategoryId( null );

            $em->persist($t);
        }

        // Save all new transactions
        $output->write("Flushing new transactions.. ");
        $em->flush();
        $output->writeln("[OK]");


        $output->writeln("[All done]");
    }
}
