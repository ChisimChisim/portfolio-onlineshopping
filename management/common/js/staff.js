$(function(){
    $("#staffid").on("click", function(){
        var staffid = $("#staffid").val();
        $.ajax({
            url: '../../main/staff/staff_edit.php',
            type: 'POST',
            data: {staffid: staffid},
            dataType:'json',
            success: function (data) {
                alert('OK');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert('NG');
            }
        });
    });
});