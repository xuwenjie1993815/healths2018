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
            //登陆
            ini_set("error_reporting","E_ALL & ~E_NOTICE");
            // $obj->userName = input('post.name');
            // $obj->passWord = input('post.password');
            // $data =  json_encode($obj);
            $data['userName']=input('post.name');
            $data['passWord']=input('post.password');
            $url = config('path')."/login";
            $res = http_request($url, $data);
            $res = json_decode($res,true);
            if ($res['token']) {
                //判断是否删除
                if ($res['userMapper']['isDeleted'] == '2') {
                    return array('code' => '2','msg' => '该用户已删除');die;
                }
                Session::set('userMsg.id',$res['userMapper']['id']);
                Session::set('userMsg.type',$res['userMapper']['type']);
                Session::set('userMsg.info',$res['userMapper']);
                return array('code' => '1');die;
            }else{
                return array('code' => '2','msg' => $res['message']?:'服务器暂无响应..请稍候');die;
            }
        }
        
        if (!Session::get('userMsg')) {
            return $this->fetch('login');die;
        }else{
            $userMsg = Session::get('userMsg');
            $this->assign('userMsg',$userMsg);
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
        $userMsg = Session::get('userMsg');
        $url = config('path')."/logout/id=".$userMsg['id'];
        $res = http_request($url, $data);
        $res = json_decode($res,true);
        if ($res === true) {
            Session::delete('userMsg');
            return array('code' => 1,'msg' => '退出成功');
        }else{
            return array('code' => 2,'msg' => $res['message']?:'服务器暂无响应..请稍候');
        }
    }

    //查找机构
    public function select_admin(){
        $select_c = input('get.content');
        //根据机构名称/id查询机构详情
        $data = "<tr onClick='admin_jg(1)' style='cursor:Pointer;' id='jg1'><th>1</th><td>健康源</td></tr>";
        return array('code' => 1,'msg' => $data);
    }

    //查找医生
    public function select_doctor(){
        $ys_id = input('get.content');
        //根据机构id查询机构医生
        $data = "<tr onClick='admin_ys(124)' style='cursor:Pointer;' id='ys124'><td>朱玉婷</td></tr><tr onClick='admin_ys(123)' style='cursor:Pointer;' id='ys123'><td>徐医生</td></tr>";
        return array('code' => 1,'msg' => $data);
    }

    //查找医助
    public function select_yz(){
        $yz_id = input('get.content');
        //根据机构id查询机构医生
        $data = "<tr onClick='admin_yz(243)' style='cursor:Pointer;' id='yz243'><td>肖莉</td></tr><tr onClick='admin_yz(245)' style='cursor:Pointer;' id='yz245'><td>杨涛</td></tr>";
        return array('code' => 1,'msg' => $data);
    }
}
