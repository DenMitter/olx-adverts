<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function index()
    {
        return SubscriptionResource::collection(
            Subscription::with('advertisement')
                ->paginate(20)
        );
    }
}
