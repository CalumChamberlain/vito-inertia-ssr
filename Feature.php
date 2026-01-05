<?php

namespace App\Vito\Plugins\CalumChamberlain\VitoInertiaSsr;

use App\SiteFeatures\FeatureInterface;
use App\Models\Site;

class Feature implements FeatureInterface
{
    public function __construct(
        public Site $site
    ) {
    }

    public function id(): string
    {
        return 'inertia-ssr';
    }

    public function name(): string
    {
        return 'Inertia SSR';
    }

    public function description(): string
    {
        return 'Enable Inertia.js Server-Side Rendering for this site';
    }
}

