
// Table sorter
//<![CDATA[

$(function() {
    $(".tablesorter").delegate(".toggle", "click" ,function(){
        var $state = $(this).closest("tr").nextUntil("tr:not(.tablesorter-childRow)").find("td").is(":visible");
        $(".tablesorter-childRow").find("td").hide();
        if($state) {
            $(this).closest("tr").nextUntil("tr:not(.tablesorter-childRow)").find("td").hide();
        }else{
            $(this).closest("tr").nextUntil("tr:not(.tablesorter-childRow)").find("td").show();
        }
        return false;
    });
    $(".tablesorter").bind("sortStart",function(e, table) {
        $(".tablesorter-childRow").find("td").hide();
    })
    $(".tablesorter").delegate(".checkAllWrapper", "click" ,function(){
        if ($("#selectAll").hasClass("hidden")) {
            $("#selectNone").addClass("hidden");
            $("#selectAll").removeClass("hidden");
            $(".ApplyCheckbox").prop("checked", false);
        }else{
            $("#selectAll").addClass("hidden");
            $("#selectNone").removeClass("hidden");
            $(".ApplyCheckbox").prop("checked", true);
        }
        return false;
    });
});
//]]>
