/** confirm modal start */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('click', '.confirm-action', function (event) {
    event.preventDefault();
    let url = $(this).data('action') ?? $(this).attr('href');
    let method = $(this).data('method') ?? 'POST';
    let title = $(this).data('title') ?? "Are you sure?";
    let icon = $(this).data('icon') ?? 'fas fa-warning';

    $.confirm({
        title: title,
        icon: icon,
        theme: 'modern',
        closeIcon: true,
        animation: 'scale',
        type: 'red',
        scrollToPreviousElement: false,
        scrollToPreviousElementAnimate: false,
        buttons: {
            confirm: {
                btnClass: 'btn-red',
                action: function () {
                    event.preventDefault();
                    $.ajax({
                        type: method,
                        url: url,
                        success: function (response) {
                            if (response.redirect) {
                                window.sessionStorage.hasPreviousMessage = true;
                                window.sessionStorage.previousMessage = response.message ?? null;

                                location.href = response.redirect;
                            } else {
                                Notify('success', response)
                            }
                        },
                        error: function (xhr, status, error) {
                            Notify('error', xhr)
                        }
                    })
                }
            },
            close: {
                action: function () {
                    this.buttons.close.hide()
                }
            }
        },
    });
});
/**confirm modal end */

/** filter all from start */

$(".searchInput").on("input", function (e) {
    const searchText = $(".searchInput").val();
    const url = $(this).attr("action");
    $.ajax({
        url: url,
        type: "GET",
        data: {search: searchText},
        success: function (response) {
            console.log(response)
            $(".searchResults").html(response.data);
        }
    });
});


/** filter all from  end */

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
                    <div class="modal-body">
                        <div class="delete-modal text-center mb-lg-4">
                            <h5>Are You Sure?</h5>
                            <p>You won't be able to revert this!</p>
                        </div>
                         <div class="d-flex justify-content-center">
                            <div class="button-group">
                                <button class="btn reset-btn border rounded" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn theme-btn border rounded delete-confirmation-button">Yes, Delete It!</button>
                            </div>
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
                t.html($savingLoader).attr('disabled', true);
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

$(document).on('change', '.file-input-change', function () {
    let prevId = $(this).data('id');
    newPreviewImage(this, prevId);
});

// image preview
function newPreviewImage(input, prevId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#' + prevId).attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}


$(document).ready(function() {

    $('.timer-option').on('click', function() {
        if ($(this).val() == 'schedule') {
            $('.schedule-sms').removeClass('d-none');
        } else {
            $('.schedule-sms').addClass('d-none');
        }
    });

    $('.edit-template').on('click', function() {
        let url = $(this).data('url');
        let name = $(this).data('name');
        let text = $(this).data('text');
        let status = $(this).data('status');

        $('.text').val(text);
        $('.name').val(name);
        $('.status-option').val(status);
        $('#edit-template-modal').modal('show');
        $('.template-edit-form').attr('action', url);
    });

    $('.user-recharge').on('click', function() {
        let user_id = $(this).data('user_id');
        $('.user_id').val(user_id);
    });

    $('.approve-reject').on('click', function() {
        let url = $(this).data('url');
        $('.approve-reject-form').attr('action', url)
        $('#approve-reject-modal').modal('show');
    });

    $('.select-campaign').on('change', function() {
        var total_numbers = $(this).find("option:selected").data("total_numbers");
        $('.total-range').val(total_numbers);
    });

    $('.copyLink').on('click', function() {
        navigator.clipboard.writeText($(this).data('text'))
        .then(() => {
                Notify('success', null, 'Link copied to clipboard!');
            }
        );
    });

    $('.password').on("click", function () {
        let input = $('.password-input .pass-input');
        if (input.attr('type') === "password") {
            input.attr('type', 'text');
            $(".password .hide-pass-img").addClass('d-none');
            $(".password .show-pass-img").removeClass('d-none');
        } else {
            input.attr('type', 'password');
            $(".password .hide-pass-img").removeClass('d-none');
            $(".password .show-pass-img").addClass('d-none');
        }
    });

    $('.confirm-password').on("click", function () {
        let input = $('.password-input .conf-pass-input');
        if (input.attr('type') === "password") {
            input.attr('type', 'text');
            $(".confirm-password .hide-pass-img").addClass('d-none');
            $(".confirm-password .show-pass-img").removeClass('d-none');
        } else {
            input.attr('type', 'password');
            $(".confirm-password .hide-pass-img").removeClass('d-none');
            $(".confirm-password .show-pass-img").addClass('d-none');
        }
    });


    // View numbers
    $('.view-numbers').on('click', function() {
        $('#view-numbers').modal('show');
        let numbers = $(this).data('numbers').split(',');
        let numbersData = [];

        $.each(numbers, function(index, number) {
            numbersData.push(`
                <tr class="my-2">
                    <td>${index+1}. ${number}</td>
                </tr>
            `);
        });

        $('.numbers-lists').html(numbersData);
    });

    $('.logoutButton').on('click', function() {
        document.getElementById('logoutForm').submit();
    });
})

function printPage() {
    window.print();
}

$(document).on('change', '.file-input-change', function () {
    let prevId = $(this).data('id');
    console.log(prevId)
    newPreviewImage(this, prevId);
});

// image preview
function newPreviewImage(input, prevId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#' + prevId).attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

$('.gateway-type').on('change', function() {

    var names = $(this).find('option:selected').data('names');
    var labels = $(this).find('option:selected').data('labels');

    let inputHtml = '';

    $.each(names, function(key, name) {
        var label = labels[key] || '';

        inputHtml += `
            <div class="col-sm-6 mb-2">
                <label>${label}</label>
                <input type="text" name="params[${name}]" class="form-control" placeholder="${label}">
            </div>
        `;
    });

    $('#input-container').html(inputHtml);
});
