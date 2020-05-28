<?php
/**
 * Created by PhpStorm.
 * User: Jiawei
 * Date: 2020/5/26
 * Time: 9:38
 */

namespace app\index\controller;

use app\index\model\ArticleModel;
use think\App;
use think\Controller;

class Article extends Controller {
	public function __construct(App $app = null) {
		parent::__construct($app);
		$this->model = new ArticleModel;
	}

	public function detail($id){
		$info = $this->model->getContent($id);
		$ADModule = $this->model->getADData();
		$viewCountRank = $this->model->getViewCountRank();
		$categoryViewCountRank = $this->model->getCategoryViewCountRank();
//		print_r($ADModule);exit(0);
		foreach($info as $key=>$user){
			$title = $user->title;
			$source = $user->source;
			$time = $user->time;
			$view_count = $user->view_times;
			$content = $user->content;
			$publisher = $user->publisher;
		}
		$content = explode('%\n%',$content);
		return $this->fetch('',[
			'title'=>$title,
			'source'=>$source,
			'time'=>$time,
			'view_count'=>$view_count,
			'content'=>$content,
			'publisher'=>$publisher,
			'ad_module' => $ADModule,
			'viewCountRank'=>$viewCountRank,
			'categoryViewCountRank'=>$categoryViewCountRank
		]);
	}
	public function more_article(){
		$cid = input('get.cid');
		$label = input('get.label');
		if(strlen($label) != 0){
			if(is_null($cid)){
				return redirect('index/news');
			}
			$data = $this->model->getOneCategoryAndLabelData($cid,$label);
			return $this->fetch('',[
				'cid'=>$cid,
				'categoryData'=>$data,
				'label'=>$label
			]);
		}else{
//			$label = input('get.label') ? input('get.label') : '';
			# $cid = 0时比较尴尬
			if(is_null($cid)){
				return redirect('index/news');
			}
			$data = $this->model->getOneCategoryData($cid);
			return $this->fetch('',[
				'cid'=>$cid,
				'categoryData'=>$data
			]);
		}

//		if($label !='' && !is_null($cid)){
//			$data = $this->model->getOneCategoryAndLabelData($cid,$label);
//			return $this->fetch('',[
//				'cid'=>$cid,
//				'categoryData'=>$data,
//				'label'=>$label
//			]);
//		}

	}
}