<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdvertisementResource;
use App\Models\Advertisement;

class AdvertisementController extends Controller
{
    public function index()
    {
        return AdvertisementResource::collection(Advertisement::paginate(20));
    }
}
