<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Log\Facades\Logger;
use App\Model\Admin;
use App\Model\Article;
use App\Model\CardBrand;
use App\Model\CashStream;
use App\Model\ClassifyPrice;
use App\Model\CodeLibrary;
use App\Model\Dealer;
use App\Model\Essay;
use App\Model\InvitedCodes;
use App\Model\Media;
use App\Model\Message;
use App\Model\MonthGetGood;
use App\Model\Order;
use App\Model\Product;
use App\Model\ProductAttr;
use App\Model\Quality;
use App\Model\Record;
use App\Model\SignRecord;
use App\Model\SubCarBrand;
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
use http\Env;
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



        if( Request::input('download') )
        {
            $list = $query->get();
            $dataList = [];
            foreach ($list as $key => $item) {
                $tempArray = array($item->work_no, $item->province,$item->upload_count?'是':'否',$item->upload_count);
                array_push($dataList, $tempArray);
            }


            $data = array(
                'title' => array('用户ID', '所属省份', '是/否上次', '上次次数'),
                'data' => $dataList,
                'name' => 'yonghuliebiao',
            );
            DownloadExcel::publicDownloadExcel($data);
            exit;
        }

        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        $hasUpload = DB::table('user_records_view')->where('upload_count','>',0)->count();
        $provinceList = DB::select('select distinct province from users');

        return view('admin.index')->with('paginate',$paginate)->with('provinceList',$provinceList)->with('total',User::count())->with('hasUpload',$hasUpload);
    }


    public function getHomeMian()
    {
        return $this->jsonReturn(1, [""=>[]]);
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
//        $record = Record::find(Request::input('id'));
//        if( !($record instanceof  Record) )
//        {
//            dd('无效记录');
//        }
//        $user = User::find($record->user_id);
        if( Request::input('type') == 'add' )
        {
            return view('admin.record')->with('record', (Object)['title'=>'', 'cover_img'=>'', 'content'=>'']);
        }
        $record = Article::find(Request::input('id'));
        if( !($record instanceof  Article) )
        {
            dd('无效记录');
        }
        return view('admin.record')->with('record',$record);
    }

    public function postRecord()
    {
//        $record = Record::find(Request::input('id'));
//        if( !($record instanceof  Record) )
//        {
//            dd('无效记录');
//        }
//        $user = User::find($record->user_id);
        $essay = Article::find(Request::input('id'));
        if (!$essay) {
            $essay = new Article();
            $essay->status = 1;
            $essay->msg_type = 1;
//            $essay->sort = DB::table('essays')->max('sort') + 1;
        }

        $essay->cover_img = Request::input('cover_image');
        $essay->title = Request::input('title');
        $essay->content = Request::input('content');

        $essay->save();
        return $this->jsonReturn(1, $essay->id);
    }

    public function getBanners()
    {
        $query = Article::where('msg_type', 3)->where('status', 1)->orderBy('id', 'desc');
        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        return view('admin.banners')->with('paginate', $paginate);
    }


    public function postDeleteBanner()
    {
        Article::where('id',Request::input('id'))->delete();
        return $this->jsonReturn(1);
    }


    public function postDeleteRecord()
    {
        Article::where('id',Request::input('id'))->delete();
        return $this->jsonReturn(1);
    }


    public function getBanner()
    {
        if( Request::input('type') == 'add' )
        {
            return view('admin.banner')->with('record', (Object)['title'=>'', 'cover_img'=>'', 'content'=>'']);
        }
        $record = Article::find(Request::input('id'));
        if( !($record instanceof  Article) )
        {
            dd('无效记录');
        }

        return view('admin.banner')->with('record',$record);
    }

    public function getRecords()
    {
        $query = Article::where('msg_type', 1)->where('status', 1)->orderBy('id', 'desc');
        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        return view('admin.records')->with('paginate',$paginate);
    }

    public function anyAddOrSaveClassify()
    {
        $id = Request::input('id', '');
        if( $id )
        {
            $item = CodeLibrary::where('id', intval($id))->first();
        }else{
            $item = new CodeLibrary();
            $item->type = 'classify';
            $item->save();
            $item->item_value = $item->id;
        }

        $item->item_name = Request::input('item_name');
        $item->year = Request::input('year');
        $item->status = Request::input('status');
        $item->save();

        return $this->jsonReturn(1);
    }

    public function anyDeleteClassify()
    {
        $id = Request::input('id', '');
        CodeLibrary::where('id', $id)->delete();
        return $this->jsonReturn(1);
    }


    public function getClassify()
    {
        $query = CodeLibrary::where('type', 'classify');
        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        return view('admin.classify')->with('paginate',$paginate);
    }


    public function postSetClassifyPrice()
    {
        $id = Request::input('id');
        $price = ClassifyPrice::where('id', intval($id))->first();
        $price->price = Request::input('price_type');
        $price->save();
        return $this->jsonReturn(1);
    }


    public function anySetClassifyYear()
    {
        $id = Request::input('id');
        $price = CodeLibrary::where('id', intval($id))->first();
        $price->year = Request::input('price_type');
        $price->save();
        return $this->jsonReturn(1);
    }

    public function getClassifyPrice()
    {
        $id = Request::input('id');
        $classify = CodeLibrary::where('type', 'classify')->where('item_value', $id)->first();

        if( ! ($classify instanceof  CodeLibrary) )
        {
            echo 'classify is not exists';
            exit;
        }

        $itemCount = ClassifyPrice::where('classify_id', $id)->count();
        if( !$itemCount ) {
            //存在则创建
            for ($i = 1; $i <= 10; $i++)
            {
                $price = new ClassifyPrice();
                $price->classify_id = $id;
                $price->level =  $i;
                $price->save();
            }
        }

        $query = ClassifyPrice::where('classify_id', $id)->leftJoin('code_library', 'code_library.item_value', '=', 'classify_id')->where('type', 'classify')->selectRaw('classify_price.*, item_name');
        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        return view('admin.classify_price')->with('paginate',$paginate);
    }


    public function getCase()
    {
        if( Request::input('type') == 'add' )
        {
            return view('admin.case')->with('record', (Object)['title'=>'', 'cover_img'=>'', 'content'=>'', 'classify'=>''])->with('classify', CodeLibrary::where('type', 'classify')->where('status', 1)->get());
        }
        $record = Article::find(Request::input('id'));
        if( !($record instanceof  Article) )
        {
            dd('无效记录');
        }

        return view('admin.case')->with('record',$record)->with('classify', CodeLibrary::where('type', 'classify')->orderBy('status', 'desc')->get());
    }

    public function getCases()
    {
        $query = Article::where('msg_type', 2)->where('status', 1)->orderBy('id', 'desc');
        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        return view('admin.cases')->with('paginate',$paginate);
    }

    public function postCase()
    {
        $essay = Article::find(Request::input('id'));
        if (!$essay) {
            $essay = new Article();
            $essay->status = 1;
            $essay->msg_type = 2;
//            $essay->sort = DB::table('essays')->max('sort') + 1;
        }

        $essay->cover_img = Request::input('cover_image');
        $essay->title = Request::input('title');
        $essay->content = Request::input('content');
        $essay->classify = Request::input('classify');

        $essay->save();
        return $this->jsonReturn(1, $essay->id);
    }


    public function postBanner()
    {
        $essay = Article::find(Request::input('id'));
        if (!$essay) {
            $essay = new Article();
            $essay->status = 1;
            $essay->msg_type = 3;
//            $essay->sort = DB::table('essays')->max('sort') + 1;
        }

        $essay->cover_img = Request::input('cover_image');
        $essay->title = Request::input('title');
        $essay->content = '';

        $essay->save();
        return $this->jsonReturn(1, $essay->id);
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

    public function anyDeleteQuality()
    {
        Quality::where('id', Request::input('id'))->delete();
        return $this->jsonReturn(1);
    }


    public function anyQualitys()
    {
        $query = Quality::orderBy('id', 'desc');


        # 搜素过滤
        if( Request::input('keyword') ) {
            $query->where('brand_card', 'like', '%' . Request::input('keyword') . '%');
        }


        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        return view('admin.qualitys')->with('paginate',$paginate);
    }

    public function anyCars()
    {
        if( isset( $_POST['id']) && $_POST['id'] )
        {
            $brand = CardBrand::where('id', $_POST['id'])->first();
            if( isset( $_POST['status']) )
            {
                $brand->status = $_POST['status'];
            }

            if( isset( $_POST['price_type']) )
            {
                $brand->price_type = $_POST['price_type'];
            }
//            return $this->jsonReturn(1);
            $brand->save();
            return $this->jsonReturn(1);
        } else {
            $query = CardBrand::where('id', '>', 1);
            $paginate = $query->where(function($query){

                if( Request::input('keyword') ) {
                    $query->where('car_brand', 'like', '%' . Request::input('keyword') . '%');
                }

            })->orderBy('id', 'asc')->paginate(env('ADMIN_PAGE_LIMIT'));
            return view('admin.cars')->with('paginate', $paginate);
        }
    }


    public function anyCar()
    {
        if( isset( $_POST['id']) && $_POST['id'] )
        {
            $brand = SubCarBrand::where('id', $_POST['id'])->first();
            if( isset( $_POST['status']) )
            {
              $brand->status = $_POST['status'];
            }

            if( isset( $_POST['price_type']) )
            {
                $brand->price_type = $_POST['price_type'];
            }
//            return $this->jsonReturn(1);
            $brand->save();
            return $this->jsonReturn(1);
        } else {
            $query = SubCarBrand::where('brand_id', Request::input('id'));
            $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
            return view('admin.car')->with('paginate', $paginate)->with('car', CardBrand::where('brand_id', Request::input('id'))->first());
        }
    }

    public function getQuality()
    {
        if( Request::input('type') == 'add' )
        {
            return view('admin.quality')->with('record', (Object)['brand_card'=>'', 'car_type'=>'', 'valid_date'=>'', 'store'=>'', 'part'=>'', 'color'=>'', 'seri_no'=>'', 'quality_year'=>'', 'product'=>'', 'mobile'=>''])->with('classify', CodeLibrary::where('type', 'classify')->where('status', 1)->get());
        }
        $record = Quality::find(Request::input('id'));
        if( !($record instanceof  Quality) )
        {
            dd('无效记录');
        }


        return view('admin.quality')->with('record',$record)->with('classify', CodeLibrary::where('type', 'classify')->orderBy('status', 'desc')->get());
    }


    public function postQuality()
    {
        $essay = Quality::find(Request::input('id'));
        if (!$essay) {
            $essay = new Quality();

            //质保单号
            while(true) {
                $number = env('TITLE')  . rand(107, 140) . rand(1000, 9999) . rand(100, 999);
                $tmp_quality = Quality::where('number', $number)->first();
                if( $tmp_quality instanceof  Quality)
                {
                    continue;
                }
                $essay->number = $number;
                break;
            }

        }

        $essay->mobile = Request::input('mobile', '');
        $essay->brand_card = Request::input('brand_card');
        $essay->car_type = Request::input('car_type');
        $essay->valid_date = Request::input('valid_date');
        $essay->store = Request::input('store');
        $essay->part = Request::input('part');
        $essay->color = Request::input('color');
        $essay->seri_no = Request::input('seri_no');
        $essay->quality_year = Request::input('quality_year');
        $essay->product = Request::input('product');


        //根据product来获取quality_year
//        $essay->quality_year = CodeLibrary::where('item_name', $essay->product)->first()->year;
        $essay->content = Request::input('content');

        $essay->save();

        //TODO 发送短信通知
        if( !Request::input('id') && $essay->mobile && env('QUALITY_SMS'))
        {
            Kit::sendInsureSms($essay->mobile, $essay->number);
        }


        return $this->jsonReturn(1, $essay->id);
    }


    public function getDealer(){
        return view('admin.dealer');
    }

    public function getDealerList(){

        $page = $_REQUEST['pageNo'];
        $page_size = $_REQUEST['pageSize'];
        $resp = [];

        //分页获取授权店列表
        $total = DB::table('dealer')->count();
        $data = DB::table('dealer')->skip(($page - 1) * $page_size)->take($page_size)->get();
//        $data =
        $resp['result'] = ['data'=>$data, 'pageSize'=>$page_size, 'pageNo'=>$page, 'totalCount'=>$total];
        return  $this->jsonReturn(1, $resp);
    }

    public function postDealer()
    {
        $essay = Dealer::find(Request::input('id'));
        if (!$essay) {
            $essay = new Dealer();
            //质保单号
        }

        $essay->name = Request::input('name');
        $essay->mobile = Request::input('mobile');
        $essay->address = Request::input('address');
        $essay->header_img = Request::input('header_img');

        if( Request::input('password') )
        {
            $essay->password = Request::input('password');
        }
        $essay->status = Request::input('status', 0);
        $essay->save();
        return  $this->jsonReturn(1);
    }

    public function getAudit(){
        return view('admin.audit');
    }

    public function anyDoAudit()
    {
//        var_dump($_REQUEST);
        $quality = Quality::where('id', $_REQUEST['id'])->first();
        if( $_REQUEST['status'] == '0' )
        {
            $quality->remark = $_REQUEST['remark'];
        }
        $quality->status = $_REQUEST['status'];
        $quality->save();

        //如果status为1，发送短信
        //TODO 发送短信通知
        if( $quality->mobile && env('QUALITY_SMS') && ($quality->status == 1) )
        {
            Kit::sendInsureSms($quality->mobile, $quality->number);
        }


        return $this->jsonReturn(1);
    }

    public function anyAuditList(){
        $page = $_REQUEST['pageNo'];
        $page_size = $_REQUEST['pageSize'];
        $resp = [];

        //分页获取授权店列表
        $total = DB::table('quality')->where('quality.status', '0')->count();
        $data = DB::table('quality')->leftJoin('dealer', 'dealer.id', '=', 'quality.dealer_id')->selectRaw('quality.*, name')->skip(($page - 1) * $page_size)->where('quality.status', '0')->take($page_size)->get();
//        $data =
        $resp['result'] = ['data'=>$data, 'pageSize'=>$page_size, 'pageNo'=>$page, 'totalCount'=>$total];
        return  $this->jsonReturn(1, $resp);
    }


    public function getMedia(){
        return view('admin.media');
    }


    public function getMediaList(){

        $page = $_REQUEST['pageNo'];
        $page_size = $_REQUEST['pageSize'];
        $resp = [];

        //分页获取授权店列表
        $total = DB::table('media')->count();
        $data = DB::table('media')->skip(($page - 1) * $page_size)->take($page_size)->get();
//        $data =
        $resp['result'] = ['data'=>$data, 'pageSize'=>$page_size, 'pageNo'=>$page, 'totalCount'=>$total];
        return  $this->jsonReturn(1, $resp);
    }

    public function postMedia()
    {
        $essay = Media::find(Request::input('id'));
        if (!$essay) {
            $essay = new Media();
            //质保单号
        }
        $essay->title = Request::input('title');
        $essay->url = Request::input('url');
        $essay->is_main = (Request::input('is_main') == 1)?1:0;
        $essay->save();

        if( $essay->is_main == 1)
        {
            Media::whereNotIn('id', [$essay->id])->update(['is_main'=>0]);
        }

        return  $this->jsonReturn(1);
    }


    public function postDeleteMedia()
    {
        $essay = Media::find(Request::input('id'));
        $essay->delete();
        return  $this->jsonReturn(1);
    }

    public function anyAddNewBrand()
    {
        //新增汽车品牌

        //step1 排重处理
        $name = Request::input('name');

        $brand = CardBrand::where('car_brand', $name)->first();
        if( $brand instanceof CardBrand )
        {
            return $this->jsonReturn(0, '该品牌已存在');
        }

        $new = new CardBrand();
        $new->car_brand = $name;
        $new->status = 0;
        $new->save();

        $new->brand_id = $new->id;
        $new->save();

        return $this->jsonReturn(1);
    }


}
