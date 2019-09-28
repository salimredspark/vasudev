@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-8">Manage Report</div>
                        <div class="col-sm-4">

                        </div> 
                    </div> 
                </div> 

                <div class="card-body">
                    @if(Session::has('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                    @elseif(Session::has('error'))
                    <div class="alert alert-error">
                        {{ Session::get('error') }}
                    </div>
                    @endif

                    <div class="content">
                        <div class="manage-tags">
                            <a href="#">By Tags</a> | <a href="#">By Company</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection