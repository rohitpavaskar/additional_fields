<?php

namespace Rohitpavaskar\AdditionalField\Models;

use Config;
use Illuminate\Database\Eloquent\Model;
use Rohitpavaskar\AdditionalField\Models\AdditionalFieldDropdownTranslation;

class AdditionalFieldDropdown extends Model {

    protected $fillable = ['id', 'name', 'additional_field_id', 'parent_id'];

    /**
     * Get the translation for additional fields
     */
    public function translations($language = '') {
        if ($language == '') {
            $language = app()->getLocale();
        }
        return $this->hasMany(AdditionalFieldDropdownTranslation::class)
                        ->whereIn('language', array($language, Config::get('app.fallback_locale')));
    }

}
