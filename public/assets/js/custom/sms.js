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
})
