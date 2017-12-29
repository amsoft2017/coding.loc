<?php

class actionLotteryDeletePlayer extends cmsAction
{

    public function run($id = false)
    {

        if (!$id) {
            cmsCore::error404();
        }

        $model = cmsCore::getModel($this->name);

        $player = $model->getPlayer($id);

        if (!$player) {
            cmsCore::error404();
        }

        $model->deletePlayer($id);

        $this->redirectToAction('players');

    }

}