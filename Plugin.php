<?php

namespace App\Vito\Plugins\CalumChamberlain\VitoInertiaSsr;

use App\Plugins\AbstractPlugin;
use App\Plugins\RegisterSiteFeature;
use App\Plugins\RegisterSiteFeatureAction;
use App\Vito\Plugins\CalumChamberlain\VitoInertiaSsr\Actions\Enable;
use App\Vito\Plugins\CalumChamberlain\VitoInertiaSsr\Actions\Disable;

class Plugin extends AbstractPlugin
{
    protected string $name = 'Inertia SSR Plugin';

    protected string $description = 'Enable Inertia.js Server-Side Rendering (SSR) for Laravel sites';

    public function boot(): void
    {
        RegisterSiteFeature::make('laravel', 'inertia-ssr')
            ->label('Inertia SSR')
            ->description('Enable Inertia.js Server-Side Rendering for this site')
            ->register();

        RegisterSiteFeatureAction::make('laravel', 'inertia-ssr', 'enable')
            ->label('Enable')
            ->handler(Enable::class)
            ->register();

        RegisterSiteFeatureAction::make('laravel', 'inertia-ssr', 'disable')
            ->label('Disable')
            ->handler(Disable::class)
            ->register();
    }
}
