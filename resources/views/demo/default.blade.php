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
@stop
