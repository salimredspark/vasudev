@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6">Manage Customers</div>
                        <div class="col-sm-6">
                            <div class="card-header-right text-right">
                                <a href="{{ route('customer-create') }}" class="btn btn-sm btn-success">Create New</a>
                                <a href="{{ route('customer-import') }}" class="btn btn-sm btn-success">Import Customers</a>
                                <a href="{{ route('customer-export') }}" class="btn btn-sm btn-success">Export with Query</a>
                                <a href="javascript://" class="btn btn-sm btn-danger deleteall" onclick="deleteAll()" >Delete All</a>
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
                        <div class="manage-customers">
                            <table id="data_list" class="table table-striped table-bordered" style="width:100%">
                                <thead> 
                                    <tr>                                       
                                        <th>#</th> 
                                        <!--<th width="25%">Company Name</th>-->
                                        <th width="20%">Number</th>
                                        <th width="40%">Tags</th>
                                        <th width="23%">Updated At</th>
                                        <th width="20%">Action</th>
                                    </tr>
                                </thead>                                 
                            </table> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"> 
    function deleteAll(){
        var deleteall = confirm("Are you sure you want to delete all?");
        if(deleteall){

            var chkids = '';
            $('.checkbox-delete').each(function(key, value){                

                if(this.checked){
                    chkids += $(this).val() + ",";
                }

            }); 

            //console.log("chkids: " + chkids );            
            //console.log("{{ route('companies-deleteall') }}");
            location.href = "{{ route('customer-deleteall') }}";
        }
    }

    function deleteRow(id){
        var deleteMe = confirm("Are you sure you want to delete?");
        if(deleteMe){            
            location.href = "{{ route('customer-delete', ['id' => "+ id +"]) }}";
        }
    }
        
    $(document).ready(function() {
        var dateObj = new Date();
        var filenameSufix = dateObj.getDate() + '_' + (dateObj.getMonth() + 1) + '_' + dateObj.getFullYear();
                
        $('#data_list').DataTable({
            "processing": true,
            "serverSide": true,   
            "responsive": true,
            "dom": 'Bfrtip',            
            "buttons": [                          
            {
                extend: 'csv', //'copy', 'csv', 'excel', 'pdf', 'print'
                filename: 'Expost_Customers_'+filenameSufix,
                text: 'Export',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'print', //'copy', 'csv', 'excel', 'pdf', 'print'
                text: 'Print',
                exportOptions: {
                    columns: ':visible'
                }
            },            
            'colvis'           
            ],
            "ajax": "{{ route('customer-ajax') }}"            
        });
        
        $('#data_list_filter input[type=search]').val("{{$filter_tag}}").trigger($.Event("keyup", { keyCode: 13 }));
    });
</script>
@endsection