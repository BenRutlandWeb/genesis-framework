<?php

namespace Genesis\Foundation\Console\Commands;

use Genesis\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeModel extends GeneratorCommand
{
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    /**
     * The command signature.
     *
     * @var string
     */
    protected $signature = 'make:model {name : The name of model}
                                       {--posttype : Extend the posts table?}
                                       {--force : Overwrite the model if it exists}';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Make a model';

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        return str_replace(['{{ table }}', '{{ postType }}'], Str::snake($name), $stub);
    }

    /**
     * Get the stub path.
     *
     * @return string
     */
    protected function getStub(): string
    {
        if ($this->option('posttype')) {
            return __DIR__ . '/stubs/model.posttype.stub';
        }
        return __DIR__ . '/stubs/model.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Models';
    }
}
