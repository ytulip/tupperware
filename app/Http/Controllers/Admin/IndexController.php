<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Log\Facades\Logger;
use App\Model\Admin;
use App\Model\CashStream;
use App\Model\Essay;
use App\Model\InvitedCodes;
use App\Model\Message;
use App\Model\MonthGetGood;
use App\Model\Order;
use App\Model\Product;
use App\Model\ProductAttr;
use App\Model\SignRecord;
use App\Model\SyncModel;
use App\Model\User;
use App\Model\UserAddress;
use App\Util\CommKit;
use App\Util\DownloadExcel;
use App\Util\Kit;
use App\Util\OrderStatical;
use App\Util\SmsTemplate;
use App\Util\TotalStatical;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class IndexController extends Controller
{
    public function getHome()
    {
//        $query = 'select users.*,upload_count from users LEFT JOIN (select count(*) as upload_count,user_id from records where is_delete = 0 GROUP BY user_id) B on B.user_id = users.id';
//        User::leftJoin(DB::raw('select count(*) as upload_count,user_id from records where is_delete = 0 GROUP BY user_id) B on B.user_id = users.id'))

        $query = User::leftJoin('user_records_view','user_records_view.user_id','=','users.id');

        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        return view('admin.index')->with('paginate',$paginate);
    }
}