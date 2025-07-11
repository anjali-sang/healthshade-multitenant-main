<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getLocations(){
        
        try{
            
            $user = auth()->user();
            $locations = Location::where('is_active',true)->where('org_id',auth()->user()->organization_id)->get();
            return response()->json(['success' => true, 'message' => '','locations' => $locations]);

        } catch (\Exception $e) {
            $apiError = ['success' => false, 'message' => $e->getMessage(), 'data' => []];
            return response()->json($apiError, 400); 
        }
    }
}
