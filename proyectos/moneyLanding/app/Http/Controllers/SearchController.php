<?php

namespace App\Http\Controllers;

use App\Services\GlobalSearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(private readonly GlobalSearchService $search)
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request)
    {
        $term = $request->get('q', '');

        return response()->json($this->search->search($term));
    }
}
