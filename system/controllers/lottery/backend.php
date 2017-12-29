<?php

class backendLottery extends cmsBackend
{
    protected $useOptions = true;
    public $useDefaultOptionsAction = true;

    public function actionIndex()
    {
        $this->redirectToAction('options');
    }

    public function getBackendMenu()
    {
        return array(

            array(
                'title' => LANG_LOTTERY_BACKEND_TAB_OPTIONS,
                'url' => href_to($this->root_url, 'options')
            ),

            array(
                'title' => LANG_LOTTERY_LOTTOS,
                'url' => href_to($this->root_url, 'lottos')
            ),

            array(
                'title' => LANG_LOTTERY_ADD_PLAYER,
                'url' => href_to($this->root_url, 'add_player')
            ),

            array(
                'title' => LANG_LOTTERY_ADD_PLAYERS,
                'url' => href_to($this->root_url, 'add_players')
            ),
        );
    }

}
