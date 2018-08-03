<?php
namespace app\index\controller;
use think\View;
use think\Controller;
use think\Session;
use app\index\controller\Base;
class Index extends Base
{
    
    public function index()
    {
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
    
    //登陆
    public function register(){
        return $this->fetch();
    }

    //我的数据
    public function user_data(){
        return $this->fetch();
    }

    //手册
    public function manual(){
        return $this->fetch();
    }

}
