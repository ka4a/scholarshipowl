<?php namespace App\Http\Controllers;

use App\Permission;

use Illuminate\Http\JsonResponse;
use Illuminate\Auth\AuthManager;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Base application path.
     * @return View
     */
    public function index()
    {
        return view('layout.admin');
    }

    /**
     * @return mixed
     */
    public function notFound()
    {
        return abort(404, 'API Action is not implemented!');
    }

    /**
     * @return JsonResponse
     */
    public function permissions()
    {
        return new JsonResponse(Permission::list());
    }

    /**
     * @param AuthManager $auth
     *
     * @return mixed
     */
    public function logout(AuthManager $auth)
    {
        $auth->guard('web')->logout();
        return redirect('/');
    }
}
