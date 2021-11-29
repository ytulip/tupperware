<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Model\Article;
use App\Model\CardBrand;
use App\Model\ClassifyPrice;
use App\Model\Dealer;
use App\Model\DomainExpires;
use App\Model\SubCarBrand;
use App\Model\CodeLibrary;
use App\Model\Quality;
use App\Model\Record;
use App\Model\php;
use App\Model\User;
use App\Util\AdminAuth;
use App\Util\Kit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class IndexController extends Controller
{

    public function anyMakeBrand()
    {
        $content = file_get_contents('http://tp.cc/CarApi/index.php?type=brand&pagesize=1000');
        $content = json_decode($content);
        foreach ($content->info as $item)
        {
            $brand = new CardBrand();
            $brand->car_brand = $item->name;
            $brand->img = $item->img;
            $brand->prantid = 0;
            $brand->firstletter = $item->firstletter;
            $brand->save();
        }
        echo 123;
    }

    public function anySearchPrice()
    {
        $type = \Illuminate\Support\Facades\Request::input('type');
        $classify = \Illuminate\Support\Facades\Request::input('classify');
        $brand = SubCarBrand::where('sub_card_brand', $type)->orWhere('id', $type)->first();
        if( !($brand instanceof  SubCarBrand) )
        {
            return $this->jsonReturn(1, '请咨询客服');
        }

        if( !$brand->price_type )
        {
            return $this->jsonReturn(1, '请咨询客服');
        }

        //获取classify_id
        $classifyObj = CodeLibrary::where('item_name', $classify)->orWhere('id', $classify)->first();

        $price = ClassifyPrice::where('classify_id', $classifyObj->item_value)->where('level', $brand->price_type)->first();


        return $this->jsonReturn(1, $price->price);

    }


    public function anySetCarType()
    {
        set_time_limit(3600);
        date_default_timezone_set('PRC');
// 读取excel文件
        try {
//            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
//            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
//            $objPHPExcel = $objReader->load($inputFileName);
            $fileName = 'D:\22.xls';
            $fileType = \PHPExcel_IOFactory::identify($fileName);
            $objReader = \PHPExcel_IOFactory::createReader($fileType);

//            $sheetName = array("2年级","3年级");//指定sheet名称
//            $objReader->setLoadSheetsOnly($sheetName);//指定加载的sheet
            $objPHPExcel = $objReader->load($fileName);
            // 确定要读取的sheet，什么是sheet，看excel的右下角，真的不懂去百度吧
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

//            var_dump($highestRow);
//            var_dump($highestColumn);

            for ($row = 1; $row <= $highestRow; $row++){
// Read a row of data into an array
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
//这里得到的rowData都是一行的数据，得到数据后自行处理，我们这里只打出来看看效果
//                echo '<pre>';
                $rowData = $rowData[0];
//                var_dump($rowData);
//                echo "<br>";
//                var_dump($rowData[10]);
//                var_dump($rowData[7]);
                $sub = SubCarBrand::where('id', $rowData[10])->first();
//                var_dump($sub);

                $sub->price_type = $rowData[7];
                $sub->save();
            }

            echo '44566';




        } catch(Exception $e) {

        }


    }


    public function anyRefreshExpires()
    {
        set_time_limit(3600);
        ini_set('memory_limit', '1024M');
        ignore_user_abort(true);
        $list = DomainExpires::get();
        foreach ($list as $domain)
        {
            //如果
            $API_KEY 	= "dLiMJQdQkUoQ_8y2uBgSMzwRfuDkPwA51WM";
            $API_SECRET = "8y2zcXPkfWx4fnufXubfLZ";

            $url = "https://api.godaddy.com/v1/domains/". $domain->domain_name;
//youtubemyvideos.com
            $header = array(
                'Authorization: sso-key '.$API_KEY.':'.$API_SECRET.''
            );
            $ch = curl_init();
            $timeout=60;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $result = curl_exec($ch);
            curl_close($ch);
//            echo $result;
            $domainDetail = json_decode($result, true);


//            print_r($domainDetail);
//            var_dump($domainDetail);
            if( isset($domainDetail['expires']) )
            {
                $domain->status = 1;
                $domain->expires = substr($domainDetail['expires'], 0, 10);
            } else {
                $domain->status = 2;
            }
            $domain->result = $result;
            $domain->save();
//            $endDate = date('Y-m-d', strtotime('+7 day'));
//            $domains = json_decode($_REQUEST['domains']);

//            $list = DomainExpires::whereIn('domain_name', $domains)->where('expires', '<', $endDate)->get();
//            return $this->jsonReturn(1, $list);
        }

        echo 123;
    }

    public function anyDomainExpires()
    {
        $domainStr = 'epicka.vip,titiroba.vip,lbell.top,grde-directservice.vip,tititaus.com,oryto-onlineservice.com,abaralaservice.vip,hope-directservice.com,fkantservice.vip,digi-lifeservice.cloud,tititaus.club,lbell.me,dostyle.me,jukuner.vip,beswill.club,oreato.vip,garsumlabs.vip,widenbit.vip,comfoarray.store,ougrand.me,liuxuecheng.club,belopezz.vip,vanpad.vip,ayyie.live,eyocean.vip,evcharger.vip,somora.vip,bathebright.vip,sminiker.me,nonecho.life,jukuner.club,meisohua.vip,amir.cool,finrezio.store,519fitness.vip,naturalfeelings.online,dimore.life,othway.vip,ponlcy.store,5mayi.online,allroad.vip,roziapro.life,camecho.vip,opiqcey.cool,cantle.vip,anstop.online,cwlakon.world,zooron.vip,trubetter.vip,jison21.store,gooday.store,zooron.live,raugee.online,yqtyqs.vip,devandy.vip,mulsanne-hair.store,ornateocean.vip,alise.life,kitchenhoney.life,1000gg.vip,avhack.vip,rosoz.vip,zooron.life,sambraid.me,buyer-support.vip,accocam.life,zy-tam.vip,maying.vip,benyar.vip,s-charma.club,shinetao.vip,jauntis.vip,besdersec.vip,tsol.vip,newleray.online,lenitech.vip,aftersale.vip,cosyhomestyle.vip,easysmx01.site,korin.vip,imden.vip,smilco.vip,cdyle.vip,turelar.vip,jenuos.store,jomst.vip,ankace.store,hecmoks.vip,idoo.club,pido.club,zoeson.online,lxjys.online,futin.club,chooseen.store,comezy.store,hiadventurer.club,latme.club,nomadco.xyz,landwind.site,zhongyiwujin.club,amzgiftcard.club,jhserdstore.online,beartwo.online,crespostore.online,smarkey.online,amanbo.site,mrgkhle.online,sdghaw.online,viden.club,segoal.online,comezy.online,taythi.club,heatedjacket.online,diykit.site,lomendio.xyz,gyroor.club,wifort.club,te-rich.club,fullclean.club,lbye.club,no1accessory.store,eqss.club,ydohome.store,mobkitfp.site,drscreen.club,bethone.store,ivosy.store,samker.store,greenoble.store,lohowo.store,thxtoms.store,mnopq.store,dam-e.store,insmy.club,powlaken.store,zhenpony.online,elegantcoast.com,comlife.club,amzgo.club,movewithyou.live,artbirdfeeder.site,sailhome.store,apzek.club,runnzer.live,umthink.store,gzunelic.vip,aftersales.vip,startailgating.club,abbyhouse.online,tutudreams.site,tanzendan.co,minilife.online,Spofe.vip,bligli.vip,AOAUUKO.vip,larbois.online,yuzhik.xyz,gideal.club,philex.online,aeduqu.store,yzzcollection.online,taiyoyuyu.xyz,kaifeng.store,Siducal.store,Siducal.club,insome.xyz,fuovt.store,hytomato.club,ejservice.life,avenco.club,tutudreams.co,techwood.club,amrobt.club,ulico.store,EOVOLA.club,enlove.site,toqicam.club,mokaloo.online,xocity.site,traely.club,freegift.life,luohecam.club,amrobt.life,earller.live,genofo.online,colorfairy.online,zomao.store,AmazLit.support,Aynone.club,CalMyotis.club,yolife.vip,velovyo.vip,OTHWAY.shop,Robomann.club,cukwily.vip,yolife.store,pinkiwine.vip,bextcok.vip,INSOME.club,aynone.site,qogir.live,richsky.vip,luckyclover.vip,ergomaker.club,aynone.fun,CIXI.website,Aynone.xyz,toqi.club,luohe.fun,CIXI.site,eyekeshe.vip,vounel.vip,ptcltraps8.club,didisky.vip,shengkou.vip,teetwenties.vip,ysj123.vip,mindbeast.vip,veshely.vip,ouyide.vip,proenrichment.vip,retope.vip,swancrown.vip,wodeeou.club,nextamz.vip,jwcfitness.vip,SOIOANS.site,Cixicam.club,ielec.vip,wubowujing.me,windflyer.vip,aojoys.vip,hobest.vip,delfin.live,ohyeahtoyz.vip,fitfirst.club,larkbird.vip,arafuna.vip,smartoto.vip,AOBOTECH.online,mogotoyz.vip,geenker.vip,xsiato.vip,nebitestore.vip,gooleen.shop,aynone.online,joomfeen.vip,alonsoo.vip,rocxf.vip,NORMIToyz.vip,freenics.vip,cydzsw.vip,mjysto.vip,alptopsecurity.vip,Awolf.club,haofilm-bt.club,toqi.online,hxy.guru,lucalda-us.live,CIXI.online,betterbuy.vip,lpow.vip,metene.vip';

        $domainArr = explode(',', $domainStr);
        $i = 0;
        foreach ($domainArr as $item) {
            $domain = DomainExpires::where('domain_name', $item)->first();

            if( $domain->status == 1 || $domain->status == 2 )
            {
                continue;
            }

//           $domain = new DomainExpires();
//           $domain->domain_name = $item;
//           $domain->save();

            $API_KEY 	= "dLiMJQdQkUoQ_8y2uBgSMzwRfuDkPwA51WM";
            $API_SECRET = "8y2zcXPkfWx4fnufXubfLZ";

            $url = "https://api.godaddy.com/v1/domains/".$item;
//youtubemyvideos.com
            $header = array(
                'Authorization: sso-key '.$API_KEY.':'.$API_SECRET.''
            );
            $ch = curl_init();
            $timeout=60;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $result = curl_exec($ch);
            curl_close($ch);
//            echo $result;
            $domainDetail = json_decode($result, true);


//            print_r($domainDetail);
//            var_dump($domainDetail);
            if( isset($domainDetail['expires']) )
            {
                $domain->status = 1;
                $domain->expires = substr($domainDetail['expires'], 0, 10);
            } else {
                $domain->status = 2;
            }
            $domain->result = $result;
            $domain->save();
//            break;
        }

        echo 123;
        exit;
    }

    public function anyDomainExpiresList()
    {
        $endDate = date('Y-m-d', strtotime('+7 day'));
        $domains = json_decode($_REQUEST['domains']);

        $list = DomainExpires::whereIn('domain_name', $domains)->where('expires', '<', $endDate)->get();
        return $this->jsonReturn(1, $list);
    }

    public function anyDomainMessage()
    {
        Log::info('域名到期提醒');
        //过期时间
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime('+7 day'));

        $list = DB::select('select user_id, domain_expires.domain_name, email, domain_expires.expires from domain_expires LEFT JOIN t_domain_name_record on domain_expires.domain_name = t_domain_name_record.domain_name left join user on t_domain_name_record.user_id = `user`.id where domain_expires.expires >= "' . $startDate.'" and domain_expires.expires < " '. $endDate.' "');
        Log::info($list);
        foreach ( $list as $item)
        {
            if( $item->email ) {
                $this->anyTestEmail($item->email, $item->domain_name, $item->expires);
            }
        }

    }

    public function anyTestEmail($email, $domainName, $expireDate)
    {
//        $email = 'yi@zhuyan.me';
        $url = 'https://api.fengdx.com/mail-sms/message/send/mail';
        $data = [
            "merchant_id"=>"136970450289819648",
            "subject"=>'域名到期提醒',
            "to"=>$email,
            "nonce"=>"123456789",
            "body"=>'您的域名' . $domainName . '将于' . $expireDate . '到期，为了不影响买家注册和上评请尽快登录蜂大侠管理后台进行续费，续费方式：左侧菜单>网站管理>网站列表'
        ];
        Log::info('请求数据');
        Log::info($data);
        $sign = Kit::MakeSign($data);
        $data['sign'] = $sign;
        return Kit::curl_post($url,$data);
    }


    public function anySubMakeBrand()
    {
        set_time_limit(3600);
        $content = file_get_contents('http://tp.cc/CarApi/index.php?type=series&pagesize=2000');
        $content = json_decode($content);
        foreach ($content->info as $item)
        {
            $brand = new SubCarBrand();
            $brand->car_brand = $item->name;
            $brand->brand_id = $item->brand_id;
            $brand->firstletter = $item->firstletter;
            $brand->save();
        }

        echo 445;
    }

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
        ];
        $classify = [
            (Object)[ 'text'=>'所有系列',  'value'=> 0 ],

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
        ];

        // 获得汽车品牌
//        CardBrand::where()

        // 获得产品系列

        $brankList = CardBrand::where('prantid', 0)->get();
        foreach( $brankList as $key=>$item )
        {
//            $item->text = $item->car_brand;
//            $item->value = $item->id;
            $brank[] = (Object)[ 'text'=>$item->car_brand,  'value'=> $item->id ];
        }
        //系列列表
        $classifyList = CodeLibrary::where('type', 'classify')->get();
//        return $this->jsonReturn(1, ['car_list'=>$carList, 'classify'=>$list]);

        foreach ( $classifyList as $key=>$item)
        {
//            $item->text = $item->item_name;
//            $item->value = $item->item_value;
            $classify[] = (Object)[ 'text'=>$item->item_name,  'value'=> $item->item_value ];
        }


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
        $news = Article::where('status',1)->where('msg_type', 1)->limit(3)->orderBy('id', 'desc')->get();

        foreach ($news as $key=>$item)
        {
            $item->publish_time = date('m-d', strtotime($item->created_at));
        }

        return $this->jsonReturn(1, $news);
    }

    public function getHomeMain()
    {
        $banners = Article::where('status',1)->where('msg_type', 3)->orderBy('id', 'desc')->get();
        foreach ($banners as $key=>$item)
        {
            $item->url = env('IMAGE_PREFIX') . $item->cover_img;;
        }

        $news = Article::where('status',1)->where('msg_type', 1)->limit(3)->orderBy('id', 'desc')->get();

        foreach ($news as $key=>$item)
        {
            $item->publish_time = date('m-d', strtotime($item->created_at));
            $item->url = env('IMAGE_PREFIX') . $item->cover_img;;
            $item->cover_img = env('IMAGE_PREFIX') . $item->cover_img;;
        }

        $cases = Article::where('status',1)->where('msg_type', 2)->limit(3)->orderBy('id', 'desc')->get();
        foreach ($cases as $key=>$item)
        {
            $item->publish_time = date('m-d', strtotime($item->created_at));
            $item->url = env('IMAGE_PREFIX') . $item->cover_img;;
            $item->cover_img = env('IMAGE_PREFIX') . $item->cover_img;;
        }

        return $this->jsonReturn(1, ["banners"=>$banners, "recommend_case"=>$cases, 'news'=>$news]);
    }

    public function getAllCases()
    {
        $news = Article::where('status',1)->where('msg_type',2)->orderBy('id', 'desc')->get();

        foreach ($news as $key=>$item)
        {
            $item->publish_time = date('m-d', strtotime($item->created_at));
            $item->url = env('IMAGE_PREFIX') . $item->cover_img;;
            $item->cover_img = env('IMAGE_PREFIX') . $item->cover_img;;
        }
        return $this->jsonReturn(1, $news);
    }

    public function postCallback()
    {
        $data = file_get_contents("php://input");
        Log::info($data);
        return $this->jsonReturn(1);
    }


    public function anyAddQua()
    {
        $essay = Quality::find(\Illuminate\Support\Facades\Request::input('id'));
        if (!$essay) {
            $essay = new Quality();

            //质保单号
            while(true) {
                $number = 'APAPPF' . rand(107, 140) . rand(1000, 9999) . rand(100, 999);
                $tmp_quality = Quality::where('number', $number)->first();
                if( $tmp_quality instanceof  Quality)
                {
                    continue;
                }
                $essay->number = $number;
                break;
            }
        }

        $essay->mobile = \Illuminate\Support\Facades\Request::input('mobile', '');
        $essay->brand_card = \Illuminate\Support\Facades\Request::input('brand_card');
        $essay->car_type = \Illuminate\Support\Facades\Request::input('car_type');
        $essay->valid_date = \Illuminate\Support\Facades\Request::input('date');
        $essay->store = \Illuminate\Support\Facades\Request::input('store');
        $essay->part = \Illuminate\Support\Facades\Request::input('part');
        $essay->color = \Illuminate\Support\Facades\Request::input('color');
        $essay->seri_no = \Illuminate\Support\Facades\Request::input('seri_no');
        $essay->product = \Illuminate\Support\Facades\Request::input('product');

        $quality_year = \Illuminate\Support\Facades\Request::input('quality_year', '');
        if( $quality_year )
        {
            $essay->quality_year = CodeLibrary::where('item_name', $essay->product)->first()->year;
        }


//        $essay->quality_year = Request::input('quality_year');
//        $essay->product = Request::input('product');

        //根据product来获取quality_year
//        $essay->quality_year = CodeLibrary::where('item_name', $essay->product)->first()->year;
        $essay->content = \Illuminate\Support\Facades\Request::input('content');
        $essay->status = 0;
        $essay->dealer_id = \Illuminate\Support\Facades\Request::input('dealer_id');
        $essay->save();


        return $this->jsonReturn(1, $essay->id);
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

        //文章图片路径替换
        $detail->content = str_replace('/ueditor/php', env('IMAGE_PREFIX'). '/ueditor/php', $detail->content);

        return $this->jsonReturn(1, $detail);
    }

    public function getQuality()
    {
        $keyword = $id = \Illuminate\Support\Facades\Request::input('keyword');
        $list = Quality::where('mobile', '=' ,"$keyword")->orWhere('brand_card', '=' ,"$keyword")->orWhere('id', '=', $keyword)->orderBy('id', 'desc')->get();


        //文章图片路径替换
//        $detail->content = str_replace('/ueditor/php', env('IMAGE_PREFIX'). '/ueditor/php', $detail->content);

        foreach ($list as $key=>$detail)
        {
            //文章图片路径替换
            $detail->content = str_replace('/ueditor/php', env('IMAGE_PREFIX'). '/ueditor/php', $detail->content);
        }

        if( count($list) )
        {
            return $this->jsonReturn(1, $list);
        } else
        {
            return $this->jsonReturn(0, '查询不到质保信息');
        }
    }

    public function getSearch()
    {
        //只根据标题搜案例
        $keyword = $id = \Illuminate\Support\Facades\Request::input('keyword');
        $list = Article::where('title', 'like' ,"%$keyword%")->where('status', 1)->where('msg_type', 2)->orderBy('id', 'desc')->get();
        foreach ($list as $key=>$item)
        {
            $item->cover_img = env('IMAGE_PREFIX') . $item->cover_img;
        }
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

    public function anyAlbumImage()
    {
        $files = \Illuminate\Support\Facades\Request::file('images');
        $count = count($files);
        if ($count != 1) {
            return json_encode(["status" => 0, "desc" => "文件个数异常"], JSON_UNESCAPED_UNICODE);
        }

        $imagesInfo = [];
        foreach ($files as $key => $file) {
            $imageExtension = $file->getClientOriginalExtension(); //上传文件的后缀
            //文件后缀转小写
            $imageExtension = strtolower($imageExtension);
            if (!in_array($imageExtension, ['jpg', 'png', 'gif', 'jpeg'])) {
                return json_encode(['status' => 0, 'desc' => '文件格式异常'], JSON_UNESCAPED_UNICODE);
            }
            $imagesInfo[] = $imageSaveName = bin2hex(base64_encode(time() . $key)) . '.' . $imageExtension; //文件保存的名字
        }

        $res = [];
        $result = false;
        foreach ($files as $key => $file) {
            //$iamgeTempPath = $file->getRealPath(); //临时文件的绝对路径
            if ($file->move('imgsys', $imagesInfo[$key])) {
                $result = true;
                $res[] = '/imgsys/' . $imagesInfo[$key];
            } else {
                $result = false;
                break;
            }
        }
        if ($result) {
            return json_encode(['status' => 1, 'data' => $res]);
        } else {
            return json_encode(['status' => 0, 'desc' => "上传异常"], JSON_UNESCAPED_UNICODE);
        }

    }




    public function anyAlbumImageMedia()
    {
        $files = \Illuminate\Support\Facades\Request::file('picture');

        $count = count($files);
        if ($count != 1) {
            return json_encode(["status" => 0, "desc" => "文件个数异常"], JSON_UNESCAPED_UNICODE);
        }

        $imagesInfo = [];
        foreach ($files as $key => $file) {
            $imageExtension = $file->getClientOriginalExtension(); //上传文件的后缀
            //文件后缀转小写
            $imageExtension = strtolower($imageExtension);
            if (!in_array($imageExtension, ['mp4', 'avi', 'mkv'])) {
                return json_encode(['status' => 0, 'desc' => '文件格式异常'], JSON_UNESCAPED_UNICODE);
            }
            $imagesInfo[] = $imageSaveName = bin2hex(base64_encode(time() . $key)) . '.' . $imageExtension; //文件保存的名字
        }

        $res = [];
        $result = false;
        foreach ($files as $key => $file) {
            //$iamgeTempPath = $file->getRealPath(); //临时文件的绝对路径
            if ($file->move('imgsys', $imagesInfo[$key])) {
                $result = true;
                $res[] = '/imgsys/' . $imagesInfo[$key];
            } else {
                $result = false;
                break;
            }
        }
        if ($result) {
            return json_encode(['status' => 1, 'data' => $res]);
        } else {
            return json_encode(['status' => 0, 'desc' => "上传异常"], JSON_UNESCAPED_UNICODE);
        }

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

    /**
     * 配额
     */
    public function anyQuotation()
    {
        $carList = CardBrand::where('prantid', 0)->where('status', 1)->get();
        foreach( $carList as $key=>$item )
        {
            $item->subList = SubCarBrand::where('brand_id', $item->brand_id)->where('status', 1)->get();
        }
        //系列列表
        $list = CodeLibrary::where('type', 'classify')->get();
        return $this->jsonReturn(1, ['car_list'=>$carList, 'classify'=>$list]);
    }


    /*供应商信息*/
    public function anyDealerInfo()
    {
        $dealer_id = $_REQUEST['dealer_id'];
        $dealer = Dealer::where('id', $dealer_id)->first();
        return $this->jsonReturn(1);
    }


    public function anyDealerLogin()
    {
        $deader = Dealer::where('mobile', $_REQUEST['mobile'])->first();
        if( !($deader instanceof  Dealer) )
        {
            return $this->jsonReturn(0, '手机号不存在');
        }

        if( $deader->password != $_REQUEST['password'] )
        {
            return $this->jsonReturn(0, '密码不存在');
        }

        if( !$deader->status )
        {
            return $this->jsonReturn(0, '已被禁用');
        }

        return $this->jsonReturn(1, $deader->id);
    }

    public function anyDealerQualiInfo(){
        $dealer_id = $_REQUEST['dealer_id'];
        $dealer = Dealer::where('id', $dealer_id)->first();

//            this.pending_total_amount = res.data.pending_total_amount
//          this.amount_all = res.data.amount_all;
//          this.count_all = res.data.count_all;
//          this.count_current_month = res.data.count_current_month;
//          Toast.clear()


        return $this->jsonReturn(1,[
            'dealer'=> $dealer,
            'audit'=> Quality::where('dealer_id', $dealer_id)->where('status', 0)->count(),
            'total'=> Quality::where('dealer_id', $dealer_id)->where('status', 1)->count(),
            'month_total'=> Quality::where('dealer_id', $dealer_id)->where('status', 1)->where('valid_date', '>=', date('Y-m-01'))->count(),
            'year_total'=> Quality::where('dealer_id', $dealer_id)->where('status', 1)->where('valid_date', '>=', date('Y-01-01'))->count(),
            'list'=>Quality::where('dealer_id', $dealer_id)->orderBy('id', 'desc')->get()
        ]);



    }
}
