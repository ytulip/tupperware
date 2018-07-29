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
use App\Model\Record;
use App\Model\SignRecord;
use App\Model\SyncModel;
use App\Model\User;
use App\Model\UserAddress;
use App\Util\AdminAuth;
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
        Kit::equalQuery($query,array_only(Request::all(),['province','work_no']));

        if( Request::input('is_upload') == '已上传' )
        {
            $query->where('upload_count','>',0);
        }

        if( Request::input('is_upload') == '未上传')
        {
            $query->where('upload_count','<',0);
        }



        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        $provinceList = DB::select('select distinct province from users');
        return view('admin.index')->with('paginate',$paginate)->with('provinceList',$provinceList);
    }

    public function getUserDetail()
    {
        $user = User::find(Request::input('id'));
        if( !($user instanceof  User) )
        {
            dd('无效用户');
        }
        $list = Record::where('user_id',$user->id)->where('is_delete',0)->orderBy('id','desc')->get();
        return view('admin.user_detail')->with('user',$user)->with('list',$list);
    }

    public function getRecord()
    {
        $record = Record::find(Request::input('id'));
        if( !($record instanceof  Record) )
        {
            dd('无效记录');
        }
        $user = User::find($record->user_id);
        return view('admin.record')->with('user',$user)->with('record',$record);
    }

    public function getRecords()
    {
        $provinceList = DB::select('select distinct province from users');
        $query = Record::where('is_delete',0)->orderBy('records.id','desc')->selectRaw('*,records.created_at as upload_at')->leftJoin('users','records.user_id','=','users.id');
        Kit::equalQuery($query,array_only(Request::all(),['province','work_no']));
        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        return view('admin.records')->with('paginate',$paginate)->with('provinceList',$provinceList);
    }

    public function getUserInfo()
    {
        $user = AdminAuth::user();
        return view('admin.user_info')->with('user',$user);
    }

    public function anyModifyEmail()
    {
        $email = Request::input('email');
        $user = AdminAuth::user();
        $user->email = $email;
        $user->save();
        return $this->jsonReturn(1);

    }

    public function anyModifyPassword()
    {
        $password = Request::input('password');
        $newPassword = Request::input('newPassword');


        $user = AdminAuth::user();
        if( !Hash::check($password,$user->password) )
        {
            return $this->jsonReturn(0,'原密码错误');
        }
        $user->password = Hash::make($newPassword);
//        $user->save();
        return $this->jsonReturn(1);

    }
}