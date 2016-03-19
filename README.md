## Laravel Environment Loader

Package for Laravel 5.X projects that allows loading providers based on environment
or debug level set.

### Requirements

 * PHP 5.5+
 * laravel 5.1+

### Installation

Install using composer, or checkout / pull the files from github.com.

 * composer require somnambulist/laravel-environment-loader

### Setup / Getting Started

 * add \Somnambulist\EnvironmentLoader\ServiceProvider::class to your config/app.php
 * the provider should occur before any of your own providers, and as far up as possible
 * ./artisan vendor:publish
 * customise the providers you want to load to the environments you want them in
 * remove those providers from your main app.php config
