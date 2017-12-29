<?php $this->addMainCSS("templates/{$this->name}/css/lott-bootstrap.css"); ?>
<?php $this->addMainCSS("templates/{$this->name}/css/bootstrap.min.css"); ?>
<?php $this->addMainJS("templates/{$this->name}/js/lottery/jquery.min.js"); ?>

<?php

    if(!empty($lotto['seo_keys'])){
        $this->setPageKeywords($lotto['seo_keys']);
    }
    if(!empty($lotto['seo_desc'])){
        $this->setPageDescription($lotto['seo_desc']);
    }

    $seo_title = !empty($lotto['seo_title']) ? $lotto['seo_title'] : $lotto['name'];
    $this->setPageTitle($seo_title);

    if(cmsUser::isLogged() && ($lotto['buy_ticket'] == 1) && ($lotto['price'] > 0)){

        $id = cmsUser::get('id');
        $nickname = cmsUser::get('nickname');

        $this->addToolButton([
            'class' => 'buy',
            'title' => LANG_LOTTERY_LOTTO_BUY_TICKET_BUTTON,
            'href' => $this->href_to('buy_ticket', [
                'id_users' => $id,
                'id_lotto' => $lotto['id'],
                'nickname' => $nickname,
                'price' => $lotto['price'],


            ]),
        ]);

    }

    $this->addBreadcrumb(LANG_LOTTERY_CONTROLLER, $this->href_to(''));

    $this->addBreadcrumb($lotto['name']);

    if(cmsUser::isAdmin()){

        $this->addToolButton([
            'class' => 'edit',
            'title' => LANG_LOTTERY_EDIT_LOTTO,
            'href' => $this->href_to('edit_lotto', $lotto['id']),
        ]);

        $this->addToolButton([
            'class' => 'delete',
            'title' => LANG_LOTTERY_DELETE_LOTTO,
            'href' => $this->href_to('delete_lotto', $lotto['id']),
        ]);

        $this->addToolButton([
            'class' => 'add',
            'title' => LANG_LOTTERY_ADD_PLAYER,
            'href' => $this->href_to('add_player'),
        ]);

    }

?>
<div class="container-fluid">
    <div class="row bg-lotto ">
        <div class="col-sm-12">


            <div>

                <div class="header page-header"><?php html($lotto['name']); ?></div>

                <?php if($lotto['status'] == 0){ ?>
                    <div class="header-timer">ДО РОЗЫГРЫША ОСТАЛОСЬ</div>
                <?php }else{ ?>

                    <div class="header-timer">РОЗЫГРЫШ СОСТОЯЛСЯ</div>
                <?php } ?>
                <div class="countdown styled"></div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-default" style="position: unset;">
            <div class="panel-heading">
                <h3 class="panel-title">Условия и описание</h3>
            </div>
            <div class="panel-body">
                <?php echo $lotto['description']; ?>
            </div>
        </div>

    </div>
    <?php if($lotto['status'] == 0){ ?>

        <div class="row ticket">


            <div class="panel panel-default" style="position: unset;">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-12 col-md-8"><h3 class="panel-title">В розыгрыше участвуют</h3></div>
                        <?php if(cmsUser::isLogged() && ($players)) foreach($players as $player){ ?>
                            <?php $id = cmsUser::get('id'); ?>
                            <?php if($player['id_user'] == $id){ ?>

                                <div class="col-xs-6 col-md-4">
                                    <div class="count_ticket">
                                        <h3 class="label label-danger "> У Вас <?php echo html_spellcount($player['count_tickets'], 'билет', 'билета', 'билетов', 'Вы не участвуете') ;  ?></h3>
                                    </div>
                                </div>
                            <?php }
                        } ?>
                    </div>

                </div>
                <div class="panel-body">
                    <div class="row">
                        <?php if($players){
                            foreach($players as $player){?>


                                <div class="col-md-2 " style="display: inline-table;">
                                    <a href="/users/<?php echo $player['id_user'] ?>"
                                       title="<?php echo $player['user_nickname'] ?>" class="thumbnail">
                                        <div class="over"><span
                                                    class="label label-default"><?php echo $player['user_nickname'] ?></span>
                                        </div>
                                        <img class="img-thumbnail"
                                             src="<?php echo html_avatar_image_src($player['user_avatar']); ?>">


                                        <div><span
                                                    class="label label-success"><?php echo html_spellcount($player['count_tickets'], 'билет', 'билета', 'билетов') ;  ?></span>
                                        </div>
                                    </a>

                                </div>


                        <?php } } ?>


                    </div>
                </div>
            </div>


        </div>

    <?php }else{ ?>

        <?php $win = cmsModel::yamlToArray($lotto['winner']) ?>
        <?php if(count($win) == 1){ ?>

            <div class="row ticket">


                <div class="panel panel-default" style="position: unset;">
                    <div class="panel-heading">
                        <h3 class="panel-title">СПИСОК ПОБЕДИТЕЛЕЙ</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">


                            <?php foreach($win as $w){
                                $id = $w['winner_id'];
                                $user = $users["$id"];
                                ?>
                                <div class="col-md-2 " style="display: inline-table;">
                                    <a href="/users/<?php echo $w['winner_id'] ?>" title="<?php echo $w['nickname'] ?>"
                                       class="thumbnail">
                                        <div><span class="label label-default"><?php echo $w['nickname'] ?></span></div>
                                        <img class="img-thumbnail"
                                             src="<?php echo html_avatar_image_src($user['avatar']); ?>">
                                        <div><span class="label label-success">Победитель</span></div>
                                    </a>

                                </div>
                            <?php } ?>


                        </div>
                    </div>
                </div>


            </div>

        <?php } ?>


        <?php if(count($win) > 1){ ?>
            <?php if(!isset($lotto['is_distribution'])){ ?>
                <div class="row ticket">


                    <div class="panel panel-default" style="position: unset;">
                        <div class="panel-heading">
                            <h3 class="panel-title">СПИСОК ПОБЕДИТЕЛЕЙ</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">


                                <?php foreach($win as $w){
                                    $id = $w['winner_id'];
                                    $user = $users["$id"];
                                    ?>
                                    <div class="col-md-2 " style="display: inline-table;">
                                        <a href="/users/<?php echo $w['winner_id'] ?>"
                                           title="<?php echo $w['nickname'] ?>"
                                           class="thumbnail">
                                            <div><span
                                                        class="label label-default"><?php echo $w['nickname'] ?></span>
                                            </div>
                                            <img class="img-thumbnail"
                                                 src="<?php echo html_avatar_image_src($user['avatar']); ?>">
                                            <div><span class="label label-success"><?php echo $w['place'] ?>
                                                    Место </span></div>
                                        </a>

                                    </div>
                                <?php } ?>


                            </div>
                        </div>
                    </div>


                </div>
            <?php } ?>

            <?php if(isset($lotto['is_distribution'])){ ?>


                <div class="row ticket">


                    <div class="panel panel-default" style="position: unset;">
                        <div class="panel-heading">
                            <h3 class="panel-title">СПИСОК ПОБЕДИТЕЛЕЙ</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">


                                <?php foreach($win as $w){
                                    if(!array_key_exists('prize', $w)){
                                        continue;
                                    }
                                    $id = $w['winner_id'];
                                    $user = $users["$id"];
                                    ?>
                                    <div class="col-md-2 " style="display: inline-table;">
                                        <a href="/users/<?php echo $w['winner_id'] ?>"
                                           title="<?php echo $w['nickname'] ?>"
                                           class="thumbnail">
                                            <div><span
                                                        class="label label-default"><?php echo $w['nickname'] ?></span>
                                            </div>
                                            <img class="img-thumbnail"
                                                 src="<?php echo html_avatar_image_src($user['avatar']); ?>">
                                            <div><span class="label label-success"><?php echo $w['prize'] ?></span>
                                            </div>
                                        </a>

                                    </div>
                                <?php } ?>


                            </div>
                        </div>
                    </div>


                </div>
            <?php } ?>

        <?php } ?>
    <?php } ?>
</div>
<style>

    .styled div {
        display: inline-block;
        margin-left: 10px;
        font-size: 42px;
        font-weight: normal;
        text-align: center;
        /* margin: 0 25px; */
        height: 100px;
        text-shadow: none;
        vertical-align: middle;
        color: #FFFFFF;
        padding: 10px 30px;
        height: auto;
        border-style: inset;
        border-color: rgba(255, 255, 255, 0.45);
        border: 0;

    }

    .ticket a {
        background-color: <?php echo $lotto['color_users']?>;
    }

    .countdown {
        text-align: center;
        margin: 0 auto 100px;
        background: <?php echo $lotto['color_timer']?>;
        border-radius: 20pc;
        max-width: 650px;

    }

    .bg-lotto {
        background-image: url(../../upload/<?php echo html_image_src($lotto['bg_lott'], $size_preset='bg_lott'); ?>);
        background-size: cover;

    }

    .header-timer {

        color: <?php echo $lotto['color_header']?>;
        font-size: 20px;
        font-weight: 400;
        padding: 10px;
        margin-top: 50px;
        text-align: center;
    }

    .header {
        border-bottom: 1px solid #EEEEEE;
        color: <?php echo $lotto['color_name']?>;
        font-size: 30px;
        font-weight: 400;
        padding-bottom: 10px;
        text-align: center;
        font-family: fantasy;
    }


</style>

<?php $this->insertJS("templates/{$this->name}/js/lottery/jquery.countdown.js"); ?>
<?php $this->insertJS("templates/{$this->name}/js/lottery/bootstrap.min.js"); ?>
<script type="text/javascript">

    $(function () {
        var endDate = '<?php echo $lotto['start_date'];?>';

        $('.countdown.simple').countdown({date: endDate});

        $('.countdown.styled').countdown({
            date: endDate,
            render: function (data) {
                $(this.el).html("<div>" + this.leadingZeros(data.days, 3) + " <span>Дней</span></div><div>" + this.leadingZeros(data.hours, 2) + " <span>Часов</span></div><div>" + this.leadingZeros(data.min, 2) + " <span>Минут</span></div><div>" + this.leadingZeros(data.sec, 2) + " <span>Секунд</span></div>");
            }
        });

        $('.countdown.callback').countdown({
            date: +(new Date) + 10000,
            render: function (data) {
                $(this.el).text(this.leadingZeros(data.sec, 2) + " sec");
            },
            onEnd: function () {
                $(this.el).addClass('ended');
            }
        }).on("click", function () {
            $(this).removeClass('ended').data('countdown').update(+(new Date) + 10000).start();
        });


    });


    var customScripts = {

        onePageNav: function () {

            $('#mainNav').onePageNav({
                currentClass: 'active',
                changeHash: false,
                scrollSpeed: 950,
                scrollThreshold: 0.2,
                filter: '',
                easing: 'swing',
                begin: function () {
                    //I get fired when the animation is starting
                },
                end: function () {
                    //I get fired when the animation is ending
                    if (!$('#main-nav ul li:first-child').hasClass('active')) {
                        $('.header').addClass('addBg');
                    } else {
                        $('.header').removeClass('addBg');
                    }

                },
                scrollChange: function ($currentListItem) {
                    //I get fired when you enter a section and I pass the list item of the section
                    if (!$('#main-nav ul li:first-child').hasClass('active')) {
                        $('.header').addClass('addBg');
                    } else {
                        $('.header').removeClass('addBg');
                    }
                }
            });

            $("a[href='#top']").click(function () {
                $("html, body").animate({scrollTop: 0}, "slow");
                return false;
            });
            $("a[href='#basics']").click(function () {
                $("html, body").animate({scrollTop: $('#services').offset().top}, "slow");
                return false;
            });
        },
        waySlide: function () {
            /* Waypoints Animations
             ------------------------------------------------------ */
            $('#services').waypoint(function () {
                $('#services .col-md-3').addClass('animated fadeInUp show');
            }, {offset: 800});
            $('#aboutUs').waypoint(function () {
                $('#aboutUs').addClass('animated fadeInUp show');
            }, {offset: 800});
            $('#contactUs').waypoint(function () {
                $('#contactUs .parlex-back').addClass('animated fadeInUp show');
            }, {offset: 800});

        },
        init: function () {
            customScripts.onePageNav();
            customScripts.waySlide();
        }
    }


</script>

