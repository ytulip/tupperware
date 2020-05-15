<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Model\Article;
use App\Model\Quality;
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
    }

    public function getCasesConfig()
    {
        $brank = [ 
            (Object)[ 'text'=>'所有品牌',  'value'=> 0 ],
            (Object)[ 'text'=>'奔驰',  'value'=> 1 ],
            (Object)[ 'text'=>'宝马',  'value'=> 2 ],
            (Object)[ 'text'=>'奥迪',  'value'=> 3 ],
            (Object)[ 'text'=>'其它',  'value'=> 99 ]
        ];
        $classify = [ 
            (Object)[ 'text'=>'所有系列',  'value'=> 0 ],
            (Object)[ 'text'=>'亮光系列',  'value'=> 1 ],
            (Object)[ 'text'=>'珍珠白变/电光白变系列',  'value'=> 2 ],
            (Object)[ 'text'=>'钢琴/高亮系列',  'value'=> 3 ],
            (Object)[ 'text'=>'陶瓷系列/电光系列',  'value'=> 4 ],
            (Object)[ 'text'=>'亚面金属系列',  'value'=> 5 ],
            (Object)[ 'text'=>'樱语系列',  'value'=> 6 ],
            (Object)[ 'text'=>'金属/珠光系列',  'value'=> 7 ],
            (Object)[ 'text'=>'超哑系列/原厂系列',  'value'=> 8 ],
            (Object)[ 'text'=>'珊瑚系列',  'value'=> 9 ],
            // (Object)[ 'text'=>'变色龙系列',  'value'=> 10 ],
            // (Object)[ 'text'=>'砖石系列',  'value'=> 11 ],
            // (Object)[ 'text'=>'镭射闪系列',  'value'=> 12 ],
            // (Object)[ 'text'=>'彩虹电镀系列',  'value'=> 13 ],
            // (Object)[ 'text'=>'闪耀、星空系列',  'value'=> 14 ],
            // (Object)[ 'text'=>'3D炫彩',  'value'=> 15 ],
            // (Object)[ 'text'=>'亚面电镀金属',  'value'=> 16 ],
            // (Object)[ 'text'=>'自修复系列',  'value'=> 17 ],
            // (Object)[ 'text'=>'砖石白变系列',  'value'=> 18 ],
            // (Object)[ 'text'=>'梦幻系列',  'value'=> 19 ],
            (Object)[ 'text'=>'其它',  'value'=> 99 ]
        ];

        $color = [ 
            (Object)[ 'text'=>'所有颜色',  'value'=> 0 ],
            (Object)[ 'text'=>'红色',  'value'=> 1 ],
            (Object)[ 'text'=>'橙色',  'value'=> 2 ],
            (Object)[ 'text'=>'黄色',  'value'=> 3 ],
            (Object)[ 'text'=>'绿色',  'value'=> 4 ],
            (Object)[ 'text'=>'蓝色',  'value'=> 5 ],
            (Object)[ 'text'=>'紫色',  'value'=> 6 ],
            (Object)[ 'text'=>'灰色',  'value'=> 7 ],
            (Object)[ 'text'=>'白色',  'value'=> 8 ],
            (Object)[ 'text'=>'黑色',  'value'=> 9 ],
            (Object)[ 'text'=>'粉色',  'value'=> 10 ],
            // (Object)[ 'text'=>'银色',  'value'=> 11 ],
            // (Object)[ 'text'=>'蒂芙尼',  'value'=> 12 ],
            // (Object)[ 'text'=>'玫瑰金',  'value'=> 13 ],
            // (Object)[ 'text'=>'极光色',  'value'=> 14 ],
            (Object)[ 'text'=>'其它',  'value'=> 99 ]
        ];;
        return $this->jsonReturn(1,[
            "brank"=> $brank,
            "classify"=> $classify,
            "color"=> $color
        ]);
    }

    public function getDoFavor()
    {
        $id = \Illuminate\Support\Facades\Request::input('id');
        $detail = Article::find($id);
        $new_count = $detail->favor + 1;
        Article::where('id', '=', $id)->update(['favor'=>$new_count]);
        return $this->jsonReturn(1, $new_count);
    }


    public function getDoEyes()
    {
        $id = \Illuminate\Support\Facades\Request::input('id');
        $detail = Article::where('id', '=', $id)->first();
        $new_count = $detail->eyes + 1;
        Article::where('id', '=', $id)->update(['eyes'=>$new_count]);
        return $this->jsonReturn(1, $new_count);
    }

    /**
     * 所有文章列表
     */
    public function getArticles()
    {

    }

    public function getHomeMain()
    {


        $news = [['id'=>'3', 'url'=>env('IMAGE_PREFIX'). '/images/article.jpg', 'title'=>'RAYA FILM开启全国代理招募计划，还不赶紧来戳一下？'], ['id'=>'4', 'url'=>env('IMAGE_PREFIX'). '/images/easy2.jpg', 'title'=>'RAYA FILM产品系列详细介绍，快来了解一下。'] , ['id'=>'5', 'url'=>env('IMAGE_PREFIX'). '/images/easy3.jpg', 'title'=>'RAYA FILM已于2020盛大开业，落户成都']];

        $news = Article::where('status',1)->where('msg_type', 1)->limit(3)->orderBy('id', 'desc')->get();

        foreach ($news as $key=>$item)
        {
            $item->publish_time = date('m-d', strtotime($item->created_at));
        }



        return $this->jsonReturn(1, ["banners"=>[['id'=>'1', 'url'=>env('IMAGE_PREFIX') . '/images/raya2.jpg'],['id'=>'2', 'url'=>env('IMAGE_PREFIX'). '/images/raya1.jpg']], "recommend_case"=>[['id'=>'1', 'url'=>env('IMAGE_PREFIX') . '/images/case1.jpg', 'text'=>'奥迪S|抹茶绿'],['id'=>'2', 'url'=>env('IMAGE_PREFIX'). '/images/case2.jpg', 'text'=>'特斯拉model s|电光绿'], ['id'=>'3', 'url'=>env('IMAGE_PREFIX'). '/images/case3.jpg', 'text'=>'奔驰S级|高级灰']], 'news'=>$news]);
    }

    public function getAllCases()
    {
        $list = [['id'=>'1', 'url'=>env('IMAGE_PREFIX') . '/images/case1.jpg', 'text'=>'奥迪S|抹茶绿', 'brand'=>1, 'classify'=>2, 'color'=>3],['id'=>'2', 'url'=>env('IMAGE_PREFIX'). '/images/case2.jpg', 'text'=>'特斯拉model s|电光绿', 'brand'=>3, 'classify'=>2, 'color'=>1], ['id'=>'3', 'url'=>env('IMAGE_PREFIX'). '/images/case3.jpg', 'text'=>'奔驰S级|高级灰', 'brand'=>2, 'classify'=>3, 'color'=>1]];
        return $this->jsonReturn(1, $list);
    }

    public function postCallback()
    {
        $data = file_get_contents("php://input");
        Log::info($data);
        return $this->jsonReturn(1);
    }


    /**
     * 精品案例
     */
    public function getRecommendCase()
    {
        return $this->jsonReturn(1, [['id'=>'1', 'url'=>env('IMAGE_PREFIX') . '/images/raya2.jpg', 'text'=>''],['id'=>'2', 'url'=>env('IMAGE_PREFIX'). '/images/raya1.jpg', 'text'=>'']]);
    }

    public function getArticle()
    {
        $id = \Illuminate\Support\Facades\Request::input('id');
        $detail = Article::find($id);
        $detail->publish_time = date('m-d', strtotime($detail->created_at));
        return $this->jsonReturn(1, $detail);
    }

    public function getQuality()
    {
        $keyword = $id = \Illuminate\Support\Facades\Request::input('keyword');
        $list = Quality::where('mobile', 'like' ,"%$keyword%")->orWhere('mobile', 'like' ,"%%")->orderBy('id', 'desc')->get();
        return $this->jsonReturn(1, $list);
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