<?php 

namespace PaschalDev\EloquentGoogleTranslate\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{

    protected $fillable = [

        'model_id',
        'model',
        'locale',
        'column',
        'translation'
    ];

    public function getTable()
    {
        return config('eloquent-google-translate.database_table');
    }
}