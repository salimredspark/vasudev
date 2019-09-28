@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-8">Manage Companies Export</div>
                        <div class="col-sm-4">
                            <div class="card-header-right text-right">
                                <a href="javascript://" class="btn btn-sm btn-success exportall" onclick="exportselected()" >Export All</a>
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
                            <table id="data_list" class="table table-striped table-bordered" style="width:100%">
                                <thead> 
                                    <tr>
                                        <th># <input type="checkbox" class="checkbox" name="chk_check_all" id="chk_check_all"></th> 
                                        <th>Field Name</th> 
                                    </tr>
                                </thead> 
                                <tbody>                                
                                    @forelse ($columns as $l)
                                    <tr> 
                                        <td><input type="checkbox" class="checkbox checkbox-export" value="{{$l}}" name="chk_delete_mass[]"></td> 
                                        <td>{{ $l }}</td>
                                    </tr>
                                    @empty
                                    <tr> 
                                        <td colspan="5"><div class="no-rows">No Records!</div></td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table> 
                        </div>
                    </div> 
                </div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript"> 
    function exportselected(){
        var exportall = confirm("Are you sure you want to proceed?");
        if(exportall){
            var chkids = '';
            $('.checkbox-export').each(function(key, value){
                if(this.checked){
                    chkids += $(this).val() + ",";
                }
            }); 
            if( 0 == chkids.length  ) { alert( "Please select atleast one field!" ); return false; }
            exporturl = "{{ route('companies-exportall') }}";
            location.href = exporturl+"?fields="+chkids;
        }
    }

    $(document).ready(function() {                                    
        $('#data_list').DataTable({
            "processing": true,   
        });
    });
</script>
@endsection