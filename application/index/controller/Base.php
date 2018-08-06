<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
class Base extends Controller
{
	public function _initialize()
    {
    	//检查是否已经登陆
        if (Session::get('app_userMsg')) {
            $this->assign('app_userMsg','1');
        }else{
            //todo暂时不判断,上线后判断
            $this->assign('app_userMsg','1');
        }
    } 
}