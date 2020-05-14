
$(function () {

    var $_this = '';

    var $pr = $(".box-pr .box-path-list ul");
    var $city = $('.box-tmp-ul-panel ul.city');
    var $area = $('.box-tmp-ul-panel ul.area');

    var str_pr = "";//存储数据的变量
    var str_city = "";//存储数据的变量
    var str_area = "";//存储数据的变量

    var $addr_pr = ''; // 保存省
    var $addr_city = ''; // 保存城市
    var $addr_area = ''; // 保存区

    $pr.empty();//清空内容
    $city.empty();//清空内容
    $area.empty();//清空内容
    $.each($.addr_list,function(key_1,val_1){
        str_pr += '<li><a href="javascript:;"><span>' + key_1 + '</span></a></li>';

        $.each(val_1,function(key_2, val_2){
            str_city += '<li data-top="' + key_1 + '"><a href="javascript:;"><span>' + key_2 + '</span></a></li>';

            $.each(val_2,function(key_3, val_3){
                str_area += '<li data-top="' + key_2 + '"><a href="javascript:;"><span>' + val_3 + '</span></a></li>';
            });
        });

    });
    $pr.html(str_pr);//显示处理后的数据
    $city.html(str_city);//显示处理后的数据
    $area.html(str_area);//显示处理后的数据

    $('.box-pr .box-path-list a').click(function () {
        var $obj = $(this);
        var $txt = $obj.children('span').text();
        var $show_city_ul = $('.box-city .box-path-list ul');

        $addr_pr = $txt;
        $show_city_ul.empty();

        $('.box-tmp-ul-panel ul.city').find('li[data-top="' + $txt + '"]').clone(true)
            .appendTo($show_city_ul);

        $('.box-city').css({'display': 'block'});

        $('.box-pr').css({'display': 'none'});
    });

    $(document).on('click', '.box-city .box-path-list a', function () {
        var $obj = $(this);
        var $txt = $obj.children('span').text();
        var $show_area_ul = $('.box-area .box-path-list ul');

        $addr_city = $txt;
        $show_area_ul.empty();

        $('.box-tmp-ul-panel ul.area').find('li[data-top="' + $txt + '"]').clone(true)
            .appendTo($show_area_ul);

        $('.box-area').css({'display': 'block'});
        $('.box-city').css({'display': 'none'});
    });

    $(document).on('click', '.box-area .box-path-list a', function () {
        var $obj = $(this);
        var $txt = $obj.children('span').text();

        $addr_area = $txt;

        $_this.val($addr_pr + ' ' + $addr_city + ' ' + $addr_area);
        $_this_parent = $_this.closest('.controls');
        $_this_parent.find('.input-province').val($addr_pr);
        $_this_parent.find('.input-city').val($addr_city);
        $_this_parent.find('.input-area').val($addr_area);

        $('.box-area').hide();
        $('.box-address').hide();
        $('.box-address-shadow').hide();
    });

    $('.input-harea').click(function (e) {
        $_this = $(this);
        $('.box-address-shadow').css({'display': 'block'});
        $('.box-address').css({'display': 'block'});
        $('.box-pr').css({'display': 'block'});
    });

    $('#pr-close').click(function () {
        $('.box-pr').hide();
        $('.box-address').hide();
        $('.box-address-shadow').hide();
    });
    $('.box-address-shadow').click(function () {
        $('.box-address').hide();
        $(this).hide();
    });
    $('#city-close').click(function () {
        $('.box-pr').css({'display': 'block'});
        $('.box-city').css({'display': 'none'});
    });
    $('#area-close').click(function () {
        $('.box-city').css({'display': 'block'});
        $('.box-area-m').css({'display': 'none'});
    });
});