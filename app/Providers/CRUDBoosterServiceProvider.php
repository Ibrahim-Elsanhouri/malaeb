<?php namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use crocodicstudio\crudbooster\commands\CrudboosterInstallationCommand;
use crocodicstudio\crudbooster\commands\CrudboosterUpdateCommand;
use Illuminate\Foundation\AliasLoader;

class CRUDBoosterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */

    public function boot()
    {   
        /**
         * Add path 
         */
        $path = base_path('vendor/crocodicstudio/crudbooster/src');                                     
        $this->loadViewsFrom($path.'/views', 'crudbooster');
        $this->publishes([$path.'/configs/crudbooster.php' => config_path('crudbooster.php')],'cb_config');            
        $this->publishes([$path.'/localization' => resource_path('lang')], 'cb_localization');                 
        $this->publishes([$path.'/database' => base_path('database')],'cb_migration');


        /* Integrate LFM to CRUDBooster */
        $this->publishes([
            $path.'/laravel-filemanager' => base_path('vendor/unisharp/laravel-filemanager'),
        ],'cb_lfm');

        $this->publishes([
            $path.'/laravel-filemanager/public' => public_path('vendor/laravel-filemanager'),
        ],'cb_lfm');        

        $this->publishes([
            $path.'/laravel-filemanager/src/config/lfm.php' => config_path('lfm.php'),
        ],'cb_lfm');        

        $this->publishes([
            $path.'/laravel-filemanager/src/views/script.blade.php' => resource_path('views/vendor/laravel-filemanager/script.blade.php'),
        ],'cb_lfm');

        $this->publishes([
            $path.'/userfiles/views/vendor/crudbooster/type_components/readme.txt' => resource_path('views/vendor/crudbooster/type_components/readme.txt'),
        ],'cb_type_components');

        if(!file_exists(app_path('Http/Controllers/CBHook.php'))) {
            $this->publishes([$path.'/userfiles/controllers/CBHook.php' => app_path('Http/Controllers/CBHook.php')],'CBHook');
        }

        if(!file_exists(app_path('Http/Controllers/AdminCmsUsersController.php'))) {
            $this->publishes([$path.'/userfiles/controllers/AdminCmsUsersController.php' => app_path('Http/Controllers/AdminCmsUsersController.php')],'cb_user_controller');
        }
        /**
         * Edit
         */
        // require $path.'/validations/validation.php';        
        require app_path('Http/Controllers/CrudBooster/routes.php');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {   
        /**
         * Add path 
         */
        $path = base_path('vendor/crocodicstudio/crudbooster/src');                         
        require $path.'/helpers/Helper.php';      

        $this->mergeConfigFrom($path.'/configs/crudbooster.php','crudbooster');        
        
        $this->app->singleton('crudbooster', function ()
        {
            return true;
        });

        // $this->commands([
        //     commands\Mailqueues::class            
        // ]);

        $this->registerCrudboosterCommand();

        $this->commands('crudboosterinstall');
        $this->commands('crudboosterupdate');


        $this->app->register('Barryvdh\DomPDF\ServiceProvider');
        $this->app->register('Maatwebsite\Excel\ExcelServiceProvider');
        $this->app->register('Unisharp\Laravelfilemanager\LaravelFilemanagerServiceProvider');
        $this->app->register('Intervention\Image\ImageServiceProvider');

        $loader = AliasLoader::getInstance();
        $loader->alias('PDF', 'Barryvdh\DomPDF\Facade');
        $loader->alias('Excel', 'Maatwebsite\Excel\Facades\Excel');
        $loader->alias('Image', 'Intervention\Image\Facades\Image');
        $loader->alias('CRUDBooster', 'crocodicstudio\crudbooster\helpers\CRUDBooster');
        $loader->alias('CB', 'crocodicstudio\crudbooster\helpers\CB');
    }


    private function registerCrudboosterCommand()
    {
        $this->app->singleton('crudboosterinstall',function() {
            return new CrudboosterInstallationCommand;
        });

        $this->app->singleton('crudboosterupdate',function() {
            return new CrudboosterUpdateCommand;
        });        
    }
}
