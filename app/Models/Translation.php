<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Translation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'locale_id',
        'key',
        'value',
        'group',
    ];

    protected $casts = [
        'locale_id' => 'integer',
    ];

    public function locale(): BelongsTo
    {
        return $this->belongsTo(Locale::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public static function getCachedTranslations($locale, $group = null)
    {
        $cacheKey = "translations.{$locale}" . ($group ? ".{$group}" : '');
        
        return Cache::remember($cacheKey, now()->addDay(), function () use ($locale, $group) {
            $query = self::query()
                ->join('locales', 'translations.locale_id', '=', 'locales.id')
                ->where('locales.code', $locale)
                ->where('locales.is_active', true);

            if ($group) {
                $query->where('translations.group', $group);
            }

            return $query->pluck('value', 'key')->toArray();
        });
    }

    public static function clearCache($locale = null, $group = null)
    {
        if ($locale) {
            $cacheKey = "translations.{$locale}" . ($group ? ".{$group}" : '');
            Cache::forget($cacheKey);
        } else {
            Cache::flush();
        }
    }

    protected static function booted()
    {
        static::saved(function ($translation) {
            self::clearCache($translation->locale->code, $translation->group);
        });

        static::deleted(function ($translation) {
            self::clearCache($translation->locale->code, $translation->group);
        });
    }
}
