<?php
namespace app\index\controller;
use think\View;
use think\Controller;
use think\Session;
use app\index\controller\Base;
class Product extends Base
{
	public function product_list(){
		//获取商品列表
		// type 1:服务包  2:实物
		$url = config('path')."/goods/type/1";
		$res = http_request($url);
		$res = json_decode($res,true);
		if ($res AND !$res['error']) {
			$this->assign('list',$res);
		}
		return $this->fetch();
	}

	//商品详情
	public function product_info(){
		//通过商品id查询商品详情
		$url = config('path')."/goods/id/".input('id');
		$res = http_request($url);
		$res = json_decode($res,true);
		if ($res AND !$res['error']) {
			if ($res['discount'] > 0) {
				$res['discount_price'] = $res['price'] * ($res['discount']/10);
			}else{
				$res['discount_price'] = $res['price'];
			}
			$this->assign('info',$res);
		}
		return $this->fetch();
	}
}