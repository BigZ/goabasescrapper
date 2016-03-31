<?php

namespace AppBundle\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Goutte\Client;

class AppGetPartyEmailsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:get-party-emails')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new Client();
        $mailTab = new ArrayCollection();

        for ($i = 75001; $i <= 89126; $i++) {
            $crawler = $client->request('GET', 'https://www.goabase.net/party/zob/'.$i);
            print $i."\n";
            $crawler->filter('.ext_link')->each(function ($node) use (&$mailTab) {
                if (strpos($node->text(), '@')) {
                    $mails = explode('; ', $node->text());
                    foreach ($mails as $mail) {
                        if (!$mailTab->contains($mail)) {
                            $mailTab->add($mail);
                            file_put_contents('/tmp/goabase', $mail."\n", FILE_APPEND | LOCK_EX);
                        }
                    }
                }
            });
        }
    }

}
