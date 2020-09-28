<?php

namespace App\Support\Console\Commands;

use App\Support\Console\GenerateCommand;

class MakeModel extends GenerateCommand
{
  /**
   * The command signature.
   *
   * @var string
   */
  protected $signature = 'make:model {name : The name of model} 
                                     {--force : Overwrite the model if it exists}';
    
  /**
   * The command description.
   *
   * @var string
   */
  protected $description = 'Make a model';
  
  /**
   * Handle the command call.
   *
   * @return void
   */
  protected function handle(): void
  {
    $name = $this->argument('name');

    $path = $this->getPath($name);

    if ($this->files->exists($path) && !$this->option('force')) {
      $this->error('Model already exists!');
    }
    
    $this->makeDirectory($path);
    
    $stub = $this->files->get($this->getStub());
    
    $stub = str_replace('{{ class }}', $name, $stub);
    
    $this->files->put($path, $stub);
    
    $this->success('Model created');
  }
  
  /**
   * Get the stub path.
   *
   * @return string
   */
  protected function getStub(): string
  {
    return __DIR__ . '/stubs/model.stub';
  }

  /**
   * Resolve the filepath.
   *
   * @param string $name The name of the class.
   *
   * @return string
   */
  protected function getPath(string $name): string
  {
    return get_template_directory() . "/app/Models/{$name}.php";
  }
}