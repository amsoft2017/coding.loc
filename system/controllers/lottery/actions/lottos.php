<?php

class actionLotteryLottos extends cmsAction
{

    public function run()
    {

        $seo = [
            'seo_title' => $this->options['seo_title'],
            'seo_keys' => $this->options['seo_keys'],
            'seo_desc' => $this->options['seo_desc'],
        ];

        $perpage = isset($this->options['lottos_perpage'])? $this->options['lottos_perpage'] : 3;


        $page = $this->request->get('page', 1);


        $template = cmsTemplate::getInstance();

        $total = $this->model->getLottosCount();


        $this->model->limitPage($page, $perpage);

        $this->model->orderBy('id', 'desc');

        $lottos = $this->model->getLottos();

        return $template->render('lottos', array(
            'lottos' => $lottos,
            'total' => $total,
            'page' => $page,
            'perpage' => $perpage,
            'seo' => $seo,

        ));

    }

}