<?php

    $this->addBreadcrumb(LANG_LOTTERY_LOTTOS, $this->href_to('lottos'));

    if ($do == 'add') { 
        $page_title = LANG_LOTTERY_ADD_LOTTO; 
    }

    if ($do == 'edit') { 
        $page_title = LANG_LOTTERY_EDIT_LOTTO; 
    }

    $this->setPageTitle($page_title);
    $this->addBreadcrumb($page_title);

    $this->renderForm($form, $lotto, array(
            'action' => '',
            'method' => 'post',
            'toolbar' => false
    ), $errors);
