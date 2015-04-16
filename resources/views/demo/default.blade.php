@extends('layouts.app')

@section('content')
    <div id="welcome">
        <div class="jumbotron">
            <div class="container">
                {{--<h1>Nayjest\Grids Demo Application</h1>--}}
                <p>
                    Nayjest\Grids is a PHP grids rendering framework with expressive, elegant syntax. We believe development
                    must be an enjoyable, creative experience. Enjoy the fresh air.
                </p>
            </div>
        </div>
        @if(!empty($text))
            <div class="container">{!! $text !!}</div>
        @endif
        <div class="container">
            <style>
                #example_grid1 td {
                    white-space: nowrap;
                }
            </style>
            <?= $grid ?>
        </div>
    </div>
    <script>
        $(function(){
            $('body').on('click', function (e) {
                $('[data-toggle="popover"]').each(function () {
                    //the 'is' for buttons that trigger popups
                    //the 'has' for icons within a button that triggers a popup
                    if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                        $(this).popover('hide');
                    }
                });
            });
        });
    </script>
@stop
