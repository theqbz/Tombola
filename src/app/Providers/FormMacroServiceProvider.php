<?php

namespace App\Providers;

use App\Services\FormMacros;
use Form;
use Collective\Html\FormBuilder;

use Collective\Html\HtmlServiceProvider;


/**
 * Class MacroServiceProvider
 *
 * @package App\Providers
 */
class FormMacroServiceProvider extends HtmlServiceProvider {

    public function register() {
        parent::register();
        $this->app->singleton('form', function ($app) {
            $form = new FormMacros($app['html'], $app['url'],$app['view'], $app['session.store']->token(),$app['request']);

            return $form->setSessionStore($app['session.store']);
        });

    }

    public function boot() {


    }

}
