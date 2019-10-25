<?php

namespace Rohitpavaskar\AdditionalField;

use Illuminate\Support\Facades\Route;

class AdditionalField {

    /**
     * Binds the Column selection routes into the controller.
     *
     * @param  callable|null  $callback
     * @param  array  $options
     * @return void
     */
    public static function routes() {
        Route::post('/additional-fields/update_sequence', '\Rohitpavaskar\AdditionalField\Http\Controllers\AdditionalFieldController@updateSequence');
        Route::get('/additional-fields/multiple_dropdowns/{id}', '\Rohitpavaskar\AdditionalField\Http\Controllers\AdditionalFieldController@multiple_dropdowns');
        Route::get('/additional-fields/dropdowns/{id}', '\Rohitpavaskar\AdditionalField\Http\Controllers\AdditionalFieldController@dropdowns');
        Route::resource('/additional-fields', '\Rohitpavaskar\AdditionalField\Http\Controllers\AdditionalFieldController');
    }

}
