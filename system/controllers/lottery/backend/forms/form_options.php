<?php

class formLotteryOptions extends cmsForm
{

    public function init()
    {

        return array(

            array(
                'type' => 'fieldset',
                'childs' => array(

                    new fieldNumber('lottos_perpage', array(
                        'title' => 'Количество записей на одной странице',
                        'hint' => 'Укажите количество записей, которое будет показываться на одной странице в списке лотерей',
                        'default' => 3,
                    )),
                )
            ),

            array(
                'type' => 'fieldset',
                'is_collapsed' => false,
                'title' => LANG_LOTTERY_LOTTOS_SEO,
                'childs' => array(
                    new fieldString('seo_title', array(
                        'title' => LANG_LOTTERY_LOTTO_SEO_TITLE,
                        'options'=>array(
                            'max_length'=> 256,
                            'show_symbol_count'=>true
                        )
                    )),
                    new fieldString('seo_keys', array(
                        'title' => LANG_LOTTERY_LOTTO_SEO_KEYS,
                        'hint' => LANG_LOTTERY_LOTTO_SEO_KEYS_HINT,
                        'options'=>array(
                            'max_length'=> 256,
                            'show_symbol_count'=>true
                        )
                    )),
                    new fieldText('seo_desc', array(
                        'title' => LANG_LOTTERY_LOTTO_SEO_DESC,
                        'hint' => LANG_LOTTERY_LOTTO_SEO_DESC_HINT,
                        'options'=>array(
                            'max_length'=> 256,
                            'show_symbol_count'=>true
                        )
                    ))
                )
            ),

        );

    }

}
