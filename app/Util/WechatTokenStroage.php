<?php

namespace App\Util;

use App\Model\CodeLibrary;

class WechatTokenStroage
{
    // 静态成员变量，用来保存类的唯一实例
    private static $_instance;
    public $_client;

    // 用private修饰构造函数，防止外部程序来使用new关键字实例化这个类
    private function __construct()
    {
    }

    /**
     * 初始化
     */
    private function init(){
//        $server = array(
//            'scheme'=>'tcp',
//            'host' => '120.77.33.223',
//            'port' => 6379,
//            'password' => '0dd0010c',
//            'database' => 1
//        );
//        $this->_client = new Client($server);
    }

// 覆盖php魔术方法__clone()，防止克隆
    private function __clone()
    {
        trigger_error('Clone is not allow', E_USER_ERROR);
    }

    // 单例方法，返回类唯一实例的一个引用
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
            self::$_instance->init();
        }
        return self::$_instance;
    }

    public function get($key)
    {
        $record = CodeLibrary::where('type', 'wechat')->where('item_name', $key)->first();
        if( !($record instanceof  CodeLibrary) )
        {
            return '';
        }
        return $record->item_value;
    }

    public function set($key, $val)
    {
        $record = CodeLibrary::where('type', 'wechat')->where('item_name', $key)->first();
        if( !($record instanceof  CodeLibrary) )
        {
            $record = new CodeLibrary();
            $record->type = 'wechat';
            $record->item_name = $key;
        }
        $record->item_value = $val;
        $record->save();
    }
}
