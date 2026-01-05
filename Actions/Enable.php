<?php

namespace App\Vito\Plugins\CalumChamberlain\VitoInertiaSsr\Actions;

use App\Actions\Worker\CreateWorker;
use App\Actions\Worker\ManageWorker;
use App\DTOs\DynamicField;
use App\DTOs\DynamicForm;
use App\Exceptions\SSHError;
use App\Models\Worker;
use App\SiteFeatures\Action;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Enable extends Action
{
    public function name(): string
    {
        return 'Enable';
    }

    public function active(): bool
    {
        $typeData = $this->site->type_data ?? [];

        return ! data_get($typeData, 'inertia_ssr', false);
    }

    public function form(): ?DynamicForm
    {
        return DynamicForm::make([
            DynamicField::make('command')
                ->text()
                ->label('Command')
                ->default('php artisan inertia:start-ssr')
                ->description('The command to start Inertia SSR server'),
        ]);
    }

    /**
     * @throws SSHError
     */
    public function handle(Request $request): void
    {
        Validator::make($request->all(), [
            'command' => 'required|string',
        ])->validate();

        $command = $request->input('command', 'php artisan inertia:start-ssr');

        /** @var ?Worker $worker */
        $worker = $this->site->workers()->where('name', 'inertia-ssr')->first();
        if ($worker) {
            app(ManageWorker::class)->restart($worker);
        } else {
            app(CreateWorker::class)->create(
                $this->site->server,
                [
                    'name' => 'inertia-ssr',
                    'command' => $command,
                    'user' => $this->site->user ?? $this->site->server->getSshUser(),
                    'auto_start' => true,
                    'auto_restart' => true,
                    'numprocs' => 1,
                ],
                $this->site,
            );
        }

        $typeData = $this->site->type_data ?? [];
        data_set($typeData, 'inertia_ssr', true);
        data_set($typeData, 'inertia_ssr_port', $port);
        $this->site->type_data = $typeData;
        $this->site->save();

        $request->session()->flash('success', 'Inertia SSR has been enabled for this site.');
    }
}
