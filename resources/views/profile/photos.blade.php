@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Your instagram photos & videos</div>

                <div class="panel-body">
                    <div id="photos" class="row photos" data-type="{{ $pageType }}"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
