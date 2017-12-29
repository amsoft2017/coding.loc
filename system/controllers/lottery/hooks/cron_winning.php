<?php

class onLotteryCronWinning extends cmsAction
{

    public function run()
    {
        Lottery::runWinner();
    }

}
