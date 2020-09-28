<?php

namespace App\Support\Console\Commands;

use App\Support\Console\GenerateCommand;
use Illuminate\Support\Str;

class MakeController extends GenerateCommand
{
  /**
   * The command signature.
   *
   * @var string
   */
  protected $signature = 'make:controller {name : The name of controller} 
                                          {--action= : The controller action}
                                          {--force : Overwrite the controller if it exists}';
  
  /**
   * The command description.
   *
   * @var string
   */
  protected $description = 'Make a controller';
  
  /**
   * Handle the command call.
   *
   * @return void
   */
  protected function handle(): void
  {
    $name = $this->argument('name');
    
    $filename = Str::studly($name);

    $path = $this->getPath($filename);

    if ($this->files->exists($path) && !$this->option('force')) {
      $this->error('Controller already exists!');
    }
    
    $this->makeDirectory($path);
    
    $stub = $this->files->get($this->getStub());
    
    $action = Str::snake($this->option('action'));
    
    $stub = str_replace(['{{ class }}', '{{ action }}'], [$filename, $action], $stub);
    
    $this->files->put($path, $stub);
    
    $this->success('Controller created');
  }
  
  /**
   * Get the stub path.
   *
   * @return string
   */
  protected function getStub(): string
  {
    return __DIR__ . '/stubs/controller.stub';
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
    return get_template_directory() . "/app/Controllers/{$name}.php";
  }
}