<?php

class formLotteryAddPlayers extends cmsForm
{

    public function init()
    {

        $lottos = cmsCore::getModel('lottery')->filter('i.start_date >= NOW()')->filterEqual('winner', null)->getLottos();

        return array(

            array(
                'type' => 'fieldset',
                'childs' => array(

                    new fieldList('id_lotto', array(
                        'title' => LANG_LOTTERY_ADD_PLAYERS_LOTTOS,
                        'hint' => LANG_LOTTERY_ADD_PLAYERS_LOTTOS_HINT,
                        'generator' => function () use ($lottos) {
                            return array_collection_to_list($lottos, 'id', 'name', true);
                        }
                    )),

                    new fieldListgroups('id_groups', array(
                        'title' => LANG_LOTTERY_ADD_PLAYERS_GROUP,
                        'hint' => LANG_LOTTERY_ADD_PLAYERS_GROUP_HINT,
                        'rules' => array(
                            array('required'),
                        ),
                    )),


                )
            )

        );

    }

}