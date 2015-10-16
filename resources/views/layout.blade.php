<!doctype html>
<html lang="en">
    <head>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
        <!-- Include roboto.css to use the Roboto web font, material.css to include the theme and ripples.css to style the ripple effect -->
        <link href="{{asset('css/material-design/roboto.min.css')}}" rel="stylesheet">
        <link href="{{asset('css/material-design/material.min.css')}}" rel="stylesheet">
        <link href="{{asset('css/material-design/ripples.min.css')}}" rel="stylesheet">
        <link href="{{asset('css/main.css')}}" rel="stylesheet">
    </head>

    <body>
        <!-- Your site -->
        <div class="container">
            @yield('main')
        </div>
        <!-- Your site ends -->

        <div class="container" style="margin-top: 500px;">

            <h2>To ensure that material-design theme is working, check out the buttons below.</h2>

            <h3 class="text-muted">If you can see the ripple effect on clicking them, then you are good to go!</h3>


            <p class="bs-component">
                <a href="javascript:void(0)" class="btn btn-default">Default</a>
                <a href="javascript:void(0)" class="btn btn-primary">Primary</a>
                <a href="javascript:void(0)" class="btn btn-success">Success</a>
                <a href="javascript:void(0)" class="btn btn-info">Info</a>
                <a href="javascript:void(0)" class="btn btn-warning">Warning</a>
                <a href="javascript:void(0)" class="btn btn-danger">Danger</a>
                <a href="javascript:void(0)" class="btn btn-link">Link</a>
            </p>

            <div class="row">
                <div class="col-md-1">.col-md-1</div>
                <div class="col-md-1">.col-md-1</div>
                <div class="col-md-1">.col-md-1</div>
                <div class="col-md-1">.col-md-1</div>
                <div class="col-md-1">.col-md-1</div>
                <div class="col-md-1">.col-md-1</div>
                <div class="col-md-1">.col-md-1</div>
                <div class="col-md-1">.col-md-1</div>
                <div class="col-md-1">.col-md-1</div>
                <div class="col-md-1">.col-md-1</div>
                <div class="col-md-1">.col-md-1</div>
                <div class="col-md-1">.col-md-1</div>
            </div>
            <div class="row">
                <div class="col-md-8">.col-md-8</div>
                <div class="col-md-4">.col-md-4</div>
            </div>
            <div class="row">
                <div class="col-md-4">.col-md-4</div>
                <div class="col-md-4">.col-md-4</div>
                <div class="col-md-4">.col-md-4</div>
            </div>
            <div class="row">
                <div class="col-md-6">.col-md-6</div>
                <div class="col-md-6">.col-md-6</div>
            </div>
        </div>

        <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

        <script src="{{asset('js/material-design/ripples.min.js')}}"></script>
        <script src="{{asset('js/material-design/material.min.js')}}"></script>
        <script>
            $(document).ready(function() {
                // This command is used to initialize some elements and make them work properly
                $.material.init();
            });
        </script>
        @yield('footer_script')
    </body>

</html>
