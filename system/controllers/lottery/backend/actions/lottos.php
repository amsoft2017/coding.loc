<?php

class actionLotteryLottos extends cmsAction
{

    public function run()
    {

        $grid = $this->loadDataGrid('lottos');

        return cmsTemplate::getInstance()->render('backend/lottos', array(
            'grid' => $grid
        ));

    }

}
