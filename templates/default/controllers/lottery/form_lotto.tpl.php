<?php

    $this->addBreadcrumb(LANG_LOTTERY_CONTROLLER, $this->href_to(''));

    if ($do == 'add') { 
        $page_title = LANG_LOTTERY_ADD_LOTTO; 
    }

    if ($do == 'edit') { 
        $page_title = LANG_LOTTERY_EDIT_LOTTO; 
        $this->addBreadcrumb($lotto['name'], $this->href_to('lotto', $lotto['id']));
    }

    $this->setPageTitle($page_title);

    $this->addBreadcrumb($page_title);

?>


<h1><?php echo $page_title; ?></h1> 


<?php
    $this->renderForm($form, $lotto, array(
            'action' => '',
            'method' => 'post',
            'toolbar' => false
    ), $errors);
