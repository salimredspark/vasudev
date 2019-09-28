@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-8">Manage Companies</div>
                        <div class="col-sm-4">
                            <div class="card-header-right text-right">
                                <a href="{{ route('companies-create') }}" class="btn btn-sm btn-success">Create New</a>
                                <a href="{{ route('companies-import') }}" class="btn btn-sm btn-success">Import</a>                                
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
                        <div class="manage-companies">
                            <table id="data_list" class="table table-striped table-bordered" style="width:100%">
                                <thead> 
                                    <tr>                                       
                                        <th width="5%">#</th> 
                                        <th width="25%">Name</th>
                                        <th width="30%">Tags</th>
                                        <th width="20%">Updated At</th>
                                        <th width="18%">Action</th>
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
            location.href = "{{ route('companies-deleteall') }}";
        }
    }

    function deleteRow(id){
        var deleteMe = confirm("Are you sure you want to delete?");
        if(deleteMe){            
            location.href = "{{ route('companies-delete', ['id' => "+ id +"]) }}";
        }
    }


    $(document).ready(function() {
        var dateObj = new Date();
        var filenameSufix = dateObj.getDate() + '_' + (dateObj.getMonth() + 1) + '_' + dateObj.getFullYear();
        
        $('#data_list').DataTable({
            "processing": true,
            "serverSide": true,   
            dom: 'Bfrtip',
            "buttons": [                          
            {
                extend: 'csv', //'copy', 'csv', 'excel', 'pdf', 'print'
                filename: 'Expost_Companies_'+filenameSufix, 
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
            "ajax": "{{ route('companies-ajax') }}"            
        });
        
        $('#data_list_filter input[type=search]').val("{{$filter_tag}}").trigger($.Event("keyup", { keyCode: 13 }));
    });
</script>
@endsection