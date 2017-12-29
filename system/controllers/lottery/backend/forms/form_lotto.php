<?php

    class formLotteryLotto extends cmsForm
    {

        public function init()
        {

            return array(

                array(
                    'type' => 'fieldset',
                    'childs' => array(

                        new fieldString('name', array(
                            'title' => LANG_LOTTERY_LOTTO_NAME,
                            'options'=>array(
                                'max_length'=> 50,
                                'show_symbol_count'=>true
                            ),
                            'rules' => array(
                                array('required'),
                            ),
                        )),

                        new fieldHtml('description', array(
                            'title' => LANG_LOTTERY_LOTTO_DESCRIPTION,
                            'rules' => array(
                                array('required'),
                            ),
                        )),

                        new fieldHtml('preview', array(
                            'title' => LANG_LOTTERY_LOTTO_PREVIEW,
                            'hint' => LANG_LOTTERY_LOTTO_PREVIEW_HINT,
                            'options'=>array(
                                'teaser_len'=> 256,
                                'show_symbol_count'=>true
                            ),
                            'rules' => array(
                                array('required'),
                            ),
                        )),


                        new fieldImage('poster', array(
                            'title' => LANG_LOTTERY_LOTTO_ADD_POSTER,
                            'hint' => LANG_LOTTERY_LOTTO_ADD_POSTER_HINT,
                            'rules' => array(
                                array('required'),
                            ),
                        )),

                        new fieldDate('start_date', array(
                            'title' => LANG_LOTTERY_MY_START_DATE,
                            'options' => array(
                                'show_time' => true
                            ),
                            'rules' => array(
                                array('required'),
                            ),
                        )),

                    )
                ),

                array(
                    'type' => 'fieldset',
                    'title' => LANG_LOTTERY_LOTTO_HOW_TO_RECRUIT_PARTICIPANTS,
                    'childs' => array(

                        new fieldCheckbox('is_comments', array(
                            'title' => LANG_LOTTERY_LOTTO_IS_COMMENTS,
                        )),

                        new fieldNumber('comments', array(
                            'hint' => LANG_LOTTERY_LOTTO_COMMENT_HINT,
                            'units' => 'шт',
                        )),

                        new fieldCheckbox('is_content', array(
                            'title' => LANG_LOTTERY_LOTTO_IS_CONTENT,
                        )),

                        new fieldNumber('count_content', array(
                            'units' => 'шт',
                            'hint' => LANG_LOTTERY_LOTTO_COUNT_CONTENT_HINT,
                        )),

                        new fieldCheckbox('buy_ticket', array(
                            'title' => LANG_LOTTERY_LOTTO_BUY_TICKET,
                        )),

                        new fieldNumber('price', array(
                            'hint' => LANG_LOTTERY_LOTTO_PRICE_HINT,
                            'units' => 'очков',
                        )),


                    )
                ),

                array(
                    'type' => 'fieldset',
                    'title' => LANG_LOTTERY_LOTTO_WINNER_METHOD,

                    'childs' => array(


                        new fieldNumber('min_count_tickets', array(
                            'title' => "Минимальное количество билетов",
                            'hint' => "Укажите минимально необходимое количество билетов для проведения розыгрыша</br>Если билетов будет меньше, Лотерее будет присвоен статус 'Розыгрыш не состоялся' и билеты анулируются",
                            'default' => 10,
                            'units' => 'шт',
                        )),

                        new fieldNumber('max_tickets_user', array(
                            'title' => "Максимальное количество билетов на одного пользователя",
                            'hint' => "Укажите максимальное количество билетов, которое может получить пользователь в этой лотерее",
                            'default' => 10,
                            'units' => 'шт',
                        )),


                    )
                ),

                array(
                    'type' => 'fieldset',
                    'title' => LANG_LOTTERY_LOTTO_PRIZE_DISTRIBUTION,

                    'childs' => array(


                        new fieldText('distribution', array(
                            'title' => LANG_LOTTERY_LOTTO_PRIZE_DISTRIBUTION_LIST,
                            'hint' => LANG_LOTTERY_LOTTO_PRIZE_DISTRIBUTION_LIST_HINT,
                            'rules' => array(
                                array('required'),
                            ),
                        )),

                        new fieldCheckbox('is_consolation_prize', array(
                            'title' => LANG_LOTTERY_LOTTO_PRIZE_IS_CONSOLATION,
                            'default' => false,
                        )),

                        new fieldNumber('number_tickets', array(
                            'title' => "Количество билетов",
                            'hint' => "Укажите количество билетов, которые получат утешительные призы.</br> 0 - Все билеты не занявшие призовых мест",
                            'default' => 0,
                            'units' => 'шт',
                        )),

                        new fieldNumber('rating', array(
                            'title' => LANG_LOTTERY_LOTTO_PRIZE_RATING,
                            'hint' => LANG_LOTTERY_LOTTO_PRIZE_RATING_HINT,
                            'default' => 10,
                            'units' => 'очков',
                        )),

                    )
                ),

                array(
                    'type' => 'fieldset',
                    'title' => LANG_LOTTERY_LOTTO_TEMPLATE_SETTINGS_OUTPUT,

                    'childs' => array(

                        new fieldImage('bg_lott', array(
                            'title' => LANG_LOTTERY_LOTTO_TEMPLATE_BACKGROUND_IMAGE_TIMER,
                            'hint' => LANG_LOTTERY_LOTTO_TEMPLATE_BACKGROUND_IMAGE_TIMER_HINT,
                            'rules' => array(

                            ),
                        )),

                        new fieldColor('color_name', array(
                            'title' => LANG_LOTTERY_LOTTO_TEMPLATE_THE_TEXT_COLOR_OF_THE_NAME,
                            'default' => '#e81717',
                            'rules' => array(
                                array('required'),
                            ),
                        )),

                        new fieldColor('color_timer', array(
                            'title' => LANG_LOTTERY_LOTTO_TEMPLATE_THE_TIMER_COLOR,
                            'default' => '#e81717',
                            'rules' => array(
                                array('required'),
                            ),
                        )),

                        new fieldColor('color_header', array(
                            'title' => LANG_LOTTERY_LOTTO_TEMPLATE_THE_TEXT_TIMER_COLOR,
                            'default' => '#e81717',
                            'rules' => array(
                                array('required'),
                            ),
                        )),

                        new fieldColor('color_users', array(
                            'title' => LANG_LOTTERY_LOTTO_TEMPLATE_BACKGROUND_COLOR_OF_PLATES,
                            'default' => '#e81717',
                            'rules' => array(
                                array('required'),
                            ),
                        )),

                    )
                ),




            );

        }

    }