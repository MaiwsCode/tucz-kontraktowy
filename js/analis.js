(function ($) {

    var sortCrits = {
        'name' : false,
        'dateStart' : false,
        'nrKey' : false,
        'status' : false,
        'deliveredAmount' : false,
        'recivedAmount' : false,
        'weightSmallPigs' : false,
        'year' : false,
        'falls' : false,
        'weightBigPig' : false,
        'feedDeliverer' : false,
        'smallPigEats' : false,
        'brutto': false,
        'dateRecived' : false
    };

    $(".sort").live("click", function(){
        openFilterBox(this.id);

    });


    function openFilterBox(id){
        console.log(id);
    }

})(jQuery);