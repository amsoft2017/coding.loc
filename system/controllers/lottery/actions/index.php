<?php

class actionLotteryIndex extends cmsAction
{

    public function run()
    {
        $template = cmsTemplate::getInstance();

//        $this->determineWinner();

//        $lottos = $this->model->getLotto(13);
//        $str = $lottos['distribution'];

        $this->redirectToAction('lottos');


        return $template->render('index', array(
//            'lottos' => $lottos,
//            'str' => $str,



        ));

    }

}
