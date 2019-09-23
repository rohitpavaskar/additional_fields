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
        Route::get('/additional-fields/dropdowns/{id}', '\Rohitpavaskar\AdditionalField\Http\Controllers\AdditionalFieldController@dropdowns');
        Route::resource('/additional-fields', '\Rohitpavaskar\AdditionalField\Http\Controllers\AdditionalFieldController');
    }

}
