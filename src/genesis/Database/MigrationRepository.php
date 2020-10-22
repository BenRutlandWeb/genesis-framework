<?php

namespace Genesis\Database;

use Illuminate\Database\Schema\Builder;
use Illuminate\Database\Schema\Blueprint;

use Genesis\Contracts\Database\MigrationRepository as MigrationRepositoryInterface;

class MigrationRepository implements MigrationRepositoryInterface
{
    /**
     * The schema builder
     *
     * @var \Illuminate\Database\Schema\Builder
     */
    protected $builder;

    /**
     * The table name
     *
     * @var string
     */
    protected $table;

    /**
     * Assign the schema builder and migration table name
     *
     * @param \Illuminate\Database\Schema\Builder $builder
     * @param string                              $table
     *
     * @return void
     */
    public function __construct(Builder $builder, string $table)
    {
        $this->builder = $builder;
        $this->table = $table;
    }

    /**
     * Create the migration repository
     *
     * @return void
     */
    public function createRepository(): void
    {
        if (!$this->builder->hasTable($this->table)) {
            $this->createTable();
        }
    }

    /**
     * Create the migration table
     *
     * @return void
     */
    public function createTable(): void
    {
        $this->builder->create($this->table, function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
}
