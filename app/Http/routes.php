<?php

Route::group(['middleware' => 'api', 'prefix' => 'api'], function() {
    Route::get('/stations/{id}', function($id) {
        $station = App\Station::findOrFail($id);

        return $station->aerometrics()->latest()->whereRaw('`created_at` >= NOW() - INTERVAL 7 DAY')->get();
    });
    Route::post('/stations/{id}', function($id) {
        $station = App\Station::findOrFail($id);
        $_get = ['created_at'];
        $_fields = request()->input('fields', []);
        $_properties = array_keys(config('aerometrics.properties'));

        $_to = new DateTime('now');
        $_from = clone $_to;
        $_from->modify('-1 week');

        $range = request()->input('range', $_from->format('d/m/Y H:i:s') . ' - ' . $_to->format('d/m/Y H:i:s'));
        $range = explode(' - ', $range);

        $from = DateTime::createFromFormat('d/m/Y H:i:s', $range[0]);
        $to = DateTime::createFromFormat('d/m/Y H:i:s', $range[1]);

        foreach($_fields as $_field) {
            if(in_array($_field, $_properties)) {
                $_get[] = $_field;
            }
        }

        return $station->aerometrics()->latest()->where('created_at', '>=', $from)->where('created_at', '<=', $to)->get($_get);
    });
    Route::get('/stations', function() {
        return App\Station::all();
    });

    Route::post('/stations', function() {
        $station = App\Station::where('unique_id', '=', request()->input('unique_id', ''))->firstOrFail();

        $aerometric = new App\Aerometric();
        $aerometric->station_id = $station->id;
        foreach(array_keys(config('aerometrics.properties')) as $property) {
            $aerometric->{$property} = request()->input($property, '0.0');
        }
        $aerometric->save();

        return ['success' => true];
    });
});

Route::group(['middleware' => 'web'], function () {
    Route::group(['middleware' => 'auth', 'prefix' => 'backend'], function () {
        Route::get('/', 'BackendController@index');
        Route::resource('stations', 'Backend\StationsController');
        Route::get('stations/{stations}/import', 'Backend\StationsController@getImport');
        Route::post('stations/{stations}/import', 'Backend\StationsController@postImport');
        Route::resource('stations.aerometrics', 'Backend\AerometricsController');
        Route::group(['middleware' => 'admin'], function () {
            Route::resource('tags', 'Backend\TagsController');
            Route::resource('users', 'Backend\UsersController');
            Route::resource('configs', 'Backend\ConfigsController');
        });
    });
    Route::auth();

    Route::get('/', 'HomeController@index');
});
