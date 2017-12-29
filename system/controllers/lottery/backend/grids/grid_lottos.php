<?php

function grid_lottos($controller)
{

    $options = array(
        'is_sortable' => false,
        'is_filter' => false,
        'is_pagination' => true,
        'is_draggable' => true,
        'order_by' => 'id',
        'order_to' => 'asc',
        'show_id' => true
    );

    $columns = array(
        'id' => array(
            'title' => 'id',
            'width' => 30,
        ),
        'name' => array(
            'title' => LANG_LOTTERY_LOTTO_NAME,
            'href' => href_to($controller->root_url, 'edit_lotto', array('{id}')),
        ),
        'start_date' => array(
            'title' => LANG_LOTTERY_MY_START_DATE,
        ),

        'status' => array(
            'title' => LANG_LOTTERY_LOTTO_STATUS,
        ),

        'count_player' => array(
            'title' => LANG_LOTTERY_LOTTO_COUNT,
        ),

    );

    $actions = array(
        array(
            'title' => LANG_EDIT,
            'class' => 'edit',
            'href' => href_to($controller->root_url, 'edit_lotto', array('{id}')),
        ),
        array(
            'title' => LANG_DELETE,
            'class' => 'delete',
            'href' => href_to($controller->root_url, 'delete_lotto', array('{id}')),
            'confirm' => LANG_LOTTERY_DELETE_LOTTO_CONFIRM,
        )
    );

    return array(
        'options' => $options,
        'columns' => $columns,
        'actions' => $actions
    );

}

