@extends('admin.layout')
@section('content')
<div class="row">
    <div class="main_header">
        Queue ({{QueueType::find($type)->name}})
        <a href="/admin/queue/clear/{{$type}}"><button class="btn pull-left module_btn"><i class="fa fa-eraser"></i> Clear Queue</button></a>
    </div>
    <div class="main_body">
        <div class="tile_row">
            <div class="col-xs-3">
                <div class="tile tile_green">
                    <div class="tile_content" id="currentNumber">
                        Loading....
                    </div>
                    <div class="tile_heading">
                        Current Number
                        <div class="tile_icon">
                            <i class="fa fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="tile tile_red">
                    <div class="tile_content" id="no_of_queues">
                        Loading....
                    </div>
                    <div class="tile_heading">
                        Queueing Tickets
                        <div class="tile_icon">
                            <i class="fa fa-ticket"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="tile tile_blue">
                    <div id="avg_time" class="tile_content">
                        Loading....
                    </div>
                    <div class="tile_heading">
                        Average waiting time
                        <div class="tile_icon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-3">
                <a href="/admin/queue_stat/{{$type}}">
                    <div class="tile tile_orange">
                        <div class="tile_content">
                            Statistics
                        </div>
                        <div class="tile_heading">
                            Get Detailed statistics
                            <div class="tile_icon">
                                <i class="fa fa-line-chart"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="module">
                <div class="module_title">
                    Queue
                </div>
                <table>
                    <thead>
                        <tr>
                            <td>Number</td>
                            <td>No. of people</td>
                            <td>Status</td>
                            <td>Time</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    @foreach ($queues as $queue)
                    <tr>
                        <td>{{$queue->queue_number}}</td>
                        <td>{{$queue->no_of_people}}</td>
                        <td><span id="entered_{{$queue->id}}">
                            @if($queue->entered == 0)
                            Waiting
                            @elseif($queue->entered == 1)
                            <b>Dequeued</b>
                            @endif
                        </span></td>
                        <td>{{$queue->created_at}}</td>
                        <td>
                            @if($queue->entered == 0)
                            <button id="{{$queue->id}}" class="btn dequeue">Dequeue</button></td>
                            @elseif($queue->entered == 1)
                            <button id="{{$queue->id}}" class="btn enter">Enter</button></td>
                            @endif
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
<script>
    var conn = new WebSocket('ws://<?=Setting::getIP()?>:<?=Setting::getPort()?>'),
    maxID = {{$maxQueueNumber}},
    type = {{$type}};
    conn.onopen = function(e) {
        console.log("Connection established!");
    };

    conn.onmessage = function(e) {
        var data = $.parseJSON( e.data );
        console.log(data);
        if(data.action === 'enqueue'){
            if(parseInt(data.type) === type){
                $('table').append("<tr><td>"+data.number+"</td><td>"+data.people+"</td><td><span id='entered_"+data.id+"'>Waiting</span></td><td>"+data.created_at.date+"</td><td><button id='"+data.id+"' class='btn dequeue'>Dequeue</button></td></tr>");
                $('#no_of_queues').html(data.totalQueue);
            }
        }else if(data.action === 'entered'){
            if(parseInt(data.type) === type){
                $('#'+data.id).attr("disabled", true);
                var targetID = "#entered_"+data.id;
                $(targetID).html('<b>Entered</b>');
                getCurrentNumber();
                getWaitingTime();
                getTotalQueue();
            }
        }else if(data.action === 'dequeue'){
            if(parseInt(data.type) === type){
                $('#'+data.id).html('Enter').removeClass('dequeue').addClass('enter');
                var targetID = "#entered_"+data.id;
                $(targetID).html('<b>Dequeued</b>');
                getCurrentNumber();
                getWaitingTime();
                getTotalQueue();
            }
        }else if(data.action === 'abandon'){
            if(parseInt(data.type) === type){
                $('#'+data.id).attr("disabled", true);
                var targetID = "#entered_"+data.id;
                $(targetID).html('<b>Abandoned</b>');
                getCurrentNumber();
                getWaitingTime();
                getTotalQueue();
            }
        }
    };
    function getWaitingTime(){
        $.get( "/queues/waitingTime/"+type, function(data) {
            $('#avg_time').html(data.time+" s");
        });
    }
    function getCurrentNumber(){
        $.get( "/queues/currentNumber/", function(data) {
            $('#currentNumber').html(data[type]);
        });
    }
    function getTotalQueue(){
        $.get( "/queues/totalQueue/"+type, function(data) {
            $('#no_of_queues').html(data);
        });
    }
    /*
    function refreshQueue(number){
    $.get( "/queues/listQueue/"+number+"/type/"+type, function( data ) {
    for(i=0;i<data.length;i++){
    temp = data[i];
    $('table').append("<tr><td>"+temp.queue_number+"</td><td>"+temp.no_of_people+"</td><td><span id='entered_"+temp.id+"'>Waiting</span></td><td>"+temp.created_at+"</td><td><button id='"+temp.id+"' class='btn'>Enter</button></td></tr>");
}
if(data.length >= 1){
maxID = data[i-1].queue_number;
}
getTotalQueue();
});
}
*/
$(document).ready(function($) {
    getCurrentNumber();
    getWaitingTime();
    getTotalQueue();
    //setInterval(function(){refreshQueue(maxID)}, 1000);
    $('body').on('click', 'button.dequeue', function(){
        var $this = $(this);
        var thisID = $(this).attr('id');
        $.ajax({
            url: "/queue/"+thisID,
            type: "PUT"
        });
    });
    $('body').on('click', 'button.enter', function(){
        var $this = $(this);
        var thisID = $(this).attr('id');
        $.ajax({
            url: "/queue/"+thisID,
            type: "PUT",
            data: { entered : 2 }
        });
    });
});

var offset = 60;
$(window).scroll(function(event){
    if($(window).scrollTop() >= offset){
        $('.main_header').addClass('header_fixed');
        $('.tile_row').addClass('tile_row_fixed');
        offset = 0;
    }else{
        $('.main_header').removeClass('header_fixed');
        $('.tile_row').removeClass('tile_row_fixed');
        offset = 60;
    }
})
</script>
@stop
