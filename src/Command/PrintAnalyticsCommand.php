<?php

declare(strict_types=1);

namespace App\Command;

use App\Analytics\AdminSuccessLoginBucket;
use App\Analytics\AnalyticsLogParser;
use App\Analytics\BucketingCollection;
use App\Analytics\SuccessLoginBucket;
use App\Analytics\UsersCreatedByRoleBucket;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PrintAnalyticsCommand extends Command
{
    protected static $defaultName = 'app:print-analytics';

    protected static $defaultDescription = 'Print all created analytics on terminal';

    private string $appAnalyticsLogFile;

    private BucketingCollection $bucketingCollection;

    private AnalyticsLogParser $analyticsLogParser;

    private SuccessLoginBucket $successLoginBucket;

    private AdminSuccessLoginBucket $adminSuccessLoginBucket;

    private UsersCreatedByRoleBucket $usersCreatedByRoleBucket;

    public function __construct(
        string $appAnalyticsLogFile,
        BucketingCollection $bucketingCollection,
        AnalyticsLogParser $analyticsLogParser,
        SuccessLoginBucket $successLoginBucket,
        AdminSuccessLoginBucket $adminSuccessLoginBucket,
        UsersCreatedByRoleBucket $usersCreatedByRoleBucket
    ) {
        $this->appAnalyticsLogFile = $appAnalyticsLogFile;
        $this->bucketingCollection = $bucketingCollection;
        $this->analyticsLogParser = $analyticsLogParser;
        $this->successLoginBucket = $successLoginBucket;
        $this->adminSuccessLoginBucket = $adminSuccessLoginBucket;
        $this->usersCreatedByRoleBucket = $usersCreatedByRoleBucket;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        foreach ($this->analyticsLogParser->getLine() as $logLine) {
            $this->bucketingCollection->add($logLine);
        }

        $io->section('Number of API logins at all times - grouped by username, sorted descending
         by login count (table data)');
        $ioTable = $io->createTable()->setHeaders(['Login count', 'Username']);
        $rows = [];
        foreach ($this->successLoginBucket->get() as $apiLogin) {
            $rows[] = [$apiLogin->getLoginCounts(), $apiLogin->getEmail()];
        }
        $ioTable->setRows($rows);
        $ioTable->render();
        $io->newLine();

        $io->section('Number of admin logins - grouped per day (histogram data)');
        $ioTable = $io->createTable()->setHeaders(['Date', 'Admin logins']);
        $rows = [];
        foreach ($this->adminSuccessLoginBucket->get() as $apiLogin) {
            $rows[] = [$apiLogin->getDataKey(), $apiLogin->getLoginCounts()];
        }
        $ioTable->setRows($rows);
        $ioTable->render();
        $io->newLine();

        $io->section('New accounts created - pie percentages by user role (table data)');
        $ioTable = $io->createTable()->setHeaders(['Roles', 'Percentage']);
        $rows = [];
        foreach ($this->usersCreatedByRoleBucket->get() as $apiLogin) {
            $rows[] = [$apiLogin->getRole(), $apiLogin->getPercent() . '%'];
        }
        $ioTable->setRows($rows);
        $ioTable->render();
        $io->newLine();

        return self::SUCCESS;
    }
}
