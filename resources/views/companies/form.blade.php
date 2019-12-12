@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{($id != "" ? "Update" : "Create")}} Company</div>

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

                        <form action="{{ route('submit-all') }}" method="post">
                            {!! csrf_field() !!}    
                            <input type="hidden" name="id" value="{{$company->_id}}">


                            <div class="form-group">
                                <label for="company_name">Name</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name" value="{{$company->company_name}}">                                
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Tags</label>                                
                                <textarea class="form-control" id="tag_name" name="tag_name" rows="3" aria-describedby="tagsHelp">{{$company->tag_name}}</textarea>
                                <small id="tagsHelp" class="form-text text-muted">external tags like vasudev,2019,pizza (comma separate).</small>
                            </div>

                            @forelse ($columns as $column)  
                           
                            <div class="form-group">
                                <label for="{{$column}}">{{$column}}</label>
                                <input type="text" class="form-control" name="extra_fileds[{{$column}}]" id="{{$column}}" placeholder="{{$column}}" value="{{$company->$column}}">                                
                            </div>
                            
                                                        
                            
                            @empty

                            @endforelse

                            <button type="submit" class="btn btn-primary" name="btn_save_company">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection