<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\portal\controller;

use cmf\controller\HomeBaseController;
use app\portal\model\PortalCategoryModel;
use app\portal\service;

class ListController extends HomeBaseController
{
    public function index()
    {
        $id = $this->request->param('id', 0, 'intval');
        $cid = $this->request->param('cid', 0, 'intval');
        $type = $this->request->param('type', 0);

        if (!empty($type)) {
            $service = new service\ApiService();
            $_articles = $service->articles(array(
                'category_ids' => (!empty($cid)) ? $cid : 1,
                'where' => '',
                'limit' => '',
                'order' => '',
                'page' => '',
                'relation' => ''
            ));


            $articles = [];
            foreach ($_articles['articles'] as $article) {
                $_a['id'] = $article['id'];
                $_a['title'] = $article['post_title'];
                $_a['source'] = $article['post_source'];
                $_a['content'] = $article['post_content'];
                $_a['time'] = date("Y-m-d H:i:s", $article['published_time']);
                $_a['photo'] = isset($article['more']['photos'][0]['url']) ? $article['more']['photos'][0]['url'] : '';
                $articles[] = $_a;
            }

            echo json_encode($articles);
            exit;
        }

        $portalCategoryModel = new PortalCategoryModel();

        $category = $portalCategoryModel->where('id', $id)->where('status', 1)->find();

        $this->assign('category', $category);

        $listTpl = empty($category['list_tpl']) ? 'list' : $category['list_tpl'];

        return $this->fetch('/' . $listTpl);

    }
}
