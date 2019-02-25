<?php
/**
 * Date: 2019/2/25 Time: 14:49
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repository\Admin\AdminUserRepository;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        return view('admin.adminUser.index');
    }

    public function list(Request $request)
    {
        $perPage = (int) $request->get('limit', 50);
        $data = AdminUserRepository::list($perPage);

        return $data;
    }

    public function showAdd()
    {
        return 'add user';
    }

    public function add(Request $request)
    {

    }
}