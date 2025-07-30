<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface EntityWithSuffix
{
    public function suffixes(): HasMany;
}