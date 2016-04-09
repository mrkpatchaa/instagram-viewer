@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            @if (session('warning'))
                <div class="alert alert-warning">
                    {{ session('warning') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <p>
                        Instagram status :
                        @if ($user->insta_active)
                        <span class="label label-success">Connected</span>
                        @else
                        <span class="label label-danger">Disconnected</span>
                        @endif
                    </p>
                    <p>
                        <a href="https://api.instagram.com/oauth/authorize/?client_id={{config('site.client_id')}}&redirect_uri={{config('site.redirect_uri')}}&scope=basic+public_content&response_type=code" class="btn btn-primary {{ $user->insta_active ?  'disabled' : '' }}">Connect your instagram account</a>
                        <form class="" action="{{ route('instagram') }}" method="post">
                            {{ method_field('DELETE') }}
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="btn btn-danger" {{ !$user->insta_active ?  'disabled="disabled"' : '' }}>Disconnect your instagram account</button>
                        </form>

                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
