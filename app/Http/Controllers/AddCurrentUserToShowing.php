<?php


namespace App\Http\Controllers;


use App\Models\MovieShowing;
use Illuminate\Http\Request;

class AddCurrentUserToShowing extends Controller
{
    public function __invoke(Request $request)
    {
        $showing = MovieShowing::findOrFail($request->get('showing'));
        $showing->users()->attach(\Auth::id(),
            [
                'created_at' => now(),
            ]);
        return redirect()->route('index');
    }
}
