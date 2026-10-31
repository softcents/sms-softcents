"use strict";

/** Party Start */
function addMoreFeature()
{
    let length = parseInt($(".duplicate-feature").length) + 1; // Increment length by 1
    if (length > 3) {
        toastr.error("You can not add more than 3 Reference!");
        return;
    }
     var newFeature = $(".duplicate-feature:last").clone().insertAfter("div.duplicate-feature:last");

    $('.reference:last').text('Reference - ' + length);
    $('.duplicate-feature:last .clear-input').val('');

    $('.duplicate-feature:last .clear-img').val(null); // Clear the file input
    $('.duplicate-feature:last .table-img').attr('src', ''); // Clear the image source
}

function removeFeature(button) {
    $(button).closest('.duplicate-feature').remove();
}
/** Party End */

/** SMS Start */
//edit modal
$('.edit-btn').each(function () {
    let container = $(this);
    let id = container.data('id');

    $('#edit_' + id).on('click', function () {
        $('#sms_edit_party_id').val($('#edit_' + id).data('party_id'));
        $('#sms_edit_message').val($('#edit_' + id).data('message'));

        let editactionroute = $(this).data('url');
        $('#editForm').attr('action', editactionroute + '/' + id);
    });
});

/** SMS End */

//dynamic Field Start
$(document).on('click', ".service-btn-possition", function () {
    var $duplicateRow = $(this).closest(".duplicate-feature");
    var $lastDuplicateRow = $duplicateRow.clone();
    $lastDuplicateRow.find(".remove-btn-features").removeAttr('disabled');
    $lastDuplicateRow.insertAfter($duplicateRow);
});

$(document).on('click', ".remove-btn-features", function () {
    var $row = $(this).closest(".duplicate-feature");
    $row.remove();
});

$(document).on('click', ".item-plus-button", function () {
    var $duplicateRow = $(this).closest(".duplicate-feature");
    var $lastDuplicateRow = $duplicateRow.clone();

    var uniqueId = new Date().getTime();
    $lastDuplicateRow.find('.image-preview').attr('id', 'img' + uniqueId);

    $lastDuplicateRow.find('.form-control').val('');
    $lastDuplicateRow.find('.image-preview').attr('src', $lastDuplicateRow.find('.image-preview').data('default-src'));
    $lastDuplicateRow.find(".remove-btn-features").removeAttr('disabled');

    $lastDuplicateRow.insertAfter($duplicateRow);
});
//Dynamic Filed End

//Tags Setting Start

$(document).off('click', '.add-new-tag').on('click', '.add-new-tag', function () {
    let html = `
    <div class="col-md-6">
        <div class="row row-items">
            <div class="col-sm-10">
                <label for="">Tags</label>
                <input type="text" name="tags[]" class="form-control" required placeholder="Enter tags name">
            </div>
            <div class="col-sm-2 align-self-center mt-3">
                <button type="button" class="btn text-danger trash remove-btn-features" onclick="removeDynamicField(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    `;
    $(".manual-rows .single-tags").append(html);
});

function removeDynamicField(element) {
    $(element).closest('.row.row-items').parent().remove();
}

//tag ends


