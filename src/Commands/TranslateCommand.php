<?php 

namespace PaschalDev\EloquentTranslate\Commands;

class TranslateCommand extends BaseCommand {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eloquent-translate:translate {--model=} {--F|force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate eloquent models';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model = $this->option('model');
        $force = $this->option('force');

        $this->setModel($model);

        // Check if model exists 
        $model = $this->getModelInstance();

        // Check if model uses translation trait 
        $this->modelUsesTrait();

        // Fetch columns
        $columns = $model->translateColumns;

        // Check if columns are defined 
        if( ! $columns || ! is_array( $columns ) )
        {
            return $this->error("Translate columns not specified on model class. It should be an array of columns to translate");
        }
        
        //Translate all models
        $model->all()->each(function($model, $key){

            $model->translate();
        });
    }
}