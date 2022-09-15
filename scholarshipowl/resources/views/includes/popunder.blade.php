<script type="text/javascript" src="{!! asset("assets/js/jquery.popunder.min.js") !!}"></script>
<script>
    var aPopunder = new Array();
    aPopunder[0] = ["{!! url_builder("clicks") !!}?ff=1", {blocktime: 1}];
    $(".Register2Button").on("click", function(){
        var valid = true;
        $("select.selectpicker").each(function(){
            if($(this).attr("name") != "school_level_id"){
                if($(this).val() === ""){
                    valid = false;
                }
            }
        });
        if (!$("input[name='gender']").is(':checked')) {
            valid = false;
        }
        if (!$("input[name='study_online']").is(':checked')) {
            valid = false;
        }
        if(valid) {
            $.popunder.helper.def.fs = "{!! asset("assets/js/jq-pu-toolkit.swf") !!}";
            $.popunder(aPopunder);
        }
    });
</script>