<?php

namespace Marjose123\FilamentWebhookServer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Marjose123\FilamentWebhookServer\Models\FilamentWebhookServer;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class WebhookController extends BaseController
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'url' => ['required', 'string'],
            'method' => ['required', 'string', 'in:get,post'],
            'model' => ['required', 'string'],
            'header' => ['sometimes', 'required', 'string'],
            'data_option' => ['required', 'string', 'in:all,summary,custom'],
            'verifySsl' => ['required', 'boolean'],
            'events' => ['required', 'array', 'in:created,updated,deleted,restored,forceDeleted'],
        ]);

        try {
            $webhook = FilamentWebhookServer::create($request->all());
            return response()->json([
                'message' => 'Webhook created!',
                'webhook_id' => $webhook->id,
            ], ResponseAlias::HTTP_CREATED);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

