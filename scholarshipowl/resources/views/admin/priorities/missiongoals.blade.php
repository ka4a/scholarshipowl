@extends("admin/base")
@section("content")


<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<style>
    .sortable {
        list-style-type: none;
        margin: 0;
        padding: 0;
        width: 450px;
    }

    .sortable li {
        margin: 3px 3px 3px 0;
        padding: 1px;
        float: left;
        width: 100px;
        height: 90px;
        text-align: center;
    }
</style>
<script>
    $(function () {
        $(".sortable").sortable();
        $(".sortable").disableSelection();
        $( ".save_layout" ).click(function() {
            var id = $(this).attr('id');

            var ids = id.split('_');
            id = ids[2];
            var sortableId = "#sortable-"+id;
            var layout = $(sortableId).sortable( "toArray" );
            $.ajax({
                type: "POST",
                data: {layout:layout},
                url: "/admin/priorities/savemissiongoals",
                success: function(msg){
//                    alert(msg);
                }
            });
        });


    });
</script>


    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-save"></i>
                    <span>Packages</span>
                </div>
                <div class="box-icons">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="expand-link">
                        <i class="fa fa-expand"></i>
                    </a>
                </div>
                <div class="no-move"></div>
            </div>
                <div class="box-content">
                    @foreach($missions as $missionId => $missionGoals)
                    <div class="well span12">
                    Mission : {{ $missionId }}
                        <div class="sortableHolder">
                            <ul class="sortable" id="sortable-{{$missionId}}">
                                @foreach($missionGoals as $missionGoal)
                                    <li class="ui-state-default sortable-item" id="{{$missionId}}-{{ $missionGoal[0]}}" >{{ $missionGoal[1] }} / {{ $missionGoal[0] }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <br/>
                        <button class="save_layout" id="save_layout_{{$missionId}}">Save Layout</button>
                        </div>
                    @endforeach
                </div>

        </div>
    </div>

@stop
