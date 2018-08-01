<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use think\Session;
use app\admin\controller\Base;
class Product extends Base
{
    public function index(){
        $url = config('path')."/goods/type/1";
        $res = http_request($url);
        $res = json_decode($res,true);
        if ($res AND !$res['error']) {
            $this->assign('goodList',$res);
        }
        return $this->fetch();
        die;
        // //获取商品列表
        // $goodList =  array('0' => array('id' => '12','goodName' => '高血压服务包','goodSubName' => '服务包','goodType' => 1,'goodDescription' => '2018年版高血压服务包','status' => '1','goodUrl' => '/ueditor/php/upload/image/20180629/1530251813618774.jpg','price' => 360.00,'discount' => 0,'remark' => '商品备注','isDeleted' => 1,'createTime' => '1530253604','updateTime' => '1530253604'),'1' => array('id' => '13','goodName' => '高血压服务包2','goodSubName' => '服务包2','goodType' => 1,'goodDescription' => '2018年版高血压服务包2','status' => '2','goodUrl' => '/ueditor/php/upload/image/20180629/1530251813333007.jpg','price' => 480.00,'discount' => 9.9,'remark' => '商品备注2','isDeleted' => 1,'createTime' => '1530253604','updateTime' => '1530253604'));
        // foreach ($goodList as $key => $value) {
        //     switch ($value['goodType']) {
        //         case '1':
        //             $goodList[$key]['goodType'] = '服务包';
        //             break;
        //         case '2':
        //             $goodList[$key]['goodType'] = '实品';
        //             break;
        //     }
        //     switch ($value['status']) {
        //         case '1':
        //             $goodList[$key]['status'] = '已发布';
        //             break;
        //         case '2':
        //             $goodList[$key]['status'] = '已下架';
        //             break;
        //     }
        //     $goodList[$key]['createTime'] = date('y-m-d h:i:s',$value['createTime']);
        //     $goodList[$key]['updateTime'] = date('y-m-d h:i:s',$value['updateTime']);
        // }
        // $this->assign('goodList',$goodList);
        // return $this->fetch();
    }

    public function product_add(){
        if ($_POST) {
            if (input('id')) {
                $data['id'] = input('id');
                $url = config('path')."/goods/updateGood";
            }else{
                $url = config('path')."/goods/insertGood";
            }
            $data['goodName'] = $_POST['goodName'];
            $data['goodSubName'] = $_POST['goodSubName'];
            $data['goodType'] = $_POST['goodType'];
            $data['goodDescription'] = $_POST['goodDescription'];
            $data['status'] = $_POST['status'];
            $data['price'] = $_POST['price'];
            $data['discount'] = $_POST['discount'];
            $data['remark'] = $_POST['remark'];

            // if (!$_POST['imgkey']) {
            // $_POST['imgkey'] = "mainTest";
            // }
            $data['goodUrl'] = "http://pbngsysl7.bkt.clouddn.com/".$_POST['imgkey'];
            foreach ($data as $key => $value) {
                $obj->$key=$value;
            }
            $data = json_encode($obj);
            $res = http_request($url,$data,1);
            $res = json_decode($res,true);
            if ($res AND !$res['error']) {
                return array('code' => 1);
            }else{
                return array('code' => 2,'msg' => $res['message']);
            }
        }
        //编辑
        if (input("?id")) {
            if (!$_POST) {
                //根据商品id查询商品信息
                $url = config('path')."/goods/id/".input('id');
                $res = http_request($url);
                $res = json_decode($res,true);
                if ($res and !$res['error']) {
                    $str = strrev($res['goodUrl']);
                    $imgkey = explode('/',$str)[0];
                    $res['imgkey'] = strrev($imgkey);
                    $this->assign('goodInfo',$res);
                }
            }
        }
    	return $this->fetch();
    }

    public function product_list(){

        $url = config('path')."/goods/type/1";
        $res = http_request($url);
        $res = json_decode($res,true);
        if ($res AND !$res['error']) {
            $this->assign('goodList',$res);
        }

        $id = input('id');
    	$this->assign('id',$id);
    	return $this->fetch();
    }

    //商品结算
    public function checking_out(){
        $data['goodId'] = $_POST['goodId'];
        $data['goodCount'] = $_POST['number'];
        $data['oderNote'] = '';
    	$data['payUserId'] = $_POST['id'];
        $userMsg = Session::get('userMsg');
        $data['getUserId'] = $userMsg['id'];

    	$price = $_POST['price'];

        //订单类型type 3:线下支付订单 4:赠送订单 
        $type = $_POST['type'];
        if ($type == 4) {
            $type = 3;
        }
        $data['payWay'] = $type;
        foreach ($data as $key => $value) {
            $obj->$key=$value;
        }
        $data = json_encode($obj);
        //提交订单(接口)
        $url = config('path')."/goods/insertOder/web";
        $res = http_request($url,$data,1);
        if ($res) {
            $res_j = json_decode($res,true);
            if (!$res_j['error']) {
                return array('code' => 1);
            }else{
                return array('code' => 2,'msg' => $res_j['message']);
            }
        }
    	//增加天数(接口)
    }

    //商品下架
    public function product_stop(){
        $id = $_POST['id'];
        //下架商品
        return array('code' => 1,'msg' => '下架成功');
    }

    //商品上架
    public function product_up(){
        $id = $_POST['id'];
        //上架商品
        return array('code' => 1,'msg' => '上架成功');
    }

    //删除商品
    public function product_del(){
        $id = $_POST['id'];
        //删除商品
        $url = config('path')."/goods/delete/".$id;
        $res = http_request($url);
        $res = json_decode($res,true);
        if ($res and !$res['error']) {
            return array('code' => 1);
        }else{
            return array('code' => 2,'msg' => $res['message']);
        }
    }
}