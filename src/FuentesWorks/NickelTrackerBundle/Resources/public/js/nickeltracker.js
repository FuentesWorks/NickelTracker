function changeButtonText(spanId, spanText){
    document.getElementById(spanId).innerHTML = spanText;
    return true;
}

window.onload = (function(){
    // Prevent iOS WebApp from opening a new tab in Safari, thus
    //  escaping its sandbox..
    var a=document.getElementsByTagName("a");
    for(var i=0;i<a.length;i++)
    {
        a[i].onclick=function()
        {
            window.location=this.getAttribute("href");
            return false
        }
    }

    ///// Save button /////
    var s = document.getElementById("btn-save");
    // assign a function to the link on "onclick"
    if(s){
        s.onclick = function() {
            document.getElementById("btn-save-text").innerHTML='Saving..';
            return true;
        };
    }

    ///// DateTime Picker /////
    var any = $("#form_date");
    var from = $("#form_datetime_from");
    var to = $("#form_datetime_to");

    if(any.length != 0) {
        any.datetimepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            minView: 2,
            pickerPosition: "bottom-left"
        });
    }
    if(from.length != 0) {
        from.datetimepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            minView: 2,
            pickerPosition: "bottom-left"
            });
    }
    if(to.length != 0) {
        to.datetimepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            minView: 2,
            pickerPosition: "bottom-left"
        });
    }
});

$(function() {
    /* FASTCLICK */
    FastClick.attach(document.body);

    /* jQUERY SWIPE PLUGINS */
    /* Disabling Swipe plugins for now, until I can find a workaround
       to enable both responsive tables and Swipes.
    $("#main-body").swipe({
        swipeLeft:function(event, direction, distance, duration, fingerCount) {
            //This only fires when the user swipes left
            // Hide the sidebar
            $('.row-offcanvas').toggleClass('active', false);
        },
        swipeRight:function(event, direction, distance, duration, fingerCount) {
            //This only fires when the user swipes left
            // Show the sidebar
            $('.row-offcanvas').toggleClass('active', true);
        }
    });
    */
});

$(document).ready(function () {
    $('[data-toggle=offcanvas]').click(function () {
        $('.row-offcanvas').toggleClass('active')
    });
});

/* Bind the function to Enable or disable the credit card limit field */
$(function () {
    var $select = $("#type");

    if($select.length) {
        updateCreditLimitStatus();
        $select.change(updateCreditLimitStatus);
    }
});

function updateCreditLimitStatus() {
    var $field = $("#creditLimit");
    var $select = $("#type");

    if($select.length && $select.val() == 'C'){
        $field.removeAttr('disabled');
    } else {
        $field.attr('disabled', 'disabled');
        $field.val('');
    }
}