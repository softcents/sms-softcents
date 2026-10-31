"use strict";

const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
$.ajaxSetup({ headers: { "X-CSRF-TOKEN": CSRF_TOKEN } });
let $savingLoader = '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>',

    $ajaxform = $(".ajaxform");
$ajaxform.initFormValidation(),
    $(document).on("submit", ".ajaxform", function (e) {
        e.preventDefault();
        let t = $(this).find(".submit-btn"),
            a = t.html();
        $ajaxform.valid() &&
        $.ajax({
            type: "POST",
            url: this.action,
            data: new FormData(this),
            dataType: "json",
            contentType: !1,
            cache: !1,
            processData: !1,
            beforeSend: function () {
                t.html($savingLoader).attr("disabled", !0);
            },
            success: function (e) {
                t.html(a).attr("disabled", false);
                Notify("success", null, e)
            },
            error: function (e) {
                t.html(a).attr("disabled", !1), Notify("error", e);
            },
        });
    });

    let $ajaxform_instant_reload = $(".ajaxform_instant_reload");
    $ajaxform_instant_reload.initFormValidation(),
    $(document).on("submit", ".ajaxform_instant_reload", function (e) {
        e.preventDefault();
        let t = $(this).find(".submit-btn"),
            a = t.html();
        $ajaxform_instant_reload.valid() &&
        $.ajax({
            type: this.method,
            url: this.action,
            data: new FormData(this),
            dataType: "json",
            contentType: !1,
            cache: !1,
            processData: !1,
            beforeSend: function () {
                t.html($savingLoader).addClass("disabled").attr("disabled", !0);
            },
            success: function (e) {
                t.html(a).removeClass("disabled").attr("disabled", !1), (window.sessionStorage.hasPreviousMessage = !0), (window.sessionStorage.previousMessage = e.message ?? null), e.redirect && (location.href = e.redirect);
            },
            error: function (e) {
                t.html(a).removeClass("disabled").attr("disabled", !1), showInputErrors(e.responseJSON), Notify("error", e);
            },
        });
    });

let $ajaxform_reset_form = $(".ajaxform_reset_form");
$ajaxform_reset_form.initFormValidation(),
    $(document).on("submit", ".ajaxform_reset_form", function (e) {
        e.preventDefault();
        let t = $(this),
            a = t.find(".submit-button"),
            s = a.html();
        $ajaxform_reset_form.valid() &&
        $.ajax({
            type: "POST",
            url: this.action,
            data: new FormData(this),
            dataType: "json",
            contentType: !1,
            cache: !1,
            processData: !1,
            beforeSend: function () {
                a.html($savingLoader).attr("disabled", !0);
            },
            success: function (e) {
                a.html(s).attr("disabled", !1), t.trigger("reset"),  Notify("success", null, e);
            },
            error: function (e) {
                a.html(s).attr("disabled", !1), showInputErrors(e.responseJSON), Notify("error", e);
            },
        });
    }),

    $(document).ready(function() {
        let $re_generate_key = $(".re-generate-key");
        $re_generate_key.initFormValidation();

        $(document).on("submit", ".re-generate-key", function (e) {
            e.preventDefault();
            let t = $(this).find(".submit-btn"),
                a = t.html();
            $re_generate_key.valid() &&
            $.ajax({
                type: "POST",
                url: this.action,
                data: new FormData(this),
                dataType: "json",
                contentType: !1,
                cache: !1,
                processData: !1,
                beforeSend: function () {
                    t.html($savingLoader).attr("disabled", !0);
                },
                success: function (res) {
                    t.html(a).attr("disabled", false);
                    $('.client_secret').val(res.secret);
                    Notify("success", null, res.message);
                },
                error: function (e) {
                    t.html(a).attr("disabled", !1), Notify("error", e);
                },
            });
        });
    });



function ajaxSuccess(e, t) {
    e.redirect ? (e.message && ((window.sessionStorage.hasPreviousMessage = !0), (window.sessionStorage.previousMessage = e.message ?? null)), (location.href = e.redirect)) : e.message && Notify("success", e);
}
function clean(e) {
    return (e = (e = e.replace(/ /g, "-")).replace(/[^A-Za-z0-9\-]/, "")).toLowerCase();
}

//PREVIEW IMAGE
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            var inputId = $(input).attr('id');

            // Select the image element based on the input's ID
            var imageElement = $('img.product-img').filter(function() {
                return $(this).closest('label').attr('for') === inputId;
            });
            imageElement.attr('src', e.target.result);
            imageElement.hide().fadeIn(650);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

// Status button Change
$(".change-text").on("change", function() {
    var $dynamicText = $(this).closest('.form-control').find('.dynamic-text');

    if (this.checked) {
        $dynamicText.text("Active");
    } else {
        $dynamicText.text("Deactive");
    }
});

// Status button Change
$(".cnge-text").on("change", function() {
    var $test = $(this).closest('.form-control').find('.is-live-text');

    if (this.checked) {
        $test.text("Yes");
    } else {
        $test.text("No");
    }
});

/** STATUS CHANGE */
$('.status').on('change', function() {
    var checkbox = $(this);
    var status = checkbox.prop('checked') ? 1 : 0;
    var url = checkbox.data('url');
    var method = checkbox.data('method');

    $.ajax({
        url: url,
        type: method ?? 'POST',
        data: {
            status: status
        },
        success: function(response) {
            if(status === 1){
                toastr.success(response.message + ' status published');
            }
            else{
                toastr.success(response.message + ' status unpublished');
            }
        },
        error: function(xhr, status, error) {
            console.log(error)
            toastr.error('Something Went Wrong');
        }
    });
});

/** DELETE ACTION */
$(document).on("click", ".delete-confirm", function (e) {
    e.preventDefault();
    let t = $(this),
        o = t.attr("href") ?? t.data("action"),
        i = t.html();

    // Create modal dynamically
    let confirmationModal = `
        <div class="modal fade" id="delete-confirmation-modal" tabindex="-1" aria-labelledby="delete-confirmation-modal-label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="text-end">
                        <button type="button" class="btn-close m-3 mb-0" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="delete-modal">
                            <h5>Are You Sure?</h5>
                            <p>You won't be able to revert this!</p>
                        </div>
                        <div class="button-group">
                            <button class="btn reset-btn" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn theme-btn delete-confirmation-button">Yes, Delete It!</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    $('body').append(confirmationModal); // Append modal to the body
    $('#delete-confirmation-modal').modal('show');

    // handle dynamic modal
    $('.delete-confirmation-button').on('click', function () {
        $.ajax({
            url: o,
            data: { _token: CSRF_TOKEN },
            type: "DELETE",
            beforeSend: function () {
                t.html($savingLoader).addClass("disabled").attr("disabled", true);
            },
            success: function (e) {
                t.html(i).removeClass("disabled").attr("disabled", false);
                ajaxSuccess(e, t);
            },
            error: function (e) {
                t.html(i).removeClass("disabled").attr("disabled", false);
                Notify(e);
            },
        });

        // Hide and remove modal
        $('#delete-confirmation-modal').modal('hide');
        $('#delete-confirmation-modal').remove();
    });
});

/** CHECKBOX FOR DELETE ALL */
$(document).ready(function () {
    // Select all checkboxes when the checkbox in the header is clicked
    $(".selectAllCheckbox").on("click", function () {
        $(".checkbox-item").prop("checked", this.checked);
        if (this.checked) {
            $('.delete-selected').addClass('text-danger');
        } else {
            $('.delete-selected').removeClass('text-danger');
        }
    });


    // Perform the delete action for selected elements when the delete icon is clicked
    $(".delete-selected").on("click", function (e) {
        var checkedCheckboxes = $(".checkbox-item:checked");
        if (checkedCheckboxes.length === 0) {
            toastr.error('No items selected. Please select at least one item to delete.');
        } else {
            $('#multi-delete-modal').modal('show');
        }
    });


    $('.multi-delete-btn').on('click', function() {
        var ids = $(".checkbox-item:checked").map(function() {
            return $(this).val();
        }).get();

        let submitButton = $(this);
        let originalButtonText = submitButton.html();
        let del_url = $('.checkbox-item').data('url');

        $.ajax({
            type: "POST",
            url: del_url,
            data: {
                ids
            },
            dataType: "json",
            beforeSend: function () {
                submitButton.html($savingLoader).attr('disabled', true);
            },
            success: function (res) {
                submitButton.html(originalButtonText).attr('disabled', false);
                window.sessionStorage.hasPreviousMessage = true;
                window.sessionStorage.previousMessage = res.message ?? null;
                res.redirect && (location.href = res.redirect);
            },
            error: function (xhr) {
                submitButton.html(originalButtonText).attr('disabled', false);
                Notify('error', xhr);
            }
        });
    });
});



/** system setting start */
    // Initial label text
var initialLabelText = $("#mail-driver-type-select option:selected").val();

$("#mail-driver-type-select").on("change", function () {
    var selectedOptionValue = $(this).val();
    $("#mail-driver-label").text(selectedOptionValue);
});

$("#mail-driver-label").text(initialLabelText);

/** system setting end */
