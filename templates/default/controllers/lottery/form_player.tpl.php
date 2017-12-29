<?php

    $this->addBreadcrumb(LANG_LOTTERY_CONTROLLER, $this->href_to(''));

    if ($do == 'add') { 
        $page_title = LANG_LOTTERY_ADD_PLAYER; 
    }

    if ($do == 'edit') { 
        $page_title = LANG_LOTTERY_EDIT_PLAYER; 
        $this->addBreadcrumb($player['title'], $this->href_to('player', $player['id']));
    }

    $this->setPageTitle($page_title);

    $this->addBreadcrumb($page_title);

?>

<h1><?php echo $page_title; ?></h1> 

<?php
    $this->renderForm($form, $player, array(
            'action' => '',
            'method' => 'post',
            'toolbar' => false
    ), $errors);
