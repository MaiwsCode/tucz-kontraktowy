
function setElements ($) {
    $(".line").last().addClass("last");
    var id = $(".line").last().attr('id').replace("F","");
    id = parseInt(id);
    $(".line").last().attr("data-last", id);
};

(function($){
    $(document).ready( function (){ 
        $(document).on('click', '.addLimit', function(){
            var lastItem = $(".last");
            var id = lastItem.attr('data-last');
            id = parseInt(id);
            id += 1;
            $("#example").clone()
            .removeClass("hide")
            .addClass("form-row")
            .addClass("mb-1")
            .addClass("line")
            .attr('id', 'F' + id)
            .insertBefore(".last");
            $("#F" + id + " .changeR").attr("id", "R"+id);
            $("#F" + id + " .changeL").attr("id", "L"+id);
            $("#F" + id + " .changeMultipler").attr("id", "M"+id);
            $(".line").last().attr("data-last", id);
        });

        $(document).on('click', '.saveLimits', function(){
            //is tucz or global changes
            var isTucz = $("#tucz").val();
            var limits = $(".line");
            var limitsArray = [];
            limits.each( function (index, box)  {
                var id = $(box).attr("id");
                id = id.replace("F","");
                var item = '';
                if( parseFloat($("#L" + id).val() ) ) {
                    var itemL = $("#L" + id);
                    var valueL = itemL.val();
                    var operatorL = itemL.attr('data-operator');
                    item += operatorL + "_" + valueL + ";";
                }
                if( parseFloat($("#R" + id).val() ) ) {
                    var itemR = $("#R" + id);
                    var valueR = itemR.val();
                    var operatorR = itemR.attr('data-operator');
                    item += operatorR + "_" + valueR;
                }
                if( $("#M" + id) ) {
                    var multipler = $("#M" + id);
                    var multiplerValue = multipler.val();
                    
                }

                limitsArray[index] = {
                    'v': item,
                    'm' : multiplerValue
                };
            });
        if(Boolean(isTucz)) {
                $.ajax({
                    'url': 'modules/tuczkontraktowy/limits.php',
                    'method': 'POST',
                    data: {
                        'updatePerRecord' : 1,
                        'updateRecordId' : isTucz,
                        'values': JSON.stringify(limitsArray)
                    }
                });
        }else{
                $.ajax({
                    'url': 'modules/tuczkontraktowy/limits.php',
                    'method': 'POST',
                    data: {
                        'updatePerRecord' : 0,
                        'values': JSON.stringify(limitsArray)
                    }
                });
        }
        });
    });
}(jQuery));
