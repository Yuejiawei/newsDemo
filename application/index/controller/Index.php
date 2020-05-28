<?php
namespace app\index\controller;

use think\App;
use think\Controller;
use app\index\model\ArticleModel;
class Index extends Controller
{
	public function __construct(App $app = null) {
		parent::__construct($app);
		$this->model = new ArticleModel;
	}

	public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
    public function news(){
    	$exposeData = $this->model->getSomeData(0,10);
    	$publishFile= $this->model->getSomeData(1,3);
		$PublishViewCountMaxId = $this->model->getIdColumnData('category','1');
		$PublishViewCountMaxTitle = $this->model->getTitleColumnData('category','1');
    	$enterpriseAnnounce = $this->model->getSomeData(2,3);
    	$AnnounceViewCountMaxId = $this->model->getIdColumnData('category','2');
    	$AnnounceViewCountMaxTitle = $this->model->getTitleColumnData('category','2');
    	$industrInformation = $this->model->getSomeData(3,12);
    	$conferenceAndExhibition = $this->model->getSomeData(4,7);
    	$topRotation = $this->model->getTopRotation();
//    	print_r($topRotation);exit(0);
    	return $this->fetch('',[
    		'exposeData'=>$exposeData,
			'publisherFile' => $publishFile,
			'enterpriseAnnounce' => $enterpriseAnnounce,
			'industrInformation'=>$industrInformation,
			'conferenceAndExhibition'=>$conferenceAndExhibition,
			'topRotation'=> $topRotation,
			'PublishViewCountMaxId'=>$PublishViewCountMaxId,
			'PublishViewCountMaxTitle'=> $PublishViewCountMaxTitle,
			'AnnounceViewCountMaxId'=>$AnnounceViewCountMaxId,
			'AnnounceViewCountMaxTitle'=>$AnnounceViewCountMaxTitle
		]);
	}
}
