<?php
namespace app\admin\controller;
use think\View;
use think\Controller;
use app\admin\model\User;
class Article extends Controller
{
	public function article_list()
    {
        return $this->fetch('article_list');die;
    	// var_dump('1',$_GET['a']);die;
    	//
    	// $view = new view();
    	return $this->fetch('index');die;
    	return view('Index/index');
    	die;
    }
}