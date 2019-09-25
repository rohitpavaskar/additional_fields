<?php

namespace Rohitpavaskar\AdditionalField\Models;

use Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rohitpavaskar\AdditionalField\Models\AdditionalFieldDropdown;
use Rohitpavaskar\AdditionalField\Models\AdditionalFieldTranslation;

class AdditionalField extends Model {
    
    use SoftDeletes;

    protected $fillable = ['id', 'name', 'mandatory', 'editable_by_user', 'parent_id', 'is_default', 'sequence_no'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot() {
        parent::boot();

        static::addGlobalScope('sequence_no', function (Builder $builder) {
            $builder->orderBy('sequence_no', 'asc');
        });
    }

    public function dropdowns() {
        return $this->hasMany(AdditionalFieldDropdown::class);
    }

    /**
     * Get the translation for additional fields
     */
    public function translations($language = '') {
        if ($language == '') {
            $language = app()->getLocale();
        }
        return $this->hasMany(AdditionalFieldTranslation::class)
                        ->whereIn('language', array($language, Config::get('app.fallback_locale')));
    }

}
