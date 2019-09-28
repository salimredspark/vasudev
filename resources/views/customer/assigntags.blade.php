@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Assign Tags to Customers and Companies</div>

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

                        <form action="{{ route('customer-save-assign-tags') }}" method="post">
                            {!! csrf_field() !!}    
                            <div class="form-group">
                                <label for="company_id">Select Companies</label>
                                <select class="form-control js-example-basic-multiple" id="company_id" name="company_id[]" aria-describedby="compHelp" multiple="multiple">
                                    @forelse ($companies as $company)
                                    <option value="{{$company->_id}}">{{$company->company_name}}</option>
                                    @empty
                                    <option>Please Create Company First</option> 
                                    @endforelse 
                                </select>                                
                                <small id="compHelp" class="form-text text-muted">Search your company.</small>
                            </div>

                            <div class="form-group">
                                <label for="tag_name">Tags</label>                                
                                <textarea class="form-control" id="tag_name" name="tag_name" rows="3" aria-describedby="tagsHelp"></textarea>
                                <small id="tagsHelp" class="form-text text-muted">This tag auto assign to selected companies and those companies customers. Tags example vasudev,2019,pizza (comma separate).</small>
                            </div>

                            <button type="submit" class="btn btn-primary" name="btn_save_customer">Save</button>
                        </form> 
                        <script type="text/javascript">                         
                            $(document).ready(function() {
                                $('.js-example-basic-multiple').select2();
                            });  
                        </script> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection