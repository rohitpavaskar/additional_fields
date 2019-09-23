<?php

namespace Rohitpavaskar\AdditionalField\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalFieldTranslation extends Model {

    protected $fillable = ['id', 'name', 'additional_field_id', 'language'];

}
