"use strict";

$(document).on('click', ".add-new-item", function () {
    let html = `
    <div class="row row-items">
        <div class="col-sm-5">
            <label for="">Label</label>
            <input type="text" name="manual_data[label][]" value="" class="form-control" placeholder="Enter label name">
        </div>
        <div class="col-sm-5">
            <label for="">Select Required/Optionl</label>
            <select class="form-control" required name="manual_data[is_required][]">
                <option value="1">Required</option>
                <option value="0">Optional</option>
            </select>
        </div>
        <div class="col-sm-2 align-self-center mt-3">
            <button type="button" class="btn text-danger trash remove-btn-features"><i class="fas fa-trash"></i></button>
        </div>
    </div>
    `
    $(".manual-rows").append(html);
});


$(document).on('click', ".remove-btn-features", function () {
    var $row = $(this).closest(".row-items");
    $row.remove();
});

$(document).on('click', ".add-new-input", function () {
    let html = `
    <div class="row row-items">
        <div class="col-sm-5">
        <label for="">Label</label>
        <input type="text" name="labels[]" class="form-control" required placeholder="Enter label name">
    </div>
    <div class="col-sm-5">
        <label for="">Input Name</label>
        <input type="text" name="inputs[]" class="form-control" required placeholder="Enter input name">
    </div>
        <div class="col-sm-2 align-self-center mt-3">
            <button type="button" class="btn text-danger trash remove-input"><i class="fas fa-trash"></i></button>
        </div>
    </div>
    `
    $(".manual-rows").append(html);
});

$(document).on('click', ".remove-input", function () {
    var $row = $(this).closest(".row-items");
    $row.remove();
});

$('.faq-btn').on('click', function () {
    $('#view-question').text($(this).data('question'));
    $('#view-answer').text($(this).data('answer'));
    $('#view-status').text($(this).data('status'));
});
