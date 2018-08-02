<?php
namespace app\index\controller;
use think\View;
use think\Controller;
use think\Session;
class Index extends Controller
{
	public function _initialize()
    {
    }
    
    public function index()
    {

        //检查是否已经登陆
        if (Session::get('app_userMsg')) {
            $this->assign('app_userMsg','1');
        }else{
            $this->assign('app_userMsg','0');
        }
        return $this->fetch();die;
    }

    public function user(){
        return $this->fetch();die;
    }
    public function json()
    {
    	$data = $_GET['a'];
        return json_encode($data);
    }
    
    public function read()
    {
        return view();
    }

    //登陆
    public function login(){
        return $this->fetch();
    }

    //我的数据
    public function user_data(){
        return $this->fetch();
    }

}
