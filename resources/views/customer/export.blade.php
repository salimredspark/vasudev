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
                                    <div id="builder"></div>
                                    <form role="form" method="post" id="query-builder-form" action="{{ route('customer-exportall') }}">
                                        {!! csrf_field() !!}
                                        <div class="btn-group">
                                            <input type="button" class="btn btn-warning reset" value="Reset" />
                                            <input type="button" class="btn btn-primary parse-sql" value="Run" />                                            
                                        </div>

                                        <div id="totalFound" style="display: none;"></div>

                                        <div class="section-tools" style="display: none;">
                                            <div class="extras">
                                                <textarea name="querybuilder" id="json-parsed" class="json-parsed" cols="100" rows="5" style="display: none;"></textarea>                                                
                                            </div>

                                            <div class="section-limit">
                                                <div class="row">
                                                    <div class="col-sm-6"> 
                                                        <div class="form-group">                                                             
                                                            <input type="text" value="" class="form-control" placeholder="Set Limit" name="setLimit" id="setLimit"  /> 
                                                            <small id="limitHelp" class="form-text text-muted">set export records limit.</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6"> 
                                                        <div class="form-group">                                                            
                                                            <select class="form-control" name="setLimitType" id="setLimitType">
                                                                <option value="all" selected="selected">All</option>
                                                                <option value="top">Top</option>
                                                                <option value="random">Random</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="section-tool">
                                                <h5>Select Columns | <input type="checkbox" checked="checked" name="selectAll" id="selectAll"> Select All</h5>
                                                <div class="row">
                                                    @forelse ($columns as $k => $column)
                                                    <div class="col-sm-3"><input type="checkbox" class="checkBoxClass" checked="checked" name="fields[]" value="{{$k}}"> {{ $column }}</div>
                                                    @empty
                                                    <p>No Columns</p>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
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

        //ref jquery: https://querybuilder.js.org/
        //ref: laravel : http://jsfiddle.net/graphitekom/ap9gxo4L/  

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $('#builder').queryBuilder({
            sortable: true,         
            filters: [
            /*{
            id: 'core_ID',
            type: 'integer',
            operators: ['equal', 'not_equal', 'in', 'not_in']
            },*/ {
                id: 'tag_name',
                label: 'Tags',
                type: 'string',
                //operators: ['equal', 'not_equal', 'in', 'not_in', 'contains']
                operators: ['contains', 'not_contains'] //,'begins_with','not_begins_with','ends_with','not_ends_with'
            }]
        });

        // set rules
        /*$('#builder').queryBuilder('setRules', {
        "condition": "AND",
        "rules": [
        {
        "id": "core_ID",
        "field": "core_ID",
        "type": "integer",
        "input": "text",
        "operator": "in",
        "value": "1240"
        }
        ]
        });*/

        // reset builder
        $('.reset').on('click', function () {
            $('#builder').queryBuilder('reset');
            $(".json-parsed").empty();
            //$(".sql-parsed").empty(); 
        });

        // get rules & SQL
        $('.parse-sql').on('click', function () {

            // JSON
            var resJson = $('#builder').queryBuilder('getRules');
            $(".json-parsed").html(JSON.stringify(resJson, null, 2));

            // SQL
            //var resSql = $('#builder').queryBuilder('getSQL', false);
            //$(".sql-parsed").html(resSql.sql);

            //calling ajax request            
            exporturl = "{{ route('customer-exportcounts') }}";

            $("#totalFound").show();
            $("#totalFound").html("Please wait...");

            $.ajax({
                type: 'POST',
                cache: false,
                dataType: 'json',
                url: exporturl,
                data: { 
                    querybuilder: resJson,                    
                    _token: CSRF_TOKEN
                },
                success:function(data){
                    if(data.status == 'success'){
                        $("#totalFound").html("Total Records: <span class='cnt'>"+data.counts+"</span>");
                        $("#setLimit").val(data.counts);
                        $(".section-tools").show(); // show other option after query run
                    }else{
                        //alert('Ajax: Something went wrong!');
                        $("#totalFound").html("");
                        $("#totalFound").hide();
                    }
                }, 
                error: function(xhr) {
                    alert('Error: Something went wrong!');  
                    $("#totalFound").html("");
                    $("#totalFound").hide();
                }
            });

        });

        // result
        //$( ".parse-sql" ).trigger( "click" );

        //export in csv
        $(".exportall").click(function(){            

            if(!$('.section-tools').is(":hidden")){
                $("#query-builder-form").submit();  
            }else{
                alert("Please select or run first query");
            }          
        });

        $("#selectAll").click(function () {
            $(".checkBoxClass").prop('checked', $(this).prop('checked'));
        });
    });    
</script>
@endsection