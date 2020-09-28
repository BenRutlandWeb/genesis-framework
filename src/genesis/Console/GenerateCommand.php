<?php

namespace Genesis\Console;

use Genesis\Console\Command;
use Genesis\Filesystem\Filesystem;

abstract class GenerateCommand extends Command
{
    /**
     * The filesystem.
     *
     * @var \Genesis\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Assign the filesystem and call the parent contructor.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;

        parent::__construct();
    }

    /**
     * If the directory doesn't exist, then create it.
     *
     * @param string $path The path to make the directory.
     *
     * @return string
     */
    protected function makeDirectory(string $path): string
    {
        $directory = dirname($path);

        if (!$this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory);
        }
        return $path;
    }
}
