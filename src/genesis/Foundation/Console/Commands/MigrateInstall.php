<?php

namespace Genesis\Foundation\Console\Commands;

use Genesis\Console\Command;
use \Genesis\Contracts\Database\MigrationRepository;

class MigrateInstall extends Command
{
    /**
     * The command signature.
     *
     * @var string
     */
    protected $signature = 'migrate:install';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Install the migration table';

    /**
     * The repository instance.
     *
     * @var \Genesis\Contracts\Database\MigrationRepository
     */
    protected $repository;

    /**
     * Create a new migration install command instance.
     *
     * @param  \Genesis\Contracts\Database\MigrationRepository  $repository
     * @return void
     */
    public function __construct(MigrationRepository $repository)
    {
        $this->repository = $repository;

        parent::__construct();
    }

    /**
     * Handle the command call.
     *
     * @return void
     */
    protected function handle(): void
    {
        $this->repository->createRepository();

        $this->success('Migration table created successfully.');
    }
}
