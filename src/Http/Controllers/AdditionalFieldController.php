<?php

namespace Rohitpavaskar\AdditionalField\Http\Controllers;

use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Rohitpavaskar\Localization\Models\Language;
use Rohitpavaskar\AdditionalField\Models\AdditionalField;
use Rohitpavaskar\AdditionalField\Models\AdditionalFieldDropdown;
use Rohitpavaskar\AdditionalField\Models\AdditionalFieldTranslation;
use Rohitpavaskar\AdditionalField\Models\AdditionalFieldDropdownTranslation;
use Rohitpavaskar\AdditionalField\Http\Requests\StoreAdditionalFieldRequest;
use Rohitpavaskar\AdditionalField\Http\Requests\UpdateAdditionalFieldRequest;

class AdditionalFieldController {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $additionalFields = AdditionalField::with(['translations'])->get()->toArray();
        return array_map('replaceKey', $additionalFields);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdditionalFieldRequest $request) {
        $sequenceNo = AdditionalField::max('sequence_no') + 1;
        $additionalField = new AdditionalField();
        $additionalField->name = $request->name;
        $additionalField->type = $request->type;
        $additionalField->parent_id = $request->parent_id;
        $additionalField->is_default = true;
        $additionalField->sequence_no = $sequenceNo;
        $additionalField->save();
        $additionalFieldTranslation = new AdditionalFieldTranslation();
        $additionalFieldTranslation->name = $request->name;
        $additionalFieldTranslation->language = $request->language;
        $additionalField->translations()->save($additionalFieldTranslation);
        $result = $additionalField->save();
        $optionArr = array();
        if (is_array($request->options)) {
            foreach ($request->options as $option) {
                $dropdown = new AdditionalFieldDropdown();
                $dropdown->name = $option['name'];
                $dropdown->additional_field_id = $additionalField->id;
                if (isset($option['parent_id'])) {
                    $dropdown->parent_id = $option['parent_id'];
                }
                $dropdown->save();
                $dropdown->translations()->save(new AdditionalFieldDropdownTranslation([
                    'name' => $option['name'],
                    'language' => $request->language,
                ]));
            }
        }

        $this->clearCache('custom_fields_{{language}}', $request->language);

        Schema::table('users', function (Blueprint $table) use($additionalField) {
            switch ($additionalField->type) {
                case 'dropdown':
                    $table->unsignedInteger('custom_' . $additionalField->id)->nullable();
                    break;
                case 'date':
                    $table->date('custom_' . $additionalField->id)->nullable();
                    break;
                case 'file':
                    $table->string('custom_' . $additionalField->id)->nullable();
                    break;
                case 'text':
                    $table->string('custom_' . $additionalField->id)->nullable();
                    break;
                case 'freetext':
                    $table->text('custom_' . $additionalField->id)->nullable();
                    break;
            }
        });

        if ($result) {
            return response(
                    array(
                "message" => __('translations.created_msg', array('attribute' => trans('translations.additional_field'))),
                "status" => true,
                    ), 201);
        }
        return response(
                array(
            "message" => trans('translations.error_processing_request'),
            "status" => true,
                ), 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $additionalField = AdditionalField::with(['translations'])
                        ->where('id', $id)->get()->toArray();
        return array_map('replaceKey', $additionalField);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdditionalFieldRequest $request, $id) {
        $additionalField = AdditionalField::findOrFail($id);
        $additionalField->parent_id = $request->parent_id;
        $result = $additionalField->save();
        AdditionalFieldTranslation::updateOrCreate(
                ['additional_field_id' => $id, 'language' => $request->language], ['name' => $request->name]
        );
        $optionArr = array();
        if (is_array($request->options)) {
            foreach ($request->options as $option) {
                if (!isset($option['id'])) {
                    $dropdown = new AdditionalFieldDropdown();
                    $dropdown->name = $option['name'];
                    $dropdown->additional_field_id = $id;
                    if (isset($option['parent_id'])) {
                        $dropdown->parent_id = $option['parent_id'];
                    }
                    $dropdown->save();
                    $dropdown->translations()->save(new AdditionalFieldDropdownTranslation([
                        'name' => $option['name'],
                        'language' => $request->language,
                    ]));
                    AdditionalFieldDropdownTranslation::firstOrCreate(
                            ['additional_field_dropdown_id' => $dropdown->id, 'language' => Config::get('app.fallback_locale')], ['name' => $option['name']]
                    );
                } else {
                    $dropdown = AdditionalFieldDropdown::findOrFail($option['id']);
                    if (isset($option['parent_id'])) {
                        $dropdown->parent_id = $option['parent_id'];
                    }
                    $dropdown->save();
                    AdditionalFieldDropdownTranslation::updateOrCreate(
                            ['additional_field_dropdown_id' => $option['id'], 'language' => $request->language], ['name' => $option['name']]
                    );
                    AdditionalFieldDropdownTranslation::firstOrCreate(
                            ['additional_field_dropdown_id' => $option['id'], 'language' => Config::get('app.fallback_locale')], ['name' => $option['name']]
                    );
                }
            }
        }

        $this->clearCache('custom_dropdowns_' . $id . '_{{language}}', $request->language);
        $this->clearCache('custom_fields_{{language}}', $request->language);

        if ($result) {
            return response(
                    array(
                "message" => __('translations.updated_msg', array('attribute' => trans('translations.additional_field'))),
                "status" => true,
                    ), 201);
        }
        return response(
                array(
            "message" => trans('translations.error_processing_request'),
            "status" => true,
                ), 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $addtionalField = AdditionalField::findOrFail($id);
        $addtionalField->delete();
        $this->clearCache('custom_fields_{{language}}', $request->language);
        return response(
                array(
            "message" => __('translations.deleted_msg', array('entity' => trans('translations.additional_field'))),
            "status" => true,
                ), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dropdowns($id) {
        $additionalFieldDropdown = AdditionalFieldDropdown::with(['translations'])
                        ->where('additional_field_id', $id)->get()->toArray();
        return array_map('replaceKey', $additionalFieldDropdown);
    }

    function clearCache($key, $language) {
        $languages = Language::all();
        Cache::forget(str_replace("{{language}}", $language, $key));
        foreach ($languages as $language) {
            if ($language->code == Config::get('app.fallback_locale')) {
                Cache::forget(str_replace("{{language}}", $language, $key));
            }
        }
    }

}
