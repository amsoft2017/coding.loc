<?php
    $this->addCSS("templates/{$this->name}/css/pricelist.css");
    $this->addJS("templates/{$this->name}/js/ampricelist/jquery.maskedinput.min.js");

    $key = array("name", "price", "init");
    if($widget->options['text']){
        $text = preg_split("/\\r\\n?|\\n/", $widget->options['text']);
        for($i = 0; $i<count($text); $i++){
            $price_list[] = array_combine($key, explode("/", $text[$i]));
        }
    }

    $captcha_html = cmsEventsManager::hook('captcha_html');

    if($widget->options['email']){
        $email_to = $widget->options['email'];
    }

    ?>

<div class="table-responsive">
    <form class="form-inline" action="/ampricelist" method="post">
        <table class="table table-striped ">
            <thead style="background-color: <?php echo $widget->options['color_thead']?>">
            <tr>
                <th>#</th>
                <th><?php echo LANG_WD_PRICELIST_TABLE_THEAD_NAME; ?></th>
                <th><?php echo LANG_WD_PRICELIST_TABLE_THEAD_CENA; ?></th>
                <th><?php echo LANG_WD_PRICELIST_TABLE_THEAD_COL; ?></th>
                <th><?php echo LANG_WD_PRICELIST_TABLE_THEAD_COST; ?></th>
            </tr>
            </thead>
            <tbody>

                    <?php
                        $n = 1;
                        foreach($price_list as $price){?>
                     <?php if(empty($price['price']) AND empty($price['init'])){?>

                                <tr>
                                    <td class="title_in_table" style="background-color: <?php echo $widget->options['color_blok']?>" colspan="5"><div><?php echo $price['name']?></div><?php continue;?></td>
                                    </tr>

                            <?php }?>
                            <tr>
                        <td><?php echo $n ?></td>
                        <td><?php echo $price['name']?><input type="hidden" name="name<?php echo $n?>" value="<?php echo $price['name']?>"></td>
                        <td><?php echo $price['price']?><span class="rub">&nbsp;&#8381;</span><input type="hidden" name="cena<?php echo $n?>" id="cena<?php echo $n?>" value="<?php echo $price['price']?>" onchange="costCalculator()"></td>
                        <td><input type="number" min="0" style="width: 4em" name="col<?php echo $n?>" id="price<?php echo $n ?>" value="0" onchange="costCalculator()">&nbsp;<?php echo $price['init']?></td>
                        <td><strong><span id="Rezult<?php echo $n ?>">0</span><span class="rub">&nbsp;&#8381;</span></strong></td>
                    </tr>
                    <?php $n++; } $n--;?>

                    <input type="hidden" name="count" value="<?php echo $n;?>">
                    <input type="hidden" name="email_to" value="<?php echo $email_to;?>">

            </tbody>

        </table>
        <table class="table table-striped table-bordered ">
            <tbody>
            <tr>

                <td>
                    <?php echo $captcha_html; ?>
                    <?php if($widget->options['email_reply_to']){?>
                        <div class="form-group">
                            <input  type="email" class="form-control" name="email_reply_to" placeholder="Укажите свой E-mail" required>
                        </div>
                    <?php }?>
                    <?php if($widget->options['tel_reply_to']){?>
                        <div class="form-group">
                            <input  type="text" class="form-control phone" name="phone" placeholder="Укажите номер телефона" required>
                        </div>
                    <?php }?>
                    <button type="submit" name="send" class="btn btn-success " value=""><?php echo LANG_WD_PRICELIST_FORM_BUTTON; ?></button>
                    <p class="result text-center"><?php echo LANG_WD_PRICELIST_TABLE_TOTAL;?><span id="result">0</span> <span class="rub">&#8381;</span></p>
                    <?php if($widget->options['page_agreement']){?>
                        <p style="font-size: 9px"> Нажимая на кнопку "Отправить заявку", вы даете согласие <a href="<?=$widget->options['page_agreement'];?>">на обработку своих персональных данных</a> </p>
                    <?php }?>
                </td>
            </tr>

            </tbody>
        </table>
    </form>

</div>

<script type="text/javascript">
function costCalculator() {
//Типы выбора
    <?php for($i = 1; $i <= $n; $i++){
            $id = "price".$i;
            $id_cena = "cena".$i;
            $Type_cena = "Typecena".$i;
            $Price = "Price".$i;
            $Type = "Type".$i;
            $Rezult = "Rezult".$i;
        ?>
    var <?php echo $Type ?> = document.getElementById("<?php echo $id ?>");
    var <?php echo $Type_cena ?> = document.getElementById("<?php echo $id_cena ?>");

//Цена для выбора по умолчанию

    var <?php echo $Price ?> = 0;

//Умножаем значение на *ЧИСЛО
    <?php echo $Price ?> += parseInt(<?php echo $Type ?>.value) * parseInt(<?php echo $Type_cena?>.value);

//Складываем типы выбора
    //Результат для выбора

    <?php echo $Rezult ?>.innerHTML = <?php echo $Price ?>;

    <?php }?>

    //Общая цена
    var price = 0;

    //Общий результат
    price = <?php for($i = 1; $i <= $n; $i++){
        $Price = "Price".$i. "+";
        $End ="Price".$i;
        ?><?php if($i < $n){ echo $Price; }else{ echo $End; }?><?php }?>;


    var result = document.getElementById("result");
    result.innerHTML = price;

}
</script>
<script type="text/javascript">
    $(function($){
        $(".phone").mask("+7 (999) 999-9999",{placeholder:" "});
    });
</script>

