<?php
/**
 * Created by PhpStorm.
 * User: Jiawei
 * Date: 2020/5/26
 * Time: 15:05
 */

namespace app\index\model;
use think\Model;

class ArticleModel extends Model {
	protected $table = 'article';

	public function getContent($id){
		$res = ArticleModel::where('id',$id)->select();
		return $res;
	}
	# 获取详情页广告位数据
	public function getADData(){
		$res = ArticleModel::where('is_set_rotation',2)->order('time','desc')->limit(1)->find();
		return $res;
	}
	# 详情页广告位热门排行
	public function getViewCountRank(){
		$res = ArticleModel::order('view_times','desc')->limit(10)->order('time','desc')->field('id,title')->select();
		return $res;
	}
	# 获取详情页推荐位数据
	public function getCategoryViewCountRank(){
		$res = ArticleModel::where('is_recommend',1)->order('time','desc')->limit(5)->field('id,title,category')->select();
		return $res;
	}
	# 按照分类、时间顺序、限定条数取出各类别数据
	public function getSomeData($type=0,$count){
		$res = ArticleModel::where('category','=',$type)->limit($count)->order('time','desc')->field('id,title,label,time')->select();
		return $res;
	}
	# 按照分类、时间顺序取出某一个分类下的所有数据
	public function getOneCategoryData($cid){
		$res = ArticleModel::where('category',$cid)->order('time','desc')->field('id,title,label')->select();
		return $res;
	}
	# 根据分类、时间顺序和label标签获取分类下数据
	public function getOneCategoryAndLabelData($cid,$label){
		$res = ArticleModel::where('category',$cid)->where('label',$label)->order('time','desc')->field('id,title,label')->select();
		return $res;
	}
	public function getTopRotation(){
		$res = ArticleModel::where('is_set_rotation','=',1)->limit(5)->order('time','desc')->field('id,title,head_image')->select();
		return $res;
	}

	# 根据某一字段取值id
	public function getIdColumnData($column,$columnData){
		$id = ArticleModel::where($column,$columnData)->order('view_times','desc')->order('time','desc')->limit(1)->value('id');
		return $id;
	}
	# 根据某一字段取值title
	public function getTitleColumnData($column,$columnData){
		$title = ArticleModel::where($column,$columnData)->order('view_times','desc')->order('time','desc')->limit(1)->value('title');
		return $title;
	}
}