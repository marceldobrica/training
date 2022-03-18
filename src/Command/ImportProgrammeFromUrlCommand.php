<?php

declare(strict_types=1);

namespace App\Command;

use App\Decrypters\CaesarDecrypter;
use App\HttpClient\HttpClientImportPogramme;
use App\Repository\ProgrammeRepository;
use App\SaveEntities\SaveProgramme;
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

    private ProgrammeRepository $programmeRepository;

    public function __construct(
        HttpClientImportPogramme $client,
        CaesarDecrypter $decrypter,
        SaveProgramme $saveProgramme,
        ProgrammeRepository $programmeRepository
    ) {
        $this->client = $client;
        $this->decrypter = $decrypter;
        $this->saveProgramme = $saveProgramme;
        $this->programmeRepository = $programmeRepository;

        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $rawData = $this->client->fetchData();

        $vector = $this->programmeRepository->findOcupiedRooms(
            \DateTime::createFromFormat('d.m.Y H:i', '15.03.2022'),
            \DateTime::createFromFormat('d.m.Y H:i', '15.03.2022')
        );

//        foreach ($rawData as $encryptedArray) {
//            $programmeArray = $this->decrypter->decryptProgrammeArray($encryptedArray);
//            $this->saveProgramme->saveProgrammeFromArray($programmeArray);
//        }

        return self::SUCCESS;
    }
}
