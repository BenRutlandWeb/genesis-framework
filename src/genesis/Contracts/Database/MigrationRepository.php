<?php

namespace Genesis\Contracts\Database;

interface MigrationRepository
{
    /**
     * Create the migration repository
     *
     * @return void
     */
    public function createRepository(): void;
}
