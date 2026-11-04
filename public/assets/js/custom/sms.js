"use strict";

var smsType = 0;
var totalCharacter = 0;
var charLeft = 0;
var noOfSms = 1;
var charPerSms = 160;
var charPerSmsForLong = 153;
var current;

$('#contents, #numbers').on('input', function () {
    messageDataCal();
});

function messageDataCal() {
    var textContent = $('#contents').val() || '';
    var isUnicode = didITypeUnicode(textContent);

    var countArr = [];
    var nonArr = [];

    let arr = textContent.split("");

    for (let i = 0; i < arr.length; i++) {
        if (/[\[\]{}|~^\\]/.test(arr[i])) {
            countArr.push(arr[i]);
        } else {
            nonArr.push(arr[i]);
        }
    }

    let characterCount = countArr.length * 2 + nonArr.length;

    setCountAndAttr(isUnicode);

    current = $('#current');
    current.text(characterCount);

    $('#char_count').val(characterCount);

    var charPerSmsDisplay = characterCount > charPerSms ? charPerSmsForLong : charPerSms;
    $('#char_per_sms').text(charPerSmsDisplay);

    noOfSms = characterCount > 0 ? Math.ceil(characterCount / charPerSmsDisplay) : 0;
    $('#no_of_sms').text(noOfSms);

    // Live Estimated Cost calculation
    var rate = parseFloat($('#the-count').data('rate')) || 0.39;
    var rawNums = $('#numbers').val() || '';
    var validNums = rawNums.split(',').map(function (n) { return n.trim(); }).filter(function (n) { return n.length >= 5; });
    var recipients = Math.max(1, validNums.length);
    var estCost = (noOfSms * rate * recipients).toFixed(2);
    $('#est_cost').text(estCost);
}

function setCountAndAttr(isUnicode) {
    charPerSms = isUnicode ? 70 : 160;
    charPerSmsForLong = isUnicode ? 67 : 153;
    $('#char_per_sms').text(charPerSms);
    $('#sms_type').val(isUnicode ? '3' : '1');

    $('.text').prop({
        'checked': !isUnicode,
        'disabled': isUnicode
    });

    $('.unicode').prop({
        'checked': isUnicode,
        'disabled': !isUnicode
    });
}

function didITypeUnicode(text) {
    return /[^\u0000-\u007F]/.test(text);
}

$('#sms-templates').on('click', function() {
    let url = $(this).data('url');

    $.ajax({
        type: "get",
        url: url,
        success: function (res) {
            $('.templates-list').html(res.data)
        }
    });

    $('#sms-template-modal').modal('show');
});

$(document).on('click', '.use-template', function() {
    let text = $(this).data('text');
    $('#contents').val(text);
    messageDataCal();
    $('#sms-template-modal').modal('hide');
});

// Confirmation Modal trigger before sending SMS
$(document).on('click', '#btn-trigger-sms-modal', function (e) {
    e.preventDefault();

    var senderSelect = $('select[name="senderid_id"]');
    var numbersInput = $('#numbers');
    var contentInput = $('#contents');

    // Basic Validations
    if (!senderSelect.val()) {
        if (typeof Notify === 'function') {
            Notify('error', { responseJSON: { message: 'Please select a Sender ID.' } });
        } else {
            alert('Please select a Sender ID.');
        }
        senderSelect.focus();
        return;
    }

    var rawNums = (numbersInput.val() || '').trim();
    if (!rawNums) {
        if (typeof Notify === 'function') {
            Notify('error', { responseJSON: { message: 'Please enter at least one recipient phone number.' } });
        } else {
            alert('Please enter at least one recipient phone number.');
        }
        numbersInput.focus();
        return;
    }

    var contentText = (contentInput.val() || '').trim();
    if (!contentText) {
        if (typeof Notify === 'function') {
            Notify('error', { responseJSON: { message: 'Please enter message content.' } });
        } else {
            alert('Please enter message content.');
        }
        contentInput.focus();
        return;
    }

    // Calculation
    var validNums = rawNums.split(',').map(function (n) { return n.trim(); }).filter(function (n) { return n.length >= 5; });
    var recipientsCount = Math.max(1, validNums.length);

    var isUnicode = didITypeUnicode(contentText);
    var charCount = parseInt($('#current').text()) || contentText.length;
    var parts = parseInt($('#no_of_sms').text()) || 1;
    if (parts < 1) parts = 1;

    var rate = parseFloat($('#the-count').data('rate')) || 0.39;
    var unitCost = parts * rate;
    var totalCost = (unitCost * recipientsCount).toFixed(2);

    // User Balance check: Old Balance & New Balance
    var oldBalance = parseFloat($('#the-count').data('balance')) || 0;
    var newBalance = Math.max(0, oldBalance - parseFloat(totalCost)).toFixed(2);

    var senderText = senderSelect.find('option:selected').text().trim();

    // Populate Compact Modal
    $('#modal-old-balance').text('৳' + oldBalance.toFixed(2));
    $('#modal-new-balance').text('৳' + newBalance);
    $('#modal-total-cost').text(totalCost);
    $('#modal-sender-id').text(senderText);
    $('#modal-recipients-count').text(recipientsCount + (recipientsCount > 1 ? ' Numbers' : ' Number'));
    $('#modal-sms-parts').text(parts + (parts > 1 ? ' Parts' : ' Part'));
    $('#modal-sms-rate').text('৳' + rate.toFixed(2));
    $('#modal-char-info').text(charCount + ' Chars (' + (isUnicode ? 'বাংলা / Unicode' : 'Text / English') + ')');
    $('#modal-content-preview').text(contentText);

    // Show Small Modal
    $('#sms-confirm-modal').modal('show');
});

// Confirm & Send Button in Modal
$(document).on('click', '#btn-confirm-send-sms', function () {
    var $btn = $(this);
    var originalHtml = $btn.html();
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Sending...');

    $('#sms-confirm-modal').modal('hide');

    // Trigger the actual form submission
    setTimeout(function () {
        $('#quick-sms-form').find('#real-submit-btn').trigger('click');
        $btn.prop('disabled', false).html(originalHtml);
    }, 250);
});
