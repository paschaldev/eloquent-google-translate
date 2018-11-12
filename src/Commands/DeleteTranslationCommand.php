<?php 

namespace PaschalDev\EloquentTranslate\Commands;

class DeleteTranslationCommand extends BaseCommand {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eloquent-translate:clear {--model=} {--L|locale?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete model translations';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model = $this->option('model');
        $locale = $this->option('locale');

        $this->setModel($model);

        // Check if model exists 
        $model = $this->getModelInstance();

        $model->all()->each(function($model, $key) use($locale){

            if( $locale ){

                return $model->translations()->where('locale', $locale)->delete();
            }

            return $model->translations()->delete();
        });
    }
}