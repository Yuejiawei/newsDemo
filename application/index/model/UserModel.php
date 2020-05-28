<?php
/**
 * Created by PhpStorm.
 * User: Jiawei
 * Date: 2020/5/24
 * Time: 11:13
 */

namespace app\index\model;
use think\Model;

class UserModel extends Model{
	protected $table = 'user';

	public function getUserEmail($email){
		if(!$email){
			return false;
		}
		$res = UserModel::where('email',$email)->find();
		# if $res 为 null表示email没用过 返回true
		return $res ? false : true;
	}
	public function getUserPhone($phone){
		if(!$phone){
			return false;
		}
		$res = UserModel::where('phone_number',$phone)->find();
		# if $res 为 null表示phone没用过 返回true
		return $res ? false : true;
	}
	public function getUserMsgNO($email){
		if(!$email){
			return 0;
		}
		$res = UserModel::where('email',$email)->value('email_code');
		return $res;
	}

	/*
	 public function getInfo($name){
		if(!$name){
			return '';
		}
		$res = UserModel::where('username',$name)->find();
		return $res;
	}
	 */

	# 根据某一字段取值
	public function getOneColumnData($column,$columnData,$getColumn){
		$res = UserModel::where($column,$columnData)->value($getColumn);
		return $res ? $res : false;
	}
}