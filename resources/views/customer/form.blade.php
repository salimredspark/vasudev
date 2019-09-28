@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{($id != "" ? "Update" : "Create")}} Customer</div>

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

                        <form action="{{ route('customer-store') }}" method="post">
                            {!! csrf_field() !!}    
                            <input type="hidden" name="id" value="{{$customer->_id}}">


                            <div class="form-group">
                                <label for="company_id">Select Company</label>
                                <select class="form-control" id="company_id" name="company_id">
                                    <option>Select</option>
                                    @forelse ($companies as $company)
                                    <option value="{{$company->_id}}" @if($company->_id == $customer->company_id) selected="selected" @endif>{{$company->company_name}}</option>
                                    @empty
                                    <option>Please Create Company First</option> 
                                    @endforelse 
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="tag_name">Tags</label>                                
                                <textarea class="form-control" id="tag_name" name="tag_name" rows="3" aria-describedby="tagsHelp">{{$customer->tag_name}}</textarea>
                                <small id="tagsHelp" class="form-text text-muted">external tags like vasudev,2019,pizza (comma separate).</small>
                            </div>

                            @forelse ($columns as $column)  
                           
                            <div class="form-group">
                                <label for="{{$column}}">{{$column}}</label>
                                <input type="text" class="form-control" name="extra_fileds[{{$column}}]" id="{{$column}}" placeholder="{{$column}}" value="{{$customer->$column}}">                                
                            </div>                                                       
                            
                            @empty

                            @endforelse

                            <button type="submit" class="btn btn-primary" name="btn_save_customer">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection