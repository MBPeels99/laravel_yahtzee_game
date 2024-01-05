<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class StartScreenController extends Controller
{
    public function showWelcomePage()
    {
        return view('welcome');
    }

    public function storeDisplayName(Request $request)
    {
        $displayName = $request->input('displayName');
        Log::info("Received displayName: $displayName");
        
        session(['displayName' => $displayName]);

        // Test retrieval:
        $retrievedName = session('displayName');
        Log::info("Retrieved from session: $retrievedName");
        
        return response()->json(['status' => 'success']);
    }


    // Add methods for login, sign-up, etc. here.
}
