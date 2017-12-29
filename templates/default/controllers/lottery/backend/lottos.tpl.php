<?php

    $this->addBreadcrumb(LANG_LOTTERY_LOTTOS);

    $this->addToolButton(array(
        'class' => 'add',
        'title' => LANG_LOTTERY_ADD_LOTTO,
        'href'  => $this->href_to('add_lotto')
    ));

    $this->addToolButton(array(
        'class' => 'save',
        'title' => LANG_SAVE,
        'href'  => null,
        'onclick' => "icms.datagrid.submit('{$this->href_to('lottos_reorder')}')"
    ));

?>

<?php $this->renderGrid($this->href_to('lottos_ajax'), $grid); ?>

<div class="buttons">
    <?php echo html_button(LANG_SAVE_ORDER, 'save_button', "icms.datagrid.submit('{$this->href_to('lottos_reorder')}')"); ?>
</div>
