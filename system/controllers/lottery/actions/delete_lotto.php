<?php

class actionLotteryDeleteLotto extends cmsAction
{

    public function run($id = false)
    {

        if (!$id) {
            cmsCore::error404();
        }

        $lotto = $this->model->getLotto($id);

        if (!$lotto) {
            cmsCore::error404();
        }

        $user = cmsUser::getInstance();

        if (!$user->is_admin) {
            cmsCore::error404();
        }

        $is_can_delete = true;

        if (!$is_can_delete) {
            cmsCore::error404();
        }

        $this->model->deleteLotto($id);

        $this->redirectToAction('lottos');

    }

}