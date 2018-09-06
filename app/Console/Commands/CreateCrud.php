<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class CreateCrud extends Command
{
    
    
    
    /**
     * 
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud
        {name : Nome do elemento a ser instaciado o crud}
        {--m|migration=0 : Definir ser deseja criar a migration para o CRUD a ser instanciado}
        {--r|route=0 : Definir ser deseja gerar as rotas padrões para o CRUD}
        {--f|force : Definir ser deseja criar a migration para o crud a ser instanciado}';
        

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $tipos;
    protected $TPO_SERVICO;
    protected $TPO_API;
    protected $TPO_MODEL;
    /**
     * Create a new command instance.
     * 
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
        $this->TPO_SERVICO = 1;
        $this->TPO_API = 2;
        $this->TPO_MODEL = 3;
        
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        
        
        $tipos = [$this->TPO_SERVICO,$this->TPO_API];
        foreach ($tipos as $key => $tipo) {
           
            $name = $this->qualifyClass($this->getNameInput());

            $path = $this->getPath($name,$tipo);

            // First we will check to see if the class already exists. If it does, we don't want
            // to create the class and overwrite the user's code. So, we will bail out so the
            // code is untouched. Otherwise, we will continue generating this class' files.
            if ($this->alreadyExists($this->getNameInput(),$tipo)) {
                if($tipo == $this->TPO_SERVICO){
                    $name .= "Servico";
                }
                if($tipo == $this->TPO_API){
                    $name .= "Api";                   
                }
                if (!$this->option('force')) {
                    if (! $this->confirm("O arquivo [{$name}] já existe. Deseja substitui-lo?")) {
                        continue;
                    }
                }
                
            }

            // Next, we will generate the path to the location where this class' file should get
            // written. Then, we will build the class and make the proper replacements on the
            // stub files so that it gets the correctly formatted namespace and class name.
            $this->makeDirectory($path);

            $this->files->put($path, $this->buildClass($name,$tipo));
        }
        

        if ($this->alreadyExists($this->getNameInput(),$this->TPO_MODEL)) {            
            if (!$this->option('force')) {
                $name = $this->qualifyClass($this->getNameInput());
                if ($this->confirm("O arquivo [{$name}] já existe. Deseja substitui-lo?")) {
                    $this->callSilent('make:model', [
                        'name' => $this->argument('name'), 
                        '--migration' => $this->option('migration'),
                        '--force' => true,
                    ]);
                }
            }else{
                $this->callSilent('make:model', [
                    'name' => $this->argument('name'), 
                    '--migration' => $this->option('migration'),
                    '--force' => $this->option('force'),
                ]);
            }            
        }else{
            $this->callSilent('make:model', [
                'name' => $this->argument('name'), 
                '--migration' => $this->option('migration')                
            ]);
        }
       

        if($this->option('route')){
            $this->setRoutes();
        }

        $this->info('CRUD criado com sucesso.');
    }
    
    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        $name = ltrim($name, '\\/');
        
        $rootNamespace = $this->rootNamespace();
        
        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }
        
        $name = str_replace('/', '\\', $name);
        
        return $this->qualifyClass(
            $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name
        );
    }
    
    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace;
    }
    
    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     * @return bool
     */
    protected function alreadyExists($rawName,$tipo)
    {
        return $this->files->exists($this->getPath($this->qualifyClass($rawName),$tipo));
    }
    
    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name, $tipo)    
    {
        
        
        
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);            
            if($tipo == $this->TPO_SERVICO){
                $name .= "Servico";
            }
            if($tipo == $this->TPO_API){
                $name .= "Api";
            }
            
        
        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }
    
    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name,$tipo)
    {
        $stub = $this->files->get($this->getStub()[$tipo]);

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name)->replacePrefix($stub, $name);
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $stub = str_replace(
            ['DummyNamespace', 'DummyRootNamespace', 'NamespacedDummyUserModel'],
            [$this->getNamespace($name), $this->rootNamespace(), config('auth.providers.users.model')],
            $stub
        );

        return $this;
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getNamespace($name)
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass(&$stub, $name)
    {
        $name = $this->qualifyClass($this->getNameInput());
        $class = str_replace($this->getNamespace($name).'\\', '', $name);
        $stub = str_replace('DummyClass', $class, $stub);
        return $this;
    }
    
    protected function replacePrefix($stub, $name)
    {
        $name = $this->qualifyClass($this->getNameInput());  
        $class = str_replace($this->getNamespace($name).'\\', '', $name);
        $prefix = strtolower($class."s");

        return str_replace('DummyPrefix', $prefix , $stub);
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->argument('name'));
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->laravel->getNamespace();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
            ['namespace', InputArgument::OPTIONAL, 'The name of the class'],
        ];
    }

    protected function getStub()
    {
        return 
        [
            $this->TPO_SERVICO => __DIR__.'/stubs/servico.stub',
            $this->TPO_API => __DIR__.'/stubs/apicontroller.stub'
        ];
    }
    
    protected function setRoutes()
    {
        $stubPath = __DIR__.'/stubs/routeCrud.stub';
        $stub = $this->files->get($stubPath);
        $name = $this->qualifyClass($this->getNameInput());       
        $content =  $this->replaceNamespace($stub, $name)->replaceClass($stub, $name)->replacePrefix($stub, $name);
        file_put_contents(
            base_path('routes/api.php'),
            $content,
            FILE_APPEND
        );
    }



}
