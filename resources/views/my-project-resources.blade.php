@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Resources For: <b>{{ $projectName }}</b></div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <project-resource
                    :user="{{json_encode(Auth::user()->id)}}"
                    :project-id="{{json_encode($projectId)}}"></></project-resource>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
