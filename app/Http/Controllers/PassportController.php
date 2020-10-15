<?php

namespace App\Http\Controllers;

use App\Log\Facades\Logger;
use App\Model\Essay;
use App\Model\InvitedCodes;
use App\Model\Order;
use App\Model\Product;
use App\Model\UserAddress;
use App\Util\AdminAuth;
use App\Util\Curl;
use App\Util\SmsTemplate;
use App\User;
use App\Util\DealString;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class PassportController extends Controller
{
    public function anyLoginOut()
    {
        Session::flush();
        return Redirect::to('/passport/login');
    }

    public function getAdminLoginOut()
    {
        Session::flush();
        return Redirect('/passport/admin-login');
    }

    public function getAdminLogin()
    {
        return view('admin.login');
    }


    public function postAdminLogin()
    {
        $res = AdminAuth::attempt(Request::all());
        return $res;
    }

    //制造汽车品牌
    public function anyMakeBrand()
    {

    }
}