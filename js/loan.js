(function ($) {

    var srcDown = 'data/Base_Theme/templates/default/Utils/GenericBrowser/move-down.png';
    var srcUp = 'data/Base_Theme/templates/default/Utils/GenericBrowser/move-up.png';
  
    $(".expand").live("click", function(){
        var name = $(this).attr('id');
        var id = name.replace("loan_","");
        $("."+name).css('display','flex');
        $(this).removeClass("expand");
        $(this).addClass("collapse");
        $(this).children("img").attr("src", srcUp);

        $.ajax({
            url: 'modules/tuczkontraktowy/loans_ajax.php',
            method: 'GET',

            data: {
                'action':'add',
                'id': id,
            },
            success: function (data) {

            },

        });

    });
    $(".collapse").live("click", function(){
        var name = $(this).attr('id');
        var id = name.replace("loan_","");
        $("."+name).hide();
        $(this).removeClass("collapse");
        $(this).addClass("expand");
        $(this).children("img").attr("src", srcDown);

        $.ajax({
            url: 'modules/tuczkontraktowy/loans_ajax.php',
            method: 'GET',

            data: {
                'action':'del',
                'id': id,
            },
            success: function (data) {

            },

        });
    });

})(jQuery);