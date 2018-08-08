<?php
namespace app\index\controller;
use think\View;
use think\Controller;
use think\Session;
use app\index\controller\Base;
use Weixin\Weixin;

class Index extends Base
{
    
    public function index()
    {
        $data['patientId'] = 192;
        $url = config('path')."/app/patientPage";
        $res = http_request($url,$data);
        $res = json_decode($res,true);
        if ($res AND !$res['error']) {
            $this->assign('user_info',$res);
        }
        return $this->fetch();die;
    }

    public function user(){
        $data['patientId'] = 192;
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
        if ($_POST) {
            $data['userName'] = $_POST['userName'];
            $data['password'] = $_POST['password'];
            $url = config('path')."/app/loginByUser";
            $res = http_request($url,$data);
            $res = json_decode($res,true);
            if ($res AND !$res['error']) {
                return array('code' => 1);
                //保存客户端session
            }else{
                return array('code' => 2,'msg' => $res['message']);
            }
        }
        return $this->fetch();
    }
    
    //注册
    public function register(){
        if ($_POST) {
            //根据名字查机构信息
            if ($_POST['group_name']) {
                $url = config('path')."/classification/group/getByName?name=".urlencode($_POST['group_name']);
                $res = http_request($url);
                $res = json_decode($res,true);
                if ($res AND !$res['error']) {
                    return array('code' => 1,'data' => $res[0]);
                }else{
                    return array('code' => 2,'msg' => $res['message']);
                }
                die;
            }
            //头像
            
            if ($_POST['form_data']['imgkey']) {
                $_POST['form_data']['imgUrl'] = "http://pbngsysl7.bkt.clouddn.com/".$_POST['form_data']['imgkey'];
            }
            unset($_POST['form_data']['imgkey']);

            //获取微信用户openid
            $weixin = new Weixin();
            $res = $weixin->authorization();
            if ($res['code'] != '1') {
                return $res;die;
            }
            $openid=$res["openid"];
            $headurl=$res["headimgurl"];
            $nickname=$res["nickname"];
            $_POST['openid'] = $openid;
            $_POST = json_encode($_POST);
            $url = config('path')."/app/register";
            $res = http_request($url,$_POST,1);
            $res = json_decode($res,true);
            if ($res AND !$res['error']) {
                return array('code' => 1);
            }else{
                return array('code' => 2,'msg' => $red['message']);
            }
        }

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

    //我的数据->表单数据
    public function get_table_data(){
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
            echo $info;
            var_dump($info);die;
            // $this->assign('bloodData',$info);
            // $this->assign('user_info',$res);
        }
    }

    //手册
    public function manual(){
        return $this->fetch();
    }

    //上传图片获取token
    public function uploadToken(){
        $url = config('path')."/patient/image/uploadToken";
        $res = http_request($url);
        return $res;
    }

}
