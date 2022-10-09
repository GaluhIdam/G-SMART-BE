<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Line;

class LineController extends Controller
{
    public function __invoke(Request $request)
    {
        $hangar = $request->hangar_id;
        
        $lines = Line::byHangar($hangar)
                    ->paginate(10)
                    ->withQueryString();

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data successfully',
            'data' => $lines,
        ], 200);
    }
}
