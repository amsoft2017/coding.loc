/**
 * Created with JetBrains PhpStorm.
 * User: Vitaly
 * Date: 27.02.13
 * Time: 20:42
 * To change this template use File | Settings | File Templates.
 */

/* isset for javascript */
window.isset = function (v) {
    if (typeof(v) == 'object' && v == 'undefined') {
        return false;
    } else  if (arguments.length === 0) {
        return false;
    } else {
        var buff = arguments[0];
        for (var i = 0; i < arguments.length; i++){
            if (typeof(buff) === 'undefined' || buff === null) return false;
            buff = buff[arguments[i+1]];
        }
    }
    return true;
};
function rand( min, max ) {
    if( max ) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    } else {
        return Math.floor(Math.random() * (min + 1));
    }
}

function shuffle(o){
    for(var j, x, i = o.length; i; j = parseInt(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
    return o;
}

var Lottery = {
    loadData: function(vars) {
       var obj = {};

        $.ajax({
            type: 'POST',
            url: 'lototron.php',
            async: true,
            cache: false,
            dataType: 'json',
            data: 'action=get-config',
            success: function(answer) {
                //console.log(answer);
                if(isset(answer.errors)) {
                    $.each(answer.errors, function(k,val) {
                        $.jGrowl(val, {theme: 'errors', header: 'Ошибка!', life: 2000 });
                    });
                   if(isset(answer.lottery) && answer.lottery == 'off') {
                      $('.lott-timer-title').text('Розыгрышей пока нет.');
                       $('#lott-timer-box ul.desc').remove();
                    }
                }
                 else {
                    if(isset(answer.winner)){
                        obj.winner = answer.winner;
                    }
                     else if(answer.start_time > answer.curr_time) {
                        $('#lott-timer').countdown({
                            startTime: answer.difference,
                            stepTime: 50,
                            digitImages: 6,
                            digitWidth: 46,
                            digitHeight: 67,
                            image: 'img/d/digits_transparent-w46.png',
                            timerEnd: function() {
                                Lottery.startLototron({});
                            }
                        });
                    }

                    if(isset(answer.user_list) && answer.user_list.length > 0) {
                         obj.user_list = answer.user_list;
                      }
                    Lottery.printData(obj);
                }
            }
        });
    },
    startLototron: function(vars) {
         var is, obj = {}, ok = true, ld = $.Deferred();
         is = Lottery.runEffects({});

    setTimeout(function() {

        $.ajax({
            type: 'POST',
            url: 'lototron.php',
            async: true,
            cache: false,
            dataType: 'json',
            data: 'action=startlototron',
            success: function(answer) {

                if(isset(answer.errors)) {
                    $.each(answer.errors, function(k,val) {
                        $.jGrowl(val, {theme: 'errors', header: 'Ошибка!', life: 2000 });
                    });
                }
                 else if(isset(answer.winner) && answer.winner.length > 0) {
                    obj.winner = answer.winner;
                 }
            }
        });

    }, 7000);

        is.done(function(){
           if(isset(obj.winner)) {
              Lottery.printData(obj);
            } else {
               Lottery.startLototron({});
           }
        });

    },
    printData: function(vars) {

     if(isset(vars.user_list) && vars.user_list.length > 0) {
        var lines = '';
        $.each(vars.user_list, function(k,val) {
            if(isset(vars.winner) && vars.winner[0].uid == val.uid){
               lines += '<li class="li-winner"><a href="http://vk.com/'+ val.uid +'" data-uid="'+ val.uid +'" target="_blank">'+ val.name +'</a></li>';
            } else {
               lines += '<li><a href="http://vk.com/'+ val.uid +'" data-uid="'+ val.uid +'" target="_blank">'+ val.name +'</a></li>';
            }
        });
        $('#user-list').html(lines);
       }
     if(isset(vars.winner)){
         if(!isset(vars.user_list)){
             var uid, list = $('#user-list li');
         list.removeClass('li-winner');
         $.each(list, function(k,item) {
             uid = $(item).find('a').data('uid');
             if(vars.winner[0].uid == uid) {
                 $(item).addClass('li-winner');
             }
          });
         }
         $('.lott-timer-title').text('Розыгрыш закончен.');
         $('#lott-timer').html('<div class="winner-title">Победитель</div><div class="winner"><a href="http://vk.com/'+ vars.winner[0].uid +'" target="_blank">'+ vars.winner[0].name +'</a></div>')
                         .css({height:'auto',overflow:'none'});
         $('#lott-timer-box ul.desc').remove();
     }
   },
   runEffects: function(vars) {
      var c = 0, list = $('#user-list li'),
          li, r, len = list.length, sh,
          user, si,  ok = true, is = $.Deferred();

       $('.lott-timer-title').text('Лототрон запущен...');
       $('#lott-timer').html('<div class="winner">&nbsp;</div>')
                       .css({height:'auto',overflow:'none'});
       $('#lott-timer-box ul.desc').remove();

      si = setInterval(function() {

            sh = shuffle($('#user-list li'));
            r = rand(0, (len-1));
            li = $(sh[r]);
            list.removeClass('li-winner');
            li.addClass('li-winner');
            user = li.find('a').text();
            $('#user-list').html(sh);
            $('#lott-timer div.winner').text(user);

          ++c;

          if(c > 90) {
             clearInterval(si);
              is.resolve(ok);
           }
        }, 200);

       return is;
   }
};

$(document).ready(function() {

    Lottery.loadData({});

// Get all the thumbnail
    $('div.thumbnail-item').mouseenter(function(e) {

        // Calculate the position of the image tooltip
        x = e.pageX - $(this).offset().left;
        y = e.pageY - $(this).offset().top;

        // Set the z-index of the current item,
        // make sure it's greater than the rest of thumbnail items
        // Set the position and display the image tooltip
        $(this).css('z-index','15')
            .children("div.tooltip")
            .css({'top': y + 10,'left': x + 20,'display':'block'});

    }).mousemove(function(e) {

            // Calculate the position of the image tooltip
            x = e.pageX - $(this).offset().left;
            y = e.pageY - $(this).offset().top;

            // This line causes the tooltip will follow the mouse pointer
            $(this).children("div.tooltip").css({'top': y + 10,'left': x + 20});

        }).mouseleave(function() {

            // Reset the z-index and hide the image tooltip
            $(this).css('z-index','1')
                .children("div.tooltip")
                .animate({"opacity": "hide"}, "fast");
        });


$(document).on('click', '#appme', function() {

        $.arcticmodal({
            type: 'ajax',
            url: 'ajax/appme.html',
            ajax: {
                type: 'POST',
                cache: false
            }
        });
    });

    $(document).on('click', '#rules', function() {

        $.arcticmodal({
            type: 'ajax',
            url: 'ajax/rules.html',
            ajax: {
                type: 'POST',
                cache: false
            }
        });
    });

    $(document).on('click', '#whyiswhy', function() {

        $.arcticmodal({
            type: 'ajax',
            url: 'ajax/whyiswhy.html',
            ajax: {
                type: 'POST',
                cache: false
            }
        });
    });
});
