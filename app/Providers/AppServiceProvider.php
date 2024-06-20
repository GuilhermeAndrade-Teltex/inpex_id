<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Image;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $dom_json_decode = function ($data_encode) {
                return json_decode(str_replace('&quot;', '"', $data_encode), true);
            };

            $view->with('dom_json_decode', $dom_json_decode);
        });

        View::composer('components.perfil', function ($view) {
            $user = Auth::user();
            $intranetUrl = 'http://intranet.teltexcorp.com:8090/';
            $imagePath = $intranetUrl . 'images/photo-profile/profile-default.jpg';
            if ($user) {
                $image = Image::where('module', 'users')
                    ->where('module_id', $user->id)
                    ->where('status', 'ACTIVE')
                    ->first();

                if ($image) {
                    $imagePath = $intranetUrl . $image->path_cropped;
                }

                $fullName = $user->name;
                $parts = explode(' ', $fullName);
                $firstName = $parts[0];
                $lastName = end($parts);

                $view->with('name', $firstName . ' ' . $lastName);
                $view->with('department', $user->department);
                $view->with('image', $imagePath);
            }
        });

        Validator::extend('formato_cnpj', function ($attribute, $value, $parameters, $validator) {
            // Expressão regular para validar CNPJ (com ou sem máscara)
            return preg_match('/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$|^\d{14}$/', $value);
        });

        Validator::extend('formato_cep', function ($attribute, $value, $parameters, $validator) {
            // Expressão regular para validar CEP (com ou sem máscara)
            return preg_match('/^\d{5}-\d{3}$|^\d{8}$/', $value);
        });
    }
}
