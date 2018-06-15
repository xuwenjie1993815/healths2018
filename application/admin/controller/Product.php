<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
class Product extends Controller
{
    public function index(){
        return $this->fetch();
    }

    public function product_add(){
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