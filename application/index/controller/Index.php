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
        $data['patientId'] = 125;
        $url = config('path')."/app/patientPage";
        $res = http_request($url,$data);
        $res = json_decode($res,true);
        if ($res AND !$res['error']) {
            $this->assign('user_info',$res);
        }
        return $this->fetch();die;
    }

    public function user(){
        $data['patientId'] = 125;
        $url = config('path')."/app/patientPage";
        $res = http_request($url,$data);
        $res = json_decode($res,true);
        if ($res AND !$res['error']) {
            $this->assign('user_info',$res);
        }
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
        //通过登陆患者的patientId获取数据
        $data['patientId'] = 125;
        $url = config('path')."/app/bloodPage";
        $res = http_request($url,$data);
        $res = json_decode($res,true);
        if ($res AND !$res['error']) {
            foreach ($res['avgBloodMappers'] as $key => $value) {
                if ($key == 0) {
                    $info['uploadTime'] = $value['uploadTime'];
                    $info['avgSystolic'] = $value['avgSystolic'];
                    $info['avgDiastolic'] = $value['avgDiastolic'];
                    $info['avgHeart'] = $value['avgHeart'];
                }else{
                    $info['uploadTime'] .= ','.$value['uploadTime'];
                    $info['avgSystolic'] .= ','.$value['avgSystolic'];
                    $info['avgDiastolic'] .= ','.$value['avgDiastolic'];
                    $info['avgHeart'] .= ','.$value['avgHeart'];
                }
            }

            $this->assign('bloodData',$info);
            $this->assign('user_info',$res);
        }
        return $this->fetch();
    }

    //手册
    public function manual(){
        return $this->fetch();
    }

}
