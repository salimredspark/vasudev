@extends('layouts.app')
@section('content')

<link rel="stylesheet" type="text/css" href="{{ asset('query_builder/query-builder.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('query_builder/bootstrap-theme.min.css') }}" />
<script type="text/javascript" src="{{ asset('query_builder/jquery.min.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('query_builder/query-builder.js') }}" defer></script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-4">Import Customers</div>
                        <div class="col-sm-8">
                            <div class="card-header-right text-right">
                                <a href="javascript://" class="btn btn-success exportall">Import Customers for {{ $company_name }} </a>
                                <a href="{{ route('customer-import') }}" class="btn btn-danger btn-back">Back</a>
                            </div>
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
                        <div class="manage-export-customers">

                            <div id="container" class="container search-template">
                                <div id="content">                                                                        
                                    <form class="form-horizontal" method="POST" action="{{ route('customer-saveimportprocess') }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="csv_data_file_id" value="{{ $csv_data_file_id }}">
                                        <input type="hidden" name="company_id" value="{{ $company_id }}">
                                        <input type="hidden" name="x_tag_name" value="{{ $x_tag_name }}">

                                        <table class="table">
                                            <tr>                                                
                                                @foreach (config('app.db_fields') as $db_field)
                                                <td>{{ $db_field }}</td>
                                                @endforeach                                                 
                                            </tr>

                                            @foreach (config('app.db_fields') as $db_field)
                                            <td>
                                                <div class="form-group">
                                                    <select name="fields[{{$db_field}}]" class="form-control">                                                    
                                                        <option value="">select</option>
                                                        @foreach ($csv_data[0] as $key => $value)
                                                        <option value="{{ $loop->index }}">{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            @endforeach
                                        </table>

                                        <button type="submit" class="btn btn-primary">Import Data</button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>    
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">     
    $(document).ready(function() {                                    

    });    
</script>
@endsection