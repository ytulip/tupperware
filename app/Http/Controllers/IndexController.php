<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Model\Record;
use App\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class IndexController extends Controller
{
    public function getIndex()
    {
        if(!Auth::check()){
            return Redirect::to('index/login');
        }
        //获取list列表
        $list = Record::where('user_id',Auth::id())->where('is_delete',0)->orderBy('id','desc')->get();
        $recordArr = [];
        foreach ( $list as $key=>$record)
        {
            $urls = [];
            if($record->img1) $urls[] = $record->img1;
            if($record->img2) $urls[] = $record->img2;
            $recordArr[] = ["urls"=>$urls,"attach_msg"=>[
                'id'=>$record->id,
                'day'=>date('d',strtotime($record->created_at)),
                'm'=>date('m',strtotime($record->created_at)),
                'his'=>date('h:i:s',strtotime($record->created_at))
            ]];
        }
        return view('index')->with('recordArr',$recordArr);
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
        if( !Auth::check() )
        {
            return $this->jsonReturn(0,'用户信息丢失');
        }


        $files = \Illuminate\Support\Facades\Request::file('images');
        $count = count($files);
        if (!in_array($count,[1,2])) {
            return json_encode(["status" => 0, "desc" => "文件个数异常"], JSON_UNESCAPED_UNICODE);
        }

        $imagesInfo = [];
        foreach ($files as $key => $file) {
            $imageExtension = $file->getClientOriginalExtension(); //上传文件的后缀
            if (!in_array($imageExtension, ['jpg', 'png', 'gif', 'jpeg'])) {
                return json_encode(['status' => 0, 'desc' => '文件格式异常'], JSON_UNESCAPED_UNICODE);
            }
            $imagesInfo[] = $imageSaveName = bin2hex(base64_encode(time() . $key)) . '.' . $imageExtension; //文件保存的名字
        }

        $res = [];
        $result = false;
        foreach ($files as $key => $file) {
            //$iamgeTempPath = $file->getRealPath(); //临时文件的绝对路径


            //这里应该要去翻转图片吧
            $exif = exif_read_data($file->getPathName());
            Log::info($exif);

            if ($file->move('imgsys/' . $this->getCurrentDayTime() . '/', $imagesInfo[$key])) {
                $result = true;
                $res[] = '/imgsys/' . $this->getCurrentDayTime() . '/' . $imagesInfo[$key];
            } else {
                $result = false;
                break;
            }

//            $imageFileContent = file_get_contents($iamgeTempPath);

            //上传OSS
//            $oss = \App\Util\OSS\OssCommon::getInstance();
//            $upRes = $oss->uploadFileByContent($imageFileContent,['folder' => '/',
//                'fileName' => $imagesInfo[$key]]);
//
//            if(\App\Util\Kits::checkSuccessTrue($upRes)){
//                $result = true;
//                $res[] = $imagesInfo[$key];
//            }
//            else{
//                $result = false;
//                break;
//            }
        }
        if ($result) {

            $record = new Record();
            $record->user_id = Auth::id();
            foreach ($res as $key=>$item)
            {
                $key = 'img' . ($key + 1);
                $record->$key= $item;
            }
            $record->save();

            //保存数据
            return json_encode(['status' => 1, 'data' => $res, 'attach_msg'=>['id'=>$record->id,'day'=>date('d',strtotime($record->created_at)),'m'=>date('m',strtotime($record->created_at)),'his'=>date('h:i:s',strtotime($record->created_at))]]);
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
}