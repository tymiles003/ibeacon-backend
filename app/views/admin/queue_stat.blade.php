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
                    Year:
                    <select id="year_select_1" class="chart_select">
                        <option value="2014">2014</option>
                    </select>
                    Month:
                    <select id="month_select_1" class="chart_select">
                        <option value="0">Whole year</option>
                        @for ($i = 1, $month=date('m'); $i <= 12; $i++)
                        @if($i == $month)
                        <option selected value="{{$i}}">{{$i}}</option>
                        @else
                        <option value="{{$i}}">{{$i}}</option>
                        @endif
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="col-xs-12">

            <div class="module">
                <div class="module_title">
                    Entrance Rate
                </div>
                <div class="module_content module_green" id="myfirstchart2" style="height: 250px;"></div>
                <div class="module_toolbar">
                    Year:
                    <select id="year_select_2" class="chart_select">
                        <option value="2014">2014</option>
                    </select>
                    Month:
                    <select id="month_select_2" class="chart_select">
                        <option value="0">Whole year</option>
                        @for ($i = 1, $month=date('m'); $i <= 12; $i++)
                        @if($i == $month)
                        <option selected value="{{$i}}">{{$i}}</option>
                        @else
                        <option value="{{$i}}">{{$i}}</option>
                        @endif
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="col-xs-12">

            <div class="module">
                <div class="module_title">
                    Queue Usage
                </div>
                <div class="module_content module_white" id="myfirstchart3" style="height: 250px;"></div>
                <div class="module_toolbar">
                    Year:
                    <select id="year_select_3" class="chart_select">
                        <option value="2014">2014</option>
                    </select>
                    Month:
                    <select id="month_select_3" class="chart_select">
                        <option value="0">Whole year</option>
                        @for ($i = 1, $month=date('m'); $i <= 12; $i++)
                        @if($i == $month)
                        <option selected value="{{$i}}">{{$i}}</option>
                        @else
                        <option value="{{$i}}">{{$i}}</option>
                        @endif
                        @endfor
                    </select>
                    Day:
                    <select id="day_select_3" class="chart_select">
                        <option value="0">Whole year</option>
                        @for ($i = 1, $month=date('d'); $i <= 31; $i++)
                        @if($i == $month)
                        <option selected value="{{$i}}">{{$i}}</option>
                        @else
                        <option value="{{$i}}">{{$i}}</option>
                        @endif
                        @endfor
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
<script>
$(document).ready(function() {
    $("#myfirstchart").html('');
    var currentDate = new Date();
    var currentMonth = currentDate.getMonth() + 1;
    var currentYear = currentDate.getFullYear();
    var currentDay = currentDate.getDate();
    var type = {{$type}};
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
    var graph3 = new Morris.Bar({
        element: 'myfirstchart3',
        data: [],
        xkey: 'hour',
        ykeys: ['value'],
        labels: ['Ticket'],
        resize: true
    });
    $.get( "/queues/avgWaitingTime/"+type+"/year/"+currentYear+"/month/"+currentMonth, function( data ) {
        graph.setData(data);
    });
    $.get( "/queues/entranceRate/"+type+"/year/"+currentYear+"/month/"+currentMonth, function( data ) {
        graph2.setData(data);
    });
    $.get( "/queues/usage/"+type+"/year/"+currentYear+"/month/"+currentMonth+"/day/"+currentDay, function( data ) {
        graph3.setData(data);
    });

    $('#year_select_1').change(function(){

    });
    //for average waitng time graph
    $('#month_select_1, #year_select_1').change(function(){
        $yearVal = $('#year_select_1').val();
        $monthVal = $('#month_select_1').val();
        $.get( "/queues/avgWaitingTime/"+type+"/year/"+$yearVal+"/month/"+$monthVal, function( data ) {
            if(data.length === 0){
                alert ('No data available');
                return;
            }
            if($monthVal === '0'){
                $('#myfirstchart').html('');
                graph = new Morris.Line({
                    element: 'myfirstchart',
                    hoverCallback: function (index, options, content, row) {
                        return "<b>"+row.day+"</b><br />"+row.value+"s";
                    },
                    data: data,
                    xLabels: 'month',
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
            }else{
                $('#myfirstchart').html('');
                graph = new Morris.Line({
                    element: 'myfirstchart',
                    hoverCallback: function (index, options, content, row) {
                        return "<b>"+row.day+"</b><br />"+row.value+"s";
                    },
                    data: data,
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
            }
        });
    });
    //for entrance rate graph
    $('#month_select_2, #year_select_2').change(function(){
        $yearVal = $('#year_select_2').val();
        $monthVal = $('#month_select_2').val();
        $.get( "/queues/entranceRate/"+type+"/year/"+$yearVal+"/month/"+$monthVal, function( data ) {
            if(data.length === 0){
                alert ('No data available');
                return;
            }
            if($monthVal === '0'){
                $('#myfirstchart2').html('');
                graph = new Morris.Line({
                    element: 'myfirstchart2',
                    hoverCallback: function (index, options, content, row) {
                        return "<b>"+row.day+"</b><br />"+row.value+"%";
                    },
                    data: data,
                    xLabels: 'month',
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
            }else{
                $('#myfirstchart2').html('');
                graph = new Morris.Line({
                    element: 'myfirstchart2',
                    hoverCallback: function (index, options, content, row) {
                        return "<b>"+row.day+"</b><br />"+row.value+"%";
                    },
                    data: data,
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
            }
        });
    });
    $('#month_select_3, #year_select_3, #day_select_3').change(function(){
        $yearVal = $('#year_select_3').val();
        $monthVal = $('#month_select_3').val();
        $dayVal = $('#day_select_3').val();
        $.get( "/queues/usage/"+type+"/year/"+$yearVal+"/month/"+$monthVal+"/day/"+$dayVal, function( data ) {
            if(data.length === 0){
                alert ('No data available');
                return;
            }else{
                $('#myfirstchart3').html('');
                graph = Morris.Bar({
                    element: 'myfirstchart3',
                    data: data,
                    xkey: 'hour',
                    ykeys: ['value'],
                    labels: ['Ticket'],
                    resize: true
                });
            }

        });
    });
});
</script>
@stop
