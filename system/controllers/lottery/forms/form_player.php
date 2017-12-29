<?php

class formLotteryPlayer extends cmsForm
{

    public function init()
    {

        return array(

            array(
                'type' => 'fieldset',
                'childs' => array(

                    new fieldNumber('id_users', array(
                        'title' => LANG_LOTTERY_PLAYER_ID_USERS,
                        'hint' => LANG_LOTTERY_PLAYER_ID_USERS_HINT,
                        'rules' => array(
                            array('required'),
                        ),
                    )),

                    new fieldNumber('id_lotto', array(
                        'title' => LANG_LOTTERY_PLAYER_ID_LOTTO,
                        'hint' => LANG_LOTTERY_PLAYER_ID_LOTTO_HINT,
                        'rules' => array(
                            array('required'),
                        ),
                    )),

                )
            )

        );

    }

}