<?php

class formLotteryMy extends cmsForm
{

    public function init()
    {

        return array(

            array(
                'type' => 'fieldset',
                'childs' => array(

                    new fieldDate('start_date', array(
                        'title' => LANG_LOTTERY_MY_START_DATE,
                        'rules' => array(
                            array('required'),
                        ),
                    )),

                )
            )

        );

    }

}