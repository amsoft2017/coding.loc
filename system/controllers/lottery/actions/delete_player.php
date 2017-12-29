<?php

class actionLotteryDeletePlayer extends cmsAction
{

    public function run($id = false)
    {

        if (!$id) {
            cmsCore::error404();
        }

        $player = $this->model->getPlayer($id);

        if (!$player) {
            cmsCore::error404();
        }

        $user = cmsUser::getInstance();

        $is_can_delete = true;

        if (!$is_can_delete) {
            cmsCore::error404();
        }

        $this->model->deletePlayer($id);

        $this->redirectToAction('players');

    }

}