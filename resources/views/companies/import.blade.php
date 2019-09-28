@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Import Companies CSV</div>

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

                        <form action="{{ route('companies-saveImport') }}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
                            {!! csrf_field() !!}

                            <div class="form-group">
                                <div class="download-sample"> 
                                    <a href="{{ route('download-sample',['filename' => 'companies_sample_csv_format.csv']) }}">Donwload Sample</a> 
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="upload_file">Select File</label>
                                <input type="file" class="" id="upload_file" name="upload_file">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Tags</label>                                
                                <textarea class="form-control" id="tag_name" name="tag_name" rows="3" aria-describedby="tagsHelp"></textarea>
                                <small id="tagsHelp" class="form-text text-muted">external tags like vasudev,2019,pizza (comma separate).</small>
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