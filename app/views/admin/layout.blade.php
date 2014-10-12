<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>iBeacon restaurant admin panel</title>
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="/assets/css/style.css">
        <!--jQuery 1.11.0-->
        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    </head>
    <body>
        <nav class="sidebar">
            <ul>
                <li><a href="/admin"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li><a href="/admin/queue"><i class="fa fa-users"></i>Queue</a></li>
                <li><a href="/admin/category"><i class="fa fa-users"></i>Category</a></li>
                <li><a href="/admin/item"><i class="fa fa-users"></i>item</a></li>
            </ul>
        </nav>
        <div class="main">
            @yield('content')
        </div>
    </body>
    @yield('script')
</html>
