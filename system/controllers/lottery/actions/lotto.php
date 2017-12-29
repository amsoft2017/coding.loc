<?php

class actionLotteryLotto extends cmsAction
{
    public function run($id = false)
    {
        if (!$id) {
            cmsCore::error404();
        }

        $players = $this->model->getParticipants($id);

        $lotto = $this->model->getLotto($id);

        if (!$lotto) {
            cmsCore::error404();
        }

        $template = cmsTemplate::getInstance();

        return $template->render('lotto_bootstrap', array(
            'lotto' => $lotto,
            'players' => $players,
        ));

    }

}