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
});

$(function() {
    /* FASTCLICK */
    FastClick.attach(document.body);

    /* jQUERY SWIPE PLUGINS */
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
});