<?php

class actionLotteryDeleteLotto extends cmsAction
{

    public function run($id = false)
    {

        if (!$id) {
            cmsCore::error404();
        }

        $model = cmsCore::getModel($this->name);

        $lotto = $model->getLotto($id);

        if (!$lotto) {
            cmsCore::error404();
        }

        $model->deleteLotto($id);

        $this->redirectToAction('lottos');

    }

}