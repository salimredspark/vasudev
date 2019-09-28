@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Manage Customer Tags
                    <div class="card-header-right"></div>
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
                        @if(count($allTags) > 0)
                            @foreach ($allTags as $tag)
                            @if ($tag != "")                            
                            <a href="{{ route('customer-filter',['tag' => $tag]) }}" class="badge badge-pill badge-{{$tagClass[array_rand($tagClass)]}}">#{{$tag}}</a>                            
                            @endif
                            @endforeach                             
                            @else
                            No Tags                            
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection