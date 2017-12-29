<?php

    $this->addBreadcrumb(LANG_LOTTERY_PLAYERS, $this->href_to('players'));

    if ($do == 'add') { 
        $page_title = LANG_LOTTERY_ADD_PLAYER; 
    }

    if ($do == 'edit') { 
        $page_title = LANG_LOTTERY_EDIT_PLAYER; 
    }

    $this->setPageTitle($page_title);
    $this->addBreadcrumb($page_title);

    $this->renderForm($form, $player, array(
            'action' => '',
            'method' => 'post',
            'toolbar' => false
    ), $errors);
