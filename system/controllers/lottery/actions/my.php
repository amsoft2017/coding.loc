<?php

class actionLotteryMy extends cmsAction
{

    public function run()
    {
        $user = cmsUser::getInstance();

        if (!$user->is_admin) {
            cmsCore::error404();
        }
        $reg = '/{(.*?)}/m';
        $str = '{ctypes=posts}{id=12}';
        preg_match_all($reg, $str, $mat);
        dump($mat);
        $matches = preg_split($reg, $str, -1, PREG_SPLIT_DELIM_CAPTURE);
        dump($matches);
//        $template = cmsTemplate::getInstance();
//
//        $lottos = $this->model->getParticipants(13);
//
//
//        return $template->render('test', array(
//            'lottos' => $lottos,
//
//
//        ));

    }

}
