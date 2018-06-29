<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
class Product extends Controller
{
    public function index(){
        //获取商品列表
        $goodList =  array('0' => array('id' => '12','goodName' => '高血压服务包','goodSubName' => '服务包','goodType' => 1,'goodDescription' => '2018年版高血压服务包','status' => '1','goodUrl' => '/ueditor/php/upload/image/20180629/1530251813618774.jpg','price' => 360.00,'discount' => 0,'remark' => '商品备注','isDeleted' => 1,'createTime' => '1530253604','updateTime' => '1530253604'),'1' => array('id' => '13','goodName' => '高血压服务包2','goodSubName' => '服务包2','goodType' => 1,'goodDescription' => '2018年版高血压服务包2','status' => '2','goodUrl' => '/ueditor/php/upload/image/20180629/1530251813333007.jpg','price' => 480.00,'discount' => 9.9,'remark' => '商品备注2','isDeleted' => 1,'createTime' => '1530253604','updateTime' => '1530253604'));
        foreach ($goodList as $key => $value) {
            switch ($value['goodType']) {
                case '1':
                    $goodList[$key]['goodType'] = '服务包';
                    break;
                case '2':
                    $goodList[$key]['goodType'] = '实品';
                    break;
            }
            switch ($value['status']) {
                case '1':
                    $goodList[$key]['status'] = '上架';
                    break;
                case '2':
                    $goodList[$key]['status'] = '下架';
                    break;
            }
            $goodList[$key]['createTime'] = date('y-m-d h:i:s',$value['createTime']);
            $goodList[$key]['updateTime'] = date('y-m-d h:i:s',$value['updateTime']);

        }
        $this->assign('goodList',$goodList);
        return $this->fetch();
    }

    public function product_add(){
        if ($_POST) {
            $time = time();
            $data['isDeleted'] = '1';
            $data['createTime'] = $time;
            $data['updateTime'] = $time;
            return array('code' => 1);
        }
        //编辑
        if (input("?id")) {
            if (!$_POST) {
                //根据商品id查询商品信息
                $goodInfo =  array('id' => '12','goodName' => '高血压服务包','goodSubName' => '服务包','goodType' => 1,'goodDescription' => '2018年版高血压服务包','status' => '1','goodUrl' => '/ueditor/php/upload/image/20180629/1530251813618774.jpg','price' => 360.00,'discount' => 0,'remark' => '商品备注','isDeleted' => 1,'createTime' => '1530253604','updateTime' => '1530253604');
                $this->assign('goodInfo',$goodInfo);
            }
        }
    	return $this->fetch();
    }

    public function product_list(){
    	$id = input('id');
    	$this->assign('id',$id);
    	return $this->fetch();
    }

    //商品结算
    public function checking_out(){
    	$id = $_POST['id'];
    	$number = $_POST['number'];
    	$price = $_POST['price'];
    	$unit = $_POST['unit'];
    	//提交订单(接口)
    	//增加天数(接口)
    	return array('code' => 1);
    }
}