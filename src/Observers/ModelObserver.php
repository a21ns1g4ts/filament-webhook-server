<?php

namespace Marjose123\FilamentWebhookServer\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Marjose123\FilamentWebhookServer\HookJobProcess;
use Marjose123\FilamentWebhookServer\Models\FilamentWebhookServer;
use ReflectionClass as RC;
use Spatie\ModelInfo\ModelInfo;

class ModelObserver
{
    protected function getModuleName($model)
    {
        $modelInfo = ModelInfo::forModel($model::class);
        return ucfirst(str_replace("App\\Models\\", '', $modelInfo->class));
    }

    protected function getWebhooks($eventType, $module)
    {
        return Cache::remember("webhooks_{$eventType}_{$module}", 60, function () use ($eventType, $module) {
            return FilamentWebhookServer::query()
                ->whereJsonContains('events', [$eventType])
                ->where('model', $module)
                ->get();
        });
    }

    public function created(Model $model)
    {
        $module = $this->getModuleName($model);
        $search = $this->getWebhooks('created', $module);
        (new HookJobProcess($search, $model, 'created', $module))->send();
    }

    public function updated(Model $model)
    {
        $module = $this->getModuleName($model);
        $search = $this->getWebhooks('updated', $module);
        (new HookJobProcess($search, $model, 'updated', $module))->send();
    }

    public function deleted(Model $model)
    {
        $module = $this->getModuleName($model);
        $search = $this->getWebhooks('deleted', $module);
        (new HookJobProcess($search, $model, 'deleted', $module))->send();
    }

    public function restored(Model $model)
    {
        $module = $this->getModuleName($model);
        $search = $this->getWebhooks('restored', $module);
        (new HookJobProcess($search, $model, 'restored', $module))->send();
    }

    public function forceDeleted(Model $model)
    {
        $module = $this->getModuleName($model);
        $search = $this->getWebhooks('forceDeleted', $module);
        (new HookJobProcess($search, $model, 'forceDeleted', $module))->send();
    }
}
