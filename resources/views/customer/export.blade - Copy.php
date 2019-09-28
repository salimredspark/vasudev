@extends('layouts.app')
@section('content')
<script type="text/javascript"> var jsTags = "{{ $jsTags }}"; </script>
<link href="{{ asset('query_builder/dark.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('query_builder/RedQueryBuilder.nocache.js') }}" type="text/javascript"></script>
<script src="{{ asset('query_builder/RedQueryBuilderFactory.nocache.js') }}" type="text/javascript"></script>
<script src="{{ asset('query_builder/simple.js') }}" type="text/javascript"></script>

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
                            <div id="rqb"> </div>
                            <a href="javascript://" class="btn btn-success exportrun">Check Data</a><br /><br />                            
                            <textarea id="sql_query" name="sql_query" cols="80" rows="10" ></textarea>                            
                        </div>
                    </div>
                </div>    
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">     
    $(document).ready(function() {                                    
        $('#data_list').DataTable({
            "processing": true,   
        });

        //auto select dropdown
        $('.gwt-ListBox').change(function(){
            $(this).hide();             
        });

        $('.gwt-ListBox').val('Customers').trigger('change');        

        $(".exportall").click(function(){
            var query = $("#sql_query").val();
            exporturl = "{{ route('customer-exportall') }}";
            location.href = exporturl+"?query="+query+'&method=export';
        });

        $(".exportrun").click(function(){
            var query = $("#sql_query").val();
            exporturl = "{{ route('customer-exportall') }}";
                                      
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            
            $.ajax({
                type: 'POST',
                cache: false,
                dataType: 'json',
                url: exporturl,
                data: { 
                    query: query,
                    method: 'run',
                    _token: CSRF_TOKEN
                },
                success:function(data){
                    if(data.status == 'success'){
                        
                    }else{
                        alert('Ajax: Something went wrong!');      
                    }
                }, 
                error: function(xhr) {
                    alert('Error: Something went wrong!');  
                }
            });

        });
    });
</script>
@endsection