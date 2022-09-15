<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class LogsController extends BaseController
{
    public function adminActivityLogs(Request $request)
    {
        return $this->view('Logs - Admin Activity', 'admin.logs.adminActivity');
    }
}
