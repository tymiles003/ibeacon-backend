@extends('admin.layout')
@section('content')
<div class="row">
    <div class="main_header">
                    Queue Stat
                    </div>
                                <div class="main_body">
                <div class="col-xs-12">
                
                    <div class="module">
                        <div class="module_title">
                        Average waiting time
                        </div>
                        <div class="module_content module_blue" id="myfirstchart" style="height: 250px;"></div>
                        <div class="module_toolbar">
                        Year: <select class="chart_select"><option value="2014">2014</option></select>
                        Month: <select class="chart_select"><option value="10">October</option></select>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                
                    <div class="module">
                        <div class="module_title">
                        Entrance Rate
                        </div>
                        <div class="module_content module_green" id="myfirstchart2" style="height: 250px;"></div>
                    </div>
                </div>
                </div>
            </div>
@stop

@section('script')
<script>
$(document).ready(function() {
    $("#myfirstchart").html('');
    var graph = new Morris.Line({
      element: 'myfirstchart',
      hoverCallback: function (index, options, content, row) {
        return "<b>"+row.day+"</b><br />"+row.value+"s";
      },
      xLabels: 'day',
      pointFillColors: ['#93DCFC'],
      pointStrokeColors: ['#93DCFC'],
      gridTextColor: ['#fff'],
      lineColors: ['#fff'],
      xkey: 'day',
      ykeys: ['value'],
      postUnits: 's',
      labels: ['Time'],
      smooth: false,
      resize: true
    });
    var graph2 = new Morris.Line({
      element: 'myfirstchart2',
      hoverCallback: function (index, options, content, row) {
        return "<b>"+row.day+"</b><br />"+row.value+"%";
      },
      xLabels: 'day',
      pointFillColors: ['#93DCFC'],
      pointStrokeColors: ['#93DCFC'],
      gridTextColor: ['#fff'],
      lineColors: ['#fff'],
      xkey: 'day',
      ykeys: ['value'],
      postUnits: '%',
      labels: ['Time'],
      smooth: false,
      resize: true
    });
    $.get( "http://fyp/queues/avgWaitingTime/"+{{$type}}, function( data ) {
      graph.setData(data);
    });
    $.get( "http://fyp/queues/entranceRate/"+{{$type}}, function( data ) {
      console.log(data);
      graph2.setData(data);
    });
});
</script>
@stop