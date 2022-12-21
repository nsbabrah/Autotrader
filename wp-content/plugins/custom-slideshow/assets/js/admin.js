var blockTarget = document.querySelector("body");

var blockUI = new KTBlockUI(blockTarget, {
    message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Processing...</div>',
    overlayClass: "bg-info bg-opacity-25"
});

jQuery(document).ready(function ($) {
    console.log('admin javascript loaded');
    console.log(cs_objects.ajax_url);

    $('body').on('click', '#portfoliosFTrigger', function (ev) {
        ev.preventDefault();
        $('#chosen_files').trigger('click');
    });

    $('body').on('change', '#chosen_files', function (ev) {
       $('#uploadForm').trigger('submit');
    });

    $('body').on('submit', '#uploadForm', function (ev) {
       ev.preventDefault();

       var formData = new FormData($(this)[0]);
       blockUI.block();
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: cs_objects.ajax_url,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            error: function error(response) {
                blockUI.release();
                Swal.fire({
                    text: "Internal Server Error!",
                    icon: "error",
                    buttonsStyling: !1,
                    confirmButtonText: "OK",
                    customClass: {confirmButton: "btn btn-primary"}
                });
            },
            success: function (result) {
                console.log(result);
                blockUI.release();
                if (!result.status) {
                    Swal.fire({
                        text: "Something went wrong!",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "OK",
                        customClass: {confirmButton: "btn btn-primary"}
                    });
                } else {
                    window.location.reload();
                }
            }
        });

       return false;
    });

    $('body').on('submit', '#slidesOrderForm', function (ev) {
        ev.preventDefault();

        var formData = new FormData($(this)[0]);
        blockUI.block();
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: cs_objects.ajax_url,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            error: function error(response) {
                blockUI.release();
                Swal.fire({
                    text: "Internal Server Error!",
                    icon: "error",
                    buttonsStyling: !1,
                    confirmButtonText: "OK",
                    customClass: {confirmButton: "btn btn-primary"}
                });
            },
            success: function (result) {
                console.log(result);
                blockUI.release();
                if (!result.status) {
                    Swal.fire({
                        text: "Something went wrong!",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "OK",
                        customClass: {confirmButton: "btn btn-primary"}
                    });
                }
            }
        });

        return false;
    });

    $('body').on('click', '.remove-slide', function (ev) {
        ev.preventDefault();
        var row = $(this).closest('tr');

        var formData = new FormData();
        formData.set('action', 'cs_remove_slide');
        formData.set('slide_id', $(this).attr('data-id'));
        blockUI.block();
        $.ajax({
            method: 'POST',
            dataType: 'json',
            url: cs_objects.ajax_url,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            error: function error(response) {
                blockUI.release();
                Swal.fire({
                    text: "Internal Server Error!",
                    icon: "error",
                    buttonsStyling: !1,
                    confirmButtonText: "OK",
                    customClass: {confirmButton: "btn btn-primary"}
                });
            },
            success: function (result) {
                console.log(result);
                blockUI.release();
                if (!result.status) {
                    Swal.fire({
                        text: "Something went wrong!",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "OK",
                        customClass: {confirmButton: "btn btn-primary"}
                    });
                }
                else {
                    row.remove();
                }
            }
        });

        return false;
    });
});

var row;

function start() {
    row = event.target.parentNode;
}
function dragover() {
    var e = event;
    e.preventDefault();

    let children = Array.from(e.target.parentNode.parentNode.children);

    if (row.tagName == 'TR') {
        if (children.indexOf(e.target.parentNode) > children.indexOf(row))
            e.target.parentNode.after(row);
        else
            e.target.parentNode.before(row);
    }

}
function end() {
    if (row.tagName == 'TR') {
        var e = event;
        e.preventDefault();
        let children = Array.from(e.target.parentNode.parentNode.children);
        for (var i in children) {
            let child = children[i];
            let index = parseInt(i);
            jQuery(child).find('td:eq(0)').html(index);
        }
    }
}