<?php

namespace App\Scopes;

use App\Viking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope;

class VikingScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \App\Viking  $model
     * @return void
     */
    public function apply(Builder $builder, Viking $model)
    {
        $builder->where('type', 'viking');
    }
}