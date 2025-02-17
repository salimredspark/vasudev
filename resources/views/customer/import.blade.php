@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Import Customer CSV</div>

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

                        <form action="{{ route('customer-import-process') }}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
                            {!! csrf_field() !!}

                            <div class="form-group">
                                <div class="download-sample"> 
                                    <a href="{{ route('download-sample',['filename' => 'customers_sample_csv_format.csv']) }}">Donwload Sample</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="company_id">Select Company</label>
                                <select class="form-control" id="company_id" name="company_id">
                                    <option value="">Select</option>
                                    @forelse ($companies as $company)
                                    <option value="{{$company->_id}}">{{$company->company_name}}</option>
                                    @empty
                                    <option>Please Create Company First</option> 
                                    @endforelse 
                                </select>
                            </div>

                            <div class="form-group{{ $errors->has('upload_file') ? ' has-error' : '' }}">
                                <label for="upload_file">Select File</label>
                                <input type="file" class="" id="upload_file" name="upload_file">
                                
                                @if ($errors->has('upload_file'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('upload_file') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Tags</label>                                
                                <textarea class="form-control" id="tag_name" name="tag_name" rows="3" aria-describedby="tagsHelp"></textarea>
                                <small id="tagsHelp" class="form-text text-muted">external tags like vasudev,2019,pizza (comma separate).</small>
                            </div>

                            <div class="form-group" style="display: none;">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="header"> File contains header row?
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary" name="btn_save_upload">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection