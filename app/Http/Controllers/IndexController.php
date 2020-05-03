<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Model\Record;
use App\Model\User;
use App\Util\AdminAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class IndexController extends Controller
{
    public function getIndex()
    {
        if(!AdminAuth::check()){
            return Redirect::to('index/login');
        }

        return view('product_list');
        //获取list列表
//        $list = Record::where('user_id',Auth::id())->where('is_delete',0)->orderBy('id','desc')->get();
//        $recordArr = [];
//        foreach ( $list as $key=>$record)
//        {
//            $urls = [];
//            if($record->img1) $urls[] = $record->img1;
//            if($record->img2) $urls[] = $record->img2;
//            $recordArr[] = ["urls"=>$urls,"attach_msg"=>[
//                'id'=>$record->id,
//                'day'=>date('d',strtotime($record->created_at)),
//                'm'=>date('m',strtotime($record->created_at)),
//                'his'=>date('h:i:s',strtotime($record->created_at))
//            ]];
//        }
//        return view('index')->with('recordArr',$recordArr);
    }


    public function getHomeMain()
    {


        $news = [['id'=>'3', 'url'=>env('IMAGE_PREFIX'). '/images/article.jpg', 'title'=>'RAYA FILM开启全国代理招募计划，还不赶紧来戳一下？']];
        return $this->jsonReturn(1, ["banners"=>[['id'=>'1', 'url'=>env('IMAGE_PREFIX') . '/images/raya2.jpg'],['id'=>'2', 'url'=>env('IMAGE_PREFIX'). '/images/raya1.jpg']], "recommend_case"=>[['id'=>'1', 'url'=>env('IMAGE_PREFIX') . '/images/case1.jpg', 'text'=>'奥迪S|抹茶绿'],['id'=>'2', 'url'=>env('IMAGE_PREFIX'). '/images/case2.jpg', 'text'=>'特斯拉model s|电光绿'], ['id'=>'3', 'url'=>env('IMAGE_PREFIX'). '/images/case3.jpg', 'text'=>'奔驰S级|高级灰']], 'news'=>$news]);
    }


    /**
     * 精品案例
     */
    public function getRecommendCase()
    {
        return $this->jsonReturn(1, [['id'=>'1', 'url'=>env('IMAGE_PREFIX') . '/images/raya2.jpg', 'text'=>''],['id'=>'2', 'url'=>env('IMAGE_PREFIX'). '/images/raya1.jpg', 'text'=>'']]);
    }

    public function getLogin()
    {
        return view('login');
    }

    public function postLogin()
    {
        $user = User::where('work_no',\Illuminate\Support\Facades\Request::input('work_no'))->first();
        if( !($user instanceof  User) )
        {
            return $this->jsonReturn(0,'工号不存在');
        }

        //尝试用用户ID登录
//        if(Auth::attempt(['id' => Request::input('phone'), 'password' => Request::input('password')]))
        Auth::loginUsingId($user->id);

        return $this->jsonReturn(1);
    }

    public function postAlbumImage()
    {
//        if( !AdminAuth::check() )
//        {
//            return $this->jsonReturn(0,'用户信息丢失');
//        }

        $id = \Illuminate\Support\Facades\Request::input('id');

        if( $id )
        {
            $user = User::find($id);
        } else
        {
            $user = new User();
        }


        $user->work_no =  \Illuminate\Support\Facades\Request::input('productId');
        $user->province =  \Illuminate\Support\Facades\Request::input('productName');
        $user->quantity =  \Illuminate\Support\Facades\Request::input('quantity');
        $user->income =  \Illuminate\Support\Facades\Request::input('income');
        $user->outcome =  \Illuminate\Support\Facades\Request::input('outcome');
        $user->bit =  \Illuminate\Support\Facades\Request::input('bit');

        $user->save();

        return $this->jsonReturn(1);

    }

    public function anyDelete()
    {
        $id = \Illuminate\Support\Facades\Request::input('id');
        $record = Record::find($id);
        if( !($record instanceof  Record) )
        {
            return $this->jsonReturn(0);
        }

        $record->is_delete = 1;
        $record->save();

        return $this->jsonReturn(1);
    }


    public function anyDeleteItem()
    {
        $id = \Illuminate\Support\Facades\Request::input('id');
        $user = User::find($id);
        $user->delete();

        return $this->jsonReturn(1);
    }


    public function anyList()
    {
        return $this->jsonReturn(1,User::get());
    }
}