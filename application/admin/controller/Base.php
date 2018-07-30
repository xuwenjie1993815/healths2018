<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
class Base extends Controller
{
	//检查是否登录
	public function _initialize(){  
        if (!Session::get('userMsg')) {
            return $this->fetch('Index/login');die;
        }
    }  
}