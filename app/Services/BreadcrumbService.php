<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;

class BreadcrumbService
{
    public function generateBreadcrumbs($items)
    {
        $breadcrumbs = [];
        $breadcrumbs[] = ['name' => 'Home', 'url' => route('dashboard')];

        foreach ($items as $itemName => $routeName) {
            if ($itemName !== 'Home') {
                $breadcrumbs[] = ['name' => $itemName, 'url' => route($routeName)];
            }
        }

        return $breadcrumbs;
    }
}