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
                        <div class="col-sm-8">Export Customers</div>
                        <div class="col-sm-4">
                            <div class="card-header-right text-right">
                                <a href="javascript://" class="btn btn-success exportall">Export</a>
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
                                    <form class="form-horizontal" method="POST" action="{{ route('customer-saveImport') }}">
                                        {{ csrf_field() }}

                                        <table class="table">
                                            @foreach ($csv_data as $row)
                                            <tr>
                                                @foreach ($row as $key => $value)
                                                <td>{{ $value }}</td>
                                                @endforeach
                                            </tr>
                                            @endforeach
                                            
                                        </table>

                                        <button type="submit" class="btn btn-primary">
                                            Import Data
                                        </button>
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