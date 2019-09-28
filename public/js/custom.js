$(document).ready(function() {
    $("#chk_check_all").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });       
    
} );