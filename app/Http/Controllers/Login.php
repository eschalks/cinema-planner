<?php


namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;

class Login extends Controller
{
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $user = User::firstOrNew([
            'name' => $request->get('name'),
        ]);

        if (!$user->id) {
            $user->color = $this->getNextUserColor();
            $user->save();
        }

        $userCookie = \Cookie::forever('user', $user->id);

        return redirect()
            ->route('index')
            ->withCookie($userCookie);
    }

    private function getNextUserColor()
    {
        $userCount = User::count();
        $colors = config('colors.user');
        $colorCount = count($colors);
        return $colors[$userCount % $colorCount];
    }
}
