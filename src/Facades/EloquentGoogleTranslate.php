<?php 

namespace PaschalDev\EloquentGoogleTranslate\Facades;

use Illuminate\Support\Facades\Facade;

class EloquentGoogleTranslate extends Facade
{	
	protected static function getFacadeAccessor()
	{
		return 'eloquent-translate';
	}
}