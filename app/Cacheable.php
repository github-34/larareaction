<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait Cacheable {

    /**
     * Updates an Entire Model Cache: all records from a database table associated with a model are stored in cache under key for that model (short) name
     * Useful for storing lookup tables
     * Like all() (e.g. User::all()) but it gets all records from cache; if table is not in cache, it puts the entire table in cache
     * Note:
     *      1. the method takes in a long class name that includes the namespace; the short name is the base class name
     *      2. the key in Cache is the model name (singular) not the table name, which is usually plural (User => 'users_table')
     *
     * @param String $className class name with name space ( e.g. App\Models\User, ***NOT*** User)
     * @return void
     */
    public static function obtainAllFromCache() : Collection
    {
        $reflectionClass = new \ReflectionClass(self::class);
        $cacheKey = $reflectionClass->getShortName();

        // Load Entire Table into Cache, if the key does not exist
        if (!Cache::has($cacheKey))
            self::refreshCache();
        // Return the entire cache
        return new Collection(json_decode(Cache::get($cacheKey), true));
    }

    public static function obtainFromCacheOrFail(int $id)
    {
        $reflectionClass = new \ReflectionClass(self::class);
        $cacheKey = $reflectionClass->getShortName();
       // if (!Cache::has($cacheKey))
            self::refreshCache();

        $objects = new Collection(json_decode(Cache::get($cacheKey), true));
        $object = $objects->where('id', $id);

        if (!$object->isEmpty()) {
            $newEmptyModel = $reflectionClass->newInstanceWithoutConstructor();
            return $newEmptyModel->fill($object->first());
        }
        throw new ModelNotFoundException();
    }
    public static function refreshCache() : void {

        $reflectionClass = new \ReflectionClass(self::class);
        $cacheKey = $reflectionClass->getShortName();
        // get the kind of model we want to fetch for (E.g. App\Models\User)
        $model = $reflectionClass->newInstanceWithoutConstructor();
        // getting all models (i.e. all records from a table) directly from database
        //$model = $reflectionClass->newInstance();
        // dd();
        // dd(\App\Models\User::all());
        $models = (self::class)::all();
        if (!$models->isEmpty())
            Cache::set($cacheKey, json_encode($models));
        else
            Cache::set($cacheKey, null);
    }

    public static function clearCache() : void {
        $reflectionClass = new \ReflectionClass(self::class);
        $cacheKey = $reflectionClass->getShortName();
    }
    // public function updateEntireModelCache(String $longClassName) {

    //     $reflectionClass = new \ReflectionClass($longClassName);
    //     $shortClassName = $reflectionClass->getShortName();

    //     $model = $reflectionClass->newInstanceWithoutConstructor();
    //     $models = $model->all();
    //     if (!$models->isEmpty()) {
    //         Cache::set($shortClassName, json_encode($models));
    //     }
    //     else
    //         Cache::set($shortClassName, null);
    // }
}