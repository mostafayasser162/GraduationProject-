<?php

namespace App\Models\Traits;

trait DynamicPerPage
{
    public function __construct()
    {
        $this->perPage = min(request('per_page', $this->perPage), 500);

        parent::__construct();
    }
}
