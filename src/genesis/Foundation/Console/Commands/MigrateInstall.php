<?php

namespace Genesis\Foundation\Console\Commands;

use Genesis\Console\Command;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;

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
     * @var \Illuminate\Database\Migrations\MigrationRepositoryInterface
     */
    protected $repository;

    /**
     * Create a new migration install command instance.
     *
     * @param  \Illuminate\Database\Migrations\MigrationRepositoryInterface  $repository
     * @return void
     */
    public function __construct(MigrationRepositoryInterface $repository)
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
