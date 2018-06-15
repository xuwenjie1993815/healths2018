<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use think\Session;
use app\admin\model\User;
class Index extends Controller
{
	public function _initialize()
    {
        // $User = new User;
        // var_dump($User->model1());
        // echo 'init<br/>';
    }
    
    public function index()
    {
        if (input('post.password') and input('post.name')) {
            Session::set('userMsg','thinkphp');
            return array('code' => '1');die;
        }
        
        if (!Session::get('userMsg')) {
            return $this->fetch('login');die;
        }else{
            return $this->fetch('index');die;
        }

            
        
    	// var_dump('1',$_GET['a']);die;
    	//
    	// $view = new view();
    	
    	return view('Index/index');
    	die;
    }

    public function login()
    {
        return $this->fetch('login');die;
        // var_dump('1',$_GET['a']);die;
        //
        // $view = new view();
        return $this->fetch('index');die;
        return view('Index/index');
        die;
    }

    public function welcome()
    {
        //今日测量数据
        
        //今日预警
        
        //昨日新增患者
        
        //昨日订单
        
        return $this->fetch();
    }

    public function hello(){
    	$a = ['name'=>'thinkphp','status'=>1];
    	// var_dump($a);die;
    	return $a;
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

    //退出登录
    public function sign_out(){
        Session::delete('userMsg');
        return array('code' => 1,'msg' => '退出成功');
    }
}
