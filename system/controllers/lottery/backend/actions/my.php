<?php

class actionLotteryMy extends cmsAction
{

    public function run()
    {

        $user = cmsUser::getInstance();

        if (!$user->is_admin) {
            cmsCore::error404();
        }

        return;

    }

}
