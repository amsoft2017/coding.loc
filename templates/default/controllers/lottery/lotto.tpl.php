<?php $this->addMainCSS("templates/{$this->name}/css/reset.css"); ?>
<?php $this->addMainCSS("templates/{$this->name}/css/lott.css"); ?>
<?php $this->addMainJS("templates/{$this->name}/js/lottery/jquery.min.js"); ?>
<?php $this->addMainJS("templates/{$this->name}/js/lottery/jquery.arcticmodal.js"); ?>
<?php $this->addMainJS("templates/{$this->name}/js/lottery/jquery.countdown.js"); ?>



<?php


    if (cmsUser::isLogged() && ($lotto['buy_ticket'] == 1) && ($lotto['price'] > 0)){

        $id = cmsUser::get('id');
        $nickname = cmsUser::get('nickname');
        
        $this->addToolButton(array(
            'class' => 'buy',
            'title' => LANG_LOTTERY_LOTTO_BUY_TICKET_BUTTON,
            'href' => $this->href_to('buy_ticket', array(
                'id_users' => $id,
                'id_lotto' => $lotto['id'],
                'nickname' => $nickname,
                'price' => $lotto['price'],
                

            ))
        ));

    }

    $this->setPageTitle($lotto['name']);
	
    $this->addBreadcrumb(LANG_LOTTERY_CONTROLLER, $this->href_to(''));
    
    $this->addBreadcrumb($lotto['name']);

    if(cmsUser::isAdmin()){

        $this->addToolButton(array(
            'class' => 'edit',
            'title' => LANG_LOTTERY_EDIT_LOTTO,
            'href' => $this->href_to('edit_lotto', $lotto['id'])
        ));

        $this->addToolButton(array(
            'class' => 'delete',
            'title' => LANG_LOTTERY_DELETE_LOTTO,
            'href' => $this->href_to('delete_lotto', $lotto['id'])
        ));

        $this->addToolButton(array(
            'class' => 'add',
            'title' => LANG_LOTTERY_ADD_PLAYER,
            'href' => $this->href_to('add_player')
        ));

    }

?>



<div class="content-box">
    <div class="b-title-line">
        <span id="b-title-line__inner"><?php html($lotto['name']); ?></span>
    </div>
    <div class="left-col">
        <div id="lott-timer-box">
            <?php if($lotto['winner'] === null){?>
                <span class="lott-timer-title">До розыгрыша осталось</span>
                <div id="CDT"></div>
            <?php } else {?>
                <?php $win = cmsModel::yamlToArray($lotto['winner'])?>
                <?php if(count($win) == 1){?>
                    <span class="lott-timer-title">ПРИЗ РОЗЫГРАН ПОБЕДИТЕЛЬ</span>
                    <div id="win">
                        <span class="number-wrapper">
                            <div class="line"></div>
                            <?php foreach($win as $w){ ?>
                            <span class="number end"><a href="/users/<?php echo $w['winner_id']?>"><?php echo $w['nickname']?></a></span>
                            <?php } ?>
                        </span>
                </div>
                <?php }?>

                <?php if(count($win)>1){?>
                    <?php if(!isset($lotto['is_distribution'])) { ?>
                        <span class="lott-timer-title">ПОБЕДИТЕЛИ</span>
                        <ul id="win-list" >
                            <?php foreach($win as $w){ ?>
                            <li class="b-sb"><a href="/users/<?php echo $w['winner_id']?>"> <?php echo $w['nickname'] ?>: <?php echo $w['place'] ?> место</a><li>
                                <?php } ?>
                        </ul>
                    <?php } ?>

                    <?php if(isset($lotto['is_distribution'])) { ?>
                        <span class="lott-timer-title">ПОБЕДИТЕЛИ</span>
                        <ul id="win-list" >
                            <?php foreach($win as $w){  if(!array_key_exists('prize', $w)){continue;}?>
                            <li class="b-sb"><a href="/users/<?php echo $w['winner_id']?>"> <?php echo $w['nickname'] ?>: приз <?php echo $w['prize'] ?></a><li>
                                <?php } ?>
                        </ul>
                    <?php } ?>

                <?php }?>

            <?php }?>
        </div>
        <div class="prize-box">
            <?php echo html_image($lotto['images'],$size_preset='lotto');?>

        </div>

    </div>
    <div class="right-col">



        <div class="user-list-box">
            <div class="split">
            <div class="b-small-title">Участники</div>
            </div>
            <ul id ="user-list" class="b-sb-board">

                <?php if(isset($players)) {

                    $id = cmsUser::get('id');
                
                    foreach($players as $player){ ?>

                        <li><a  href="/users/<?php echo $player['id_users']?>" <?php if($player['id_users'] == $id) { ?>class="color" <?php } ?>> <?php echo $player['nickname'] ?> (билетов <?php echo $player['ticket'] ?>)</a><li>

                    <?php }} ?>
            </ul>

        </div>

    </div>
    <div class="disc">
        <div class="seo_text"> <?php echo $lotto['description'];?></div>
    </div>
</div>
<script type="text/javascript">
    function CountdownTimer(elm,tl,mes){
        this.initialize.apply(this,arguments);
    }
    CountdownTimer.prototype={
        initialize:function(elm,tl,mes) {
            this.elem = document.getElementById(elm);
            this.tl = tl;
            this.mes = mes;
        },countDown:function(){
            var timer='';
            var today=new Date();
            var day=Math.floor((this.tl-today)/(24*60*60*1000));
            var hour=Math.floor(((this.tl-today)%(24*60*60*1000))/(60*60*1000));
            var min=Math.floor(((this.tl-today)%(24*60*60*1000))/(60*1000))%60;
            var sec=Math.floor(((this.tl-today)%(24*60*60*1000))/1000)%60%60;
            var me=this;

            if( ( this.tl - today ) > 0 ){
                timer += '<span class="number-wrapper"><div class="line"></div><div class="caption">Дни</div><span class="number day">'+day+'</span></span>';
                timer += '<span class="number-wrapper"><div class="line"></div><div class="caption">Часы</div><span class="number hour">'+hour+'</span></span>';
                timer += '<span class="number-wrapper"><div class="line"></div><div class="caption">Минуты</div><span class="number min">'+this.addZero(min)+'</span></span><span class="number-wrapper"><div class="line"></div><div class="caption">Секунды</div><span class="number sec">'+this.addZero(sec)+'</span></span>';
                this.elem.innerHTML = timer;
                tid = setTimeout( function(){me.countDown();},10 );
            }else{
                this.elem.innerHTML = this.mes;
                return;
            }
        },addZero:function(num){ return ('0'+num).slice(-2); }
    }
    function CDT(){

        // Set countdown limit
        var tl = new Date('<?php echo $lotto['start_date'];?>');

        // You can add time's up message here
        var timer = new CountdownTimer('CDT',tl,'<span class="number-wrapper"><div class="line"></div><span class="number end">Идет розыгрыш!</span></span>');
        timer.countDown();
    }
    window.onload=function(){
        CDT();
    }
</script>