$(document).ready(function ()
{
    $(document).on("click", ".reset-password", function () {
        var x = $(this).closest('tr').data("user_id");
        resetearPass(x);
    });
});
jQuery(document).ready(function () {
    $.fn.editable.defaults.mode = 'popup';
    $('.xedit').editable();
    $(document).on('click', '.editable-submit', function () {
        var x = $(this).closest('tr').data("user_id");
        var y = $('td .input-sm').val();
        var z = $(this).closest('td').children('span');
        var dato = $(this).closest('td').data("name");

        $.ajax({
            url: "https://www.superbackings.com/admin/GetUsuarios/?id=" + x + "&data=" + y + "&columna=" + dato,
            type: 'GET',
            dataType: 'html',
            success: function (s) {
                if (s == 'status') {
                    $(z).html(y);
                }
                if (s == 'error') {
                    alert('Error Processing your Request!');
                }
            },
            error: function (e) {
                alert('Error Processing your Request!!');
            }
        });
    });

});
function resetearPass(id) {
    $.ajax({
        url: "https://www.superbackings.com/admin/resetearPass/?id=" + id,
        type: 'GET',
        dataType: 'html',
        success: function (s) {
            alert("Contraseña Restablecida");
            console.log(s);
        },
        error: function (e) {
            alert('Error Processing your Request!!');
        }
    });
}
