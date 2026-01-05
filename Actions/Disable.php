<?php

namespace App\Vito\Plugins\CalumChamberlain\VitoInertiaSsr\Actions;

use App\Actions\Worker\ManageWorker;
use App\Models\Worker;
use App\SiteFeatures\Action;
use Illuminate\Http\Request;

class Disable extends Action
{
    public function name(): string
    {
        return 'Disable';
    }

    public function active(): bool
    {
        $typeData = $this->site->type_data ?? [];
        return (bool) data_get($typeData, 'inertia_ssr', false);
    }

    public function handle(Request $request): void
    {
        /** @var ?Worker $worker */
        $worker = $this->site->workers()->where('name', 'inertia-ssr')->first();
        if ($worker) {
            app(ManageWorker::class)->delete($worker);
        }

        $typeData = $this->site->type_data ?? [];
        data_set($typeData, 'inertia_ssr', false);
        $this->site->type_data = $typeData;
        $this->site->save();

        $request->session()->flash('success', 'Inertia SSR has been disabled for this site.');
    }
}

