$(function(){
    var $div_shang = $('<div></div>');
    $div_shang.addClass('shang');
    var $div_shang_wechat = $('<div></div>');
    $div_shang_wechat.addClass('shang-wechat-qrcode');
    $div_shang.show().appendTo($(document.body));
    $div_shang_wechat.hide().appendTo($(document.body));
    $div_shang.mouseover(function(){
        $div_shang_wechat.css({
            'left': $div_shang.offset().left + $div_shang.outerWidth() / 2 - $div_shang_wechat.outerWidth() / 2,
            'top': $div_shang.offset().top + $div_shang.outerHeight()
        });
        $div_shang_wechat.show();
    });
    $div_shang.mouseout(function(){$div_shang_wechat.hide()});
    $div_shang_wechat.mouseover(function(){$div_shang_wechat.show()});
    $div_shang_wechat.mouseout(function(){$div_shang_wechat.hide()});
});
