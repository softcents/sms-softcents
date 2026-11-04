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

window.bulkSmsFileCache = window.bulkSmsFileCache || {};

window.parseBulkSmsNumberCount = function (file, callback) {
    if (!file) {
        callback(0);
        return;
    }
    var cacheKey = file.name + '_' + file.size + '_' + file.lastModified;
    if (window.bulkSmsFileCache[cacheKey] !== undefined) {
        callback(window.bulkSmsFileCache[cacheKey]);
        return;
    }

    // 1. If SheetJS is available, parse immediately in browser (supports .xlsx, .xls, .ods, .csv)
    if (typeof XLSX !== 'undefined') {
        var reader = new FileReader();
        reader.onload = function (e) {
            try {
                var data = new Uint8Array(e.target.result);
                var workbook = XLSX.read(data, { type: 'array' });
                var firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                var rows = XLSX.utils.sheet_to_json(firstSheet, { header: 1, defval: '' });
                var validCount = 0;
                var startIndex = 1;
                if (rows.length > 0 && rows[0][0]) {
                    var firstClean = String(rows[0][0]).replace(/[^0-9]/g, '');
                    if (firstClean.length >= 10 && /^(?:88)?01[3-9]/.test(firstClean)) {
                        startIndex = 0;
                    }
                }
                for (var i = startIndex; i < rows.length; i++) {
                    var cellVal = rows[i][0];
                    if (cellVal !== undefined && cellVal !== null) {
                        var clean = String(cellVal).replace(/[^0-9]/g, '');
                        if (clean.length >= 8) {
                            validCount++;
                        }
                    }
                }
                var finalCount = Math.max(1, validCount);
                window.bulkSmsFileCache[cacheKey] = finalCount;
                callback(finalCount);
                return;
            } catch (err) {
                console.warn('SheetJS parsing failed, falling back to server...', err);
            }
        };
        reader.readAsArrayBuffer(file);
        return;
    }

    // 2. If CSV without SheetJS
    if (file.name.toLowerCase().endsWith('.csv')) {
        var textReader = new FileReader();
        textReader.onload = function (e) {
            var lines = (e.target.result || '').split(/\r\n|\n/).map(function (l) { return l.trim(); }).filter(function (l) { return l.length >= 5; });
            var finalCount = Math.max(1, lines.length);
            window.bulkSmsFileCache[cacheKey] = finalCount;
            callback(finalCount);
        };
        textReader.readAsText(file);
        return;
    }

    // 3. Fallback to AJAX route
    var formData = new FormData();
    formData.append('file', file);
    formData.append('_token', $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val() || '');

    $.ajax({
        url: '/users/bulk-sms/count-numbers',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            var count = (res && res.count) ? parseInt(res.count) : 1;
            window.bulkSmsFileCache[cacheKey] = count;
            callback(count);
        },
        error: function () {
            callback(1);
        }
    });
};

// Pre-parse on file input change
$(document).on('change', 'input[name="file"]', function () {
    if (this.files && this.files.length > 0) {
        var file = this.files[0];
        window.parseBulkSmsNumberCount(file, function (count) {
            $('#file-status-badge').removeClass('d-none').html(
                '<small class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i>' + count + (count > 1 ? ' phone numbers detected' : ' phone number detected') + '</small>'
            );
        });
    }
});

// Confirmation Modal trigger before sending SMS (supports Quick, Group, Bulk, Campaign SMS)
$(document).off('click', '#btn-trigger-sms-modal').on('click', '#btn-trigger-sms-modal', function (e) {
    e.preventDefault();

    var $form = $(this).closest('form');
    if (!$form.length) {
        $form = $('form:has(#real-submit-btn)');
    }

    var senderSelect = $form.find('select[name="senderid_id"]');
    var contentInput = $form.find('#contents, textarea[name="contents"]');
    var contentText = (contentInput.val() || '').trim();

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

    if (!contentText) {
        if (typeof Notify === 'function') {
            Notify('error', { responseJSON: { message: 'Please enter message content.' } });
        } else {
            alert('Please enter message content.');
        }
        contentInput.focus();
        return;
    }

    // Accurate Character count & Unicode detection
    var charCount = Array.from(contentText).length;
    var isUnicode = didITypeUnicode(contentText);
    var charPerSms = isUnicode ? 70 : 160;
    var charPerSmsLong = isUnicode ? 67 : 153;
    var effectiveCharPerSms = charCount > charPerSms ? charPerSmsLong : charPerSms;
    var parts = charCount > 0 ? Math.ceil(charCount / effectiveCharPerSms) : 1;

    var rate = parseFloat($('#the-count').attr('data-rate')) || parseFloat($('#the-count').data('rate')) || 0.39;
    var unitCost = parts * rate;

    // User Balance check: Old Balance & New Balance
    var userBalanceAttr = $('#the-count').attr('data-balance') || $('#the-count').data('balance');
    var oldBalance = userBalanceAttr !== undefined && userBalanceAttr !== null && userBalanceAttr !== '' ? parseFloat(userBalanceAttr) : 0;

    var senderText = senderSelect.find('option:selected').text().trim();
    var charInfo = charCount + ' Chars (' + (isUnicode ? 'বাংলা / Unicode' : 'Text / English') + ')';

    var renderModal = function(recipientsLabel, totalCostText, newBalanceText) {
        $('#modal-old-balance').text('৳' + oldBalance.toFixed(2));
        $('#modal-new-balance').text(newBalanceText);
        $('#modal-total-cost').text(totalCostText);
        $('#modal-sender-id').text(senderText);
        $('#modal-recipients-count').text(recipientsLabel);
        $('#modal-sms-parts').text(parts + (parts > 1 ? ' Parts' : ' Part'));
        $('#modal-sms-rate').text('৳' + rate.toFixed(2));
        $('#modal-char-info').text(charInfo);
        $('#modal-content-preview').text(contentText);

        // Store active form reference
        window.activeSmsForm = $form;

        $('#sms-confirm-modal').modal('show');
    };

    // Determine type of form
    var numbersInput = $form.find('#numbers, textarea[name="numbers"], input[name="numbers"]');
    var groupSelect = $form.find('#group_id, select[name="group_id"]');
    var fileInput = $form.find('input[name="file"]');
    var campaignSelect = $form.find('select[name="campaign_id"]');

    if (groupSelect.length) {
        // GROUP SMS
        if (!groupSelect.val()) {
            if (typeof Notify === 'function') {
                Notify('error', { responseJSON: { message: 'Please select a contact group.' } });
            } else {
                alert('Please select a contact group.');
            }
            groupSelect.focus();
            return;
        }
        var selectedOpt = groupSelect.find('option:selected');
        var contactsCount = parseInt(selectedOpt.data('contacts')) || 0;
        var totalCost = (unitCost * Math.max(1, contactsCount)).toFixed(2);
        var newBalance = Math.max(0, oldBalance - parseFloat(totalCost)).toFixed(2);
        var groupName = selectedOpt.text().split('(')[0].trim();
        renderModal(contactsCount + (contactsCount === 1 ? ' Contact (' : ' Contacts (') + groupName + ')', totalCost, '৳' + newBalance);

    } else if (fileInput.length) {
        // BULK SMS
        if (!fileInput[0].files || fileInput[0].files.length === 0) {
            if (typeof Notify === 'function') {
                Notify('error', { responseJSON: { message: 'Please upload an Excel or CSV file containing phone numbers.' } });
            } else {
                alert('Please upload an Excel or CSV file containing phone numbers.');
            }
            fileInput.focus();
            return;
        }
        var file = fileInput[0].files[0];
        var fileName = file.name;
        var $triggerBtn = $(this);
        var originalBtnHtml = $triggerBtn.html();

        $triggerBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Reading file...');

        window.parseBulkSmsNumberCount(file, function (recipientsCount) {
            $triggerBtn.prop('disabled', false).html(originalBtnHtml);

            var totalCost = (unitCost * Math.max(1, recipientsCount)).toFixed(2);
            var newBalance = Math.max(0, oldBalance - parseFloat(totalCost)).toFixed(2);

            renderModal(
                recipientsCount + (recipientsCount === 1 ? ' Number (' : ' Numbers (') + fileName + ')',
                totalCost,
                '৳' + newBalance
            );
        });
        return;

    } else if (campaignSelect.length) {
        // CAMPAIGN SMS
        if (!campaignSelect.val()) {
            if (typeof Notify === 'function') {
                Notify('error', { responseJSON: { message: 'Please select a Campaign recipient list.' } });
            } else {
                alert('Please select a Campaign recipient list.');
            }
            campaignSelect.focus();
            return;
        }

        var selectedOpt = campaignSelect.find('option:selected');
        var totalAvailable = parseInt(selectedOpt.data('total_numbers')) || 0;
        var startVal = parseInt($form.find('#range_start, input[name="range_start"]').val()) || 1;
        var endVal = ($form.find('#range_end, input[name="range_end"]').val() || 'All').trim();

        var recipientsCount = 0;
        var rangeDesc = '';
        if (endVal.toLowerCase() === 'all' || endVal === '') {
            recipientsCount = totalAvailable > 0 ? Math.max(1, totalAvailable - startVal + 1) : 1;
            rangeDesc = '#' + startVal + ' to All';
        } else {
            var endNum = parseInt(endVal) || startVal;
            recipientsCount = endNum >= startVal ? (endNum - startVal + 1) : endNum;
            rangeDesc = '#' + startVal + ' to #' + (startVal + recipientsCount - 1);
        }

        var totalCost = (unitCost * recipientsCount).toFixed(2);
        var newBalance = Math.max(0, oldBalance - parseFloat(totalCost)).toFixed(2);
        var campName = selectedOpt.text().trim();
        renderModal(recipientsCount + ' Contacts (' + campName + ' [' + rangeDesc + '])', totalCost, '৳' + newBalance);

    } else {
        // QUICK SMS
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
        var validNums = rawNums.split(',').map(function (n) { return n.trim(); }).filter(function (n) { return n.length >= 5; });
        var recipientsCount = Math.max(1, validNums.length);
        var totalCost = (unitCost * recipientsCount).toFixed(2);
        var newBalance = Math.max(0, oldBalance - parseFloat(totalCost)).toFixed(2);
        renderModal(recipientsCount + (recipientsCount > 1 ? ' Numbers' : ' Number'), totalCost, '৳' + newBalance);
    }
});

// Confirm & Send Button in Modal
$(document).off('click', '#btn-confirm-send-sms').on('click', '#btn-confirm-send-sms', function () {
    var $btn = $(this);
    var originalHtml = $btn.html();
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Sending...');

    $('#sms-confirm-modal').modal('hide');

    // Trigger the actual form submission
    setTimeout(function () {
        var formToSubmit = window.activeSmsForm || $('form:has(#real-submit-btn)');
        formToSubmit.find('#real-submit-btn').trigger('click');
        $btn.prop('disabled', false).html(originalHtml);
    }, 250);
});
