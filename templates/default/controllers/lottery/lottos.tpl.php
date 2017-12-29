<?php $this->addMainCSS("templates/{$this->name}/css/lott.css"); ?>
<?php $this->addMainCSS("templates/{$this->name}/css/lott-bootstrap.css"); ?>
<?php $this->addMainCSS("templates/{$this->name}/css/bootstrap.min.css"); ?>
<?php

    if (!empty($seo['seo_keys'])){ $this->setPageKeywords($seo['seo_keys']); }
    if (!empty($seo['seo_desc'])){ $this->setPageDescription($seo['seo_desc']); }

    $seo_title = !empty($seo['seo_title']) ? $seo['seo_title'] : LANG_LOTTERY_LOTTOS;
    $this->setPageTitle($seo_title);
	
    $this->addBreadcrumb(LANG_LOTTERY_CONTROLLER);

    if(cmsUser::isAdmin()) {

        $this->addToolButton(array(
            'class' => 'add',
            'title' => LANG_LOTTERY_ADD_LOTTO,
            'href' => $this->href_to('add_lotto')
        ));
    }
?>



    <div class="row">

        <?php if (!$lottos) { ?>
            <p><?php echo LANG_LOTTERY_LOTTOS_NONE; ?></p>
            <?php return; ?>
        <?php } ?>




        <div class="col-sm-12">
            <?php foreach($lottos as $lotto) { ?>
            <div class="col-md-4 col-sm-6">
                <div class="card-container">
                    <div class="card">
                        <div class="front">
                            <div class="cover">
                                <?php echo html_image($lotto['poster'],$size_preset='lotto_poster');?>
                            </div>

                            <div class="content">

                                <div class="footer">
                                    <div class="status">
                                        <?php if($lotto['status'] == 0 ) {?>Участвует билетов: <?php echo $lotto['count_player']?><?php }else{?> РОЗЫГРЫШ ЗАВЕРШЕН <?php }?>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end front panel -->

                        <div class="back">
                            <div class="header">
                                <h2 class="motto"><?php echo $lotto['name']; ?></h2>
                            </div>
                            <div class="content">
                                <div class="main">
                                    <?php echo $lotto['preview']; ?>
                                </div>
                            </div>
                            <div class="footer">
                                <a href="<?php echo href_to('lottery','lotto',$lotto['id']); ?>">
                                    <button type="button" class="btn btn-primary btn-lg btn-block">ПОДРОБНЕЕ</button>
                                </a>



                            </div>
                        </div> <!-- end back panel -->
                    </div> <!-- end card -->
                </div> <!-- end card-container -->
            </div> <!-- end col sm 3 -->
            <?php } ?>
        </div>







	
<?php if($total > $perpage) { ?>
    <?php echo html_pagebar($page, $perpage, $total); ?>	
<?php } ?>

        </div>