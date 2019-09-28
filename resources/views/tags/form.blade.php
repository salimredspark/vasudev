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

                        <form action="{{ route('companies-store') }}" method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" name="id" value="{{$company->_id}}">
                        <div class="col-md-12">
                            <div class="col-md-4">Name</div>
                            <div class="col-md-4"><input type="text" name="company_name" value="{{$company->company_name}}"></div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-4">Tags</div>
                            <div class="col-md-4"><input type="text" name="tag_name" value="{{$company->tag_name}}"> </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-12"><input type="submit" name="btn_save_company" value="Save"></div>                            
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection