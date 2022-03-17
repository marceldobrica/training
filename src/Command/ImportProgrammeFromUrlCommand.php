<?php

declare(strict_types=1);

namespace App\Command;

use App\Decrypters\CaesarDecrypter;
use App\HttpClient\HttpClientImportPogramme;
use App\SaveEntities\SaveProgramme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportProgrammeFromUrlCommand extends Command
{
    protected static $defaultName = 'app:import:programme-url';

    protected static $defaultDescription = 'Import programme from url';

    private HttpClientImportPogramme $client;

    private CaesarDecrypter $decrypter;

    private SaveProgramme $saveProgramme;

    public function __construct(
        HttpClientImportPogramme $client,
        CaesarDecrypter $decrypter,
        EntityManagerInterface $entityManager,
        SaveProgramme $saveProgramme
    ) {
        $this->client = $client;
        $this->decrypter = $decrypter;
        $this->entityManager = $entityManager;
        $this->saveProgramme = $saveProgramme;

        parent::__construct();
    }


    public function execute(InputInterface $input, OutputInterface $output): int
    {

        $rowdata = $this->client->fetchData();

        foreach ($rowdata as $encryptedArray) {
            $programmeArray = $this->decrypter->decryptProgrammeArray($encryptedArray);
            $allLines[] = $programmeArray;
        }

        $output->write(print_r($allLines));

        return self::SUCCESS;
    }
}
