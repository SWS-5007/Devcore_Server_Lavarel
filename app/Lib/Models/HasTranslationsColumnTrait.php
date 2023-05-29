<?php

namespace App\Lib\Models;

use App\Lib\DotArray;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait HasTranslationsColumnTrait
{
    protected $translationsValues = [];

    public static function bootHasTranslationsColumnTrait()
    {
        static::retrieved(function ($model) {
            if (static::convertTranslationsToPropertiesOnLoad() && is_array($model->{static::translationsColumnName()})) {
                $model->convertTranslationsToProperties();
            }
        });

        //delete translations fields from attributes
        static::creating(function ($model) {
            foreach ($model->translationFields() as $k) {
                if (isset($model->attributes[$k])) {
                    unset($model->attributes[$k]);
                }
            }
        });
        static::updating(function ($model) {
            foreach ($model->translationFields() as $k) {
                if (isset($model->attributes[$k])) {
                    unset($model->attributes[$k]);
                }
            }
        });
    }


    public function translationFields()
    {
        if (property_exists(static::class, 'translationsFields')) {
            return static::$translationsFields;
        }
        return [];
    }


    public function convertTranslationsToProperties($lang = null)
    {
        $translations = $this->getTranslations($lang);
        foreach ($this->translationFields() as $field) {
            if (!property_exists($this, $field)) {
                $this->translationsValues[] = $field;
                $this->attributes[$field] = $this->trans($field, $lang);
            }
        }
    }

    public function isTranslatableAttribute($key)
    {
        return  in_array($key, $this->translationFields());
    }


    public function __get($key)
    {
        if ($this->isTranslatableAttribute($key)) {
            return $this->trans($key);
        }
        return parent::__get($key);
    }

    public function __set($key, $value)
    {
        if ($this->isTranslatableAttribute($key)) {
            $this->setTrans($key, $value);
        }
        parent::__set($key, $value);
        return $this;
    }


    //add translation for cast to array
    public function getCasts(): array
    {
        return array_merge(
            parent::getCasts(),
            [static::translationsColumnName() => 'array']
        );
    }

    public static function convertTranslationsToPropertiesOnLoad()
    {
        if (property_exists(static::class, 'convertTranslationsToPropertiesOnLoad')) {
            return static::$convertTranslationsToPropertiesOnLoad;
        }
        return config('translations.conver_to_properties', true);
    }

    public static function translationsColumnName()
    {
        if (property_exists(static::class, 'translationsColumnName')) {
            return static::$translationsColumnName;
        }
        return config('translations.column', 'translations');
    }

    public function getTranslations($lang = 'current')
    {
        $lang = $this->normalizeTranslationCode($lang);
        return DotArray::get($this->{static::translationsColumnName()}, $lang, []);
    }

    public function trans($field, $lang = null, $default = '', $useFallbackLocale = false)
    {
        $trans = $default;

        $translations = $this->getTranslations($lang);
        if ($translations) {
            return DotArray::get($translations, $field, $default);
        } else if ($useFallbackLocale) {
            $translations = $this->getTranslations(Config::get('app.fallback_locale'));
            if ($translations) {
                return DotArray::get($translations, $field, $default);
            }
        }
        return $trans;
    }

    public function setTrans($field, $value, $lang = null)
    {
        $lang = $this->normalizeTranslationCode($lang);
        $originalTranslations = $this->{static::translationsColumnName()};
        DotArray::set($originalTranslations, $lang . '.' . $field, $value);
        $this->{static::translationsColumnName()} = $originalTranslations;
        $this->convertTranslationsToProperties();
    }

    public function normalizeTranslationCode($lang = null)
    {
        if ($lang === 'current' || (!$lang)) {
            $lang = Lang::getLocale();
        }
        return $lang;
    }

    public function isValidTranslationAttribute($field, $lang = null)
    {
        $lang = $this->normalizeTranslationCode($lang);
        return strlen(trim($this->trans($field, $lang))) > 0;
    }

    public function scopeHasTranslationAttribute(Builder $builder, $field, $lang = null)
    {
        $lang = $this->normalizeTranslationCode($lang);
        $builder->where(function ($q) use ($lang, $field) {
            $q->where(DB::raw("TRIM(" . $this->table . '.' . static::translationsColumnName() . "->>'$.{$lang}.{$field}')"), '!=', "")
                ->where(DB::raw("TRIM(" . $this->table . '.' . static::translationsColumnName() . "->>'$.{$lang}.{$field}')"), '!=', null);
        });
        return $builder;
    }

    public function scopeOrderByTranslationAttribute(Builder $builder, $field, $direction = "ASC", $lang = null)
    {
        $table = with(new static)->getTable();
        $lang = $this->normalizeTranslationCode($lang);
        $builder->orderBy(DB::raw("$table." . static::translationsColumnName() . "->>'$.{$lang}.{$field}' COLLATE utf8mb4_general_ci"), $direction);
        return $builder;
    }


    public function scopeWhereTranslationAttribute(Builder $builder, $field, $operation = "", $value = null, $lang = null)
    {

        $fieldRaw = $this->prepareTranslationFieldForQuery($field, $lang);
        if ($operation === "IN") {
            $builder->whereIn($fieldRaw, $value);
        } else {
            $builder->where($fieldRaw, $operation, $value);
        }

        return $builder;
    }

    public function prepareTranslationFieldForQuery($field, $lang = null)
    {
        $table = with(new static)->getTable();
        $lang = $this->normalizeTranslationCode($lang);
        return DB::raw("($table." . static::translationsColumnName() . "->>'$.{$lang}.{$field}' COLLATE utf8mb4_general_ci)");
    }

    public function scopeGetTranslationFieldForQuery(Builder $builder, $field, $lang = null)
    {
        if ($this->isTranslatableAttribute($field)) {
            return $this->prepareTranslationFieldForQuery($field, $lang);
        }
        return $field;
    }

    public function scopeIsTranslatableAttribute(Builder $builder, $field)
    {
        return $this->isTranslatableAttribute($field);
    }
}
