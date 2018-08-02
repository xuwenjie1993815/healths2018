<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
class Base extends Controller
{
	//检查是否登录/接口状态
	public function _initialize(){
		//登陆
        if (!Session::get('userMsg')) {
			// echo "<script language=JavaScript> location.replace(location.href);</script>";
            return $this->fetch('Index/login');die;
        }else{
        	//接口
	        $userMsg = session::get('userMsg');
			$url = config('path')."/goods/oder/groupOders?groupId=".$userMsg['id'];
			$res = http_request($url);
			if ($res == false) {
           		Session::delete('userMsg');

				// echo "<script language=JavaScript> location.replace(location.href);</script>";

            	// return $this->fetch('/Index/login');die;
			}
        }
    }  
}