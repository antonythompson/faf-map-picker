<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AppModel extends Model
{
    public static $storeValidationFields = [];
    public static $updateValidationFields = [];
    public static $defaultLoaded = [];
    public static $loadable = [];
    public static $sortable = [];

    public function scopeWithAndWhereHas($query, $relation, $constraint)
    {
        return $query->whereHas($relation, $constraint)
            ->with([$relation => $constraint]);
    }

    public static function validateStore(Request $request)
    {
        if (!empty(static::$storeValidationFields)) {
            $validator = Validator::make($request->all(), static::$storeValidationFields);
            $data = $validator->validate();
            return $data;
        }
        return $request->all();
    }

    public static function validateUpdate(Request $request)
    {
        if (!empty(static::$updateValidationFields)) {
            $validator = Validator::make($request->all(), static::$updateValidationFields);
            $data = $validator->validate();
            dd($data);
            return $data;
        }
        return $request->all();
    }
}
