<?php 
namespace app\index\model;
use think\Model;
use think\Db;

class User extends Model{

	public function model1(){
		$a = Db::table('patient')->where('id','>','100');
// echo $user->name;

		// $User = new patient;

		// $a = M('patient')->find();
	 	return $a;
	}

}


 ?>