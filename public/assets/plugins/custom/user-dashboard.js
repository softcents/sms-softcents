"use strict";

let smsOverView = false;
let statiSticsValu = false;
var currentDashboardFilter = 'today';

// Render SMS Overview Doughnut Chart
function renderSmsOverviewChart(totalSuccessSms, totalCanceledSms) {
    if (smsOverView) {
        smsOverView.destroy();
    }
    Chart.defaults.elements.arc.roundedCornersFor = {
        "start": 0,
        "end": 0
    };
    Chart.defaults.datasets.doughnut.cutout = '65%';
    let inMonths = $("#ShareProfit");
    if (!inMonths.length) return;

    smsOverView = new Chart(inMonths, {
        type: 'doughnut',
        data: {
            labels: ["SMS Delivered: " + totalSuccessSms, "SMS Failed: " + totalCanceledSms],
            datasets: [{
                label: "Total Sms",
                borderWidth: 0,
                data: [totalSuccessSms, totalCanceledSms],
                backgroundColor: [
                    "#2CE78D",
                    "#4876FF",
                ],
                borderColor: [
                    "#2CE78D",
                    "#2CE78D",
                ],
            }]
        },
        plugins: [{
            afterUpdate: function (chart) {
                if (chart.options.elements.arc.roundedCornersFor !== undefined) {
                    var arcValues = Object.values(chart.options.elements.arc.roundedCornersFor);
                    arcValues.forEach(function (arcs) {
                        arcs = Array.isArray(arcs) ? arcs : [arcs];
                        arcs.forEach(function (i) {
                            var arc = chart.getDatasetMeta(0).data[i];
                            if (arc) {
                                arc.round = {
                                    x: (chart.chartArea.left + chart.chartArea.right) / 2,
                                    y: (chart.chartArea.top + chart.chartArea.bottom) / 2,
                                    radius: (arc.outerRadius + arc.innerRadius) / 2,
                                    thickness: (arc.outerRadius - arc.innerRadius) / 2,
                                    backgroundColor: arc.options.backgroundColor
                                };
                            }
                        });
                    });
                }
            },
            afterDraw: (chart) => {
                if (chart.options.elements.arc.roundedCornersFor !== undefined) {
                    var { ctx } = chart;
                    var roundedCornersFor = chart.options.elements.arc.roundedCornersFor;
                    for (var position in roundedCornersFor) {
                        var values = Array.isArray(roundedCornersFor[position]) ?
                            roundedCornersFor[position] : [roundedCornersFor[position]];
                        values.forEach(p => {
                            var arc = chart.getDatasetMeta(0).data[p];
                            if (arc && arc.round) {
                                var startAngle = Math.PI / 2 - arc.startAngle;
                                var endAngle = Math.PI / 2 - arc.endAngle;
                                ctx.save();
                                ctx.translate(arc.round.x, arc.round.y);
                                ctx.fillStyle = arc.options.backgroundColor;
                                ctx.beginPath();
                                if (position == "start") {
                                    ctx.arc(arc.round.radius * Math.sin(startAngle), arc.round.radius * Math.cos(startAngle), arc.round.thickness, 0, 2 * Math.PI);
                                } else {
                                    ctx.arc(arc.round.radius * Math.sin(endAngle), arc.round.radius * Math.cos(endAngle), arc.round.thickness, 0, 2 * Math.PI);
                                }
                                ctx.closePath();
                                ctx.fill();
                                ctx.restore();
                            }
                        });
                    }
                }
            }
        }],
        options: {
            responsive: true,
            tooltips: {
                displayColors: true,
                zIndex: 999999,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 10
                    }
                }
            },
            scales: {
                x: {
                    display: false,
                    stacked: true,
                },
                y: {
                    display: false,
                    stacked: true,
                }
            },
        }
    });
}

// Render SMS Statistics Line Chart
function renderSmsStatisticsChart(labels, data, chartTitle) {
    if (statiSticsValu) {
        statiSticsValu.destroy();
    }
    var canvas = document.getElementById('monthly-statistics');
    if (!canvas) return;

    var ctx = canvas.getContext('2d');
    var gradient = ctx.createLinearGradient(0, 100, 10, 280);
    gradient.addColorStop(0, '#4876FF');
    gradient.addColorStop(1, 'rgba(50, 130, 241, 0)');

    var title = chartTitle || ("Total: " + data.reduce((prevVal, currentVal) => prevVal + currentVal, 0));

    statiSticsValu = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                backgroundColor: gradient,
                label: title,
                fill: true,
                borderWidth: 2,
                borderColor: "#4876FF",
                data: data,
            }]
        },
        options: {
            responsive: true,
            tension: 0.3,
            tooltips: {
                displayColors: true,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            },
            scales: {
                x: {
                    display: true,
                },
                y: {
                    display: true,
                    beginAtZero: true
                }
            },
        },
    });
}

// MAIN FUNCTION: FETCH ALL DASHBOARD DATA DATE-WISE (A to Z)
function getDashboardData(filter, startDate, endDate) {
    filter = filter || currentDashboardFilter || 'today';
    var url = $('#get-dashboard').val();
    if (!url) return;

    var params = { filter: filter };
    if (startDate) params.start_date = startDate;
    if (endDate) params.end_date = endDate;

    // Loading opacities
    $('#total_sms, #pending_sms, #success_sms, #canceled_sms, #recharge_amount, #groups, #contacts, #templates, #recent-messages-tbody').css('opacity', '0.5');

    $.ajax({
        type: "GET",
        url: url,
        data: params,
        dataType: "json",
        success: function (res) {
            // 1. Top Counter Cards
            $('#subscription_subscribe').text(res.subscription_subscribe);
            $('#recharge_amount').text(res.recharge_amount);
            $('#total_sms').text(res.total_sms);
            $('#pending_sms').text(res.pending_sms);
            $('#success_sms').text(res.success_sms);
            $('#canceled_sms').text(res.canceled_sms);
            $('#groups').text(res.groups);
            $('#contacts').text(res.contacts);
            $('#templates').text(res.templates);

            // 2. Active Badges
            if (res.filter_label) {
                $('#active-filter-badge').html('<i class="fas fa-calendar-day me-1"></i>' + res.filter_label);
                $('#chart-history-period').text(res.filter_label);
                $('#chart-overview-period').text(res.filter_label);
                $('#table-period-badge').text(res.filter_label);
            }

            // 3. Doughnut Chart (Overview: Delivered vs Failed)
            var delivered = (res.sms_overview && res.sms_overview.delivered !== undefined) ? res.sms_overview.delivered : (res.success_sms || 0);
            var failed = (res.sms_overview && res.sms_overview.failed !== undefined) ? res.sms_overview.failed : (res.canceled_sms || 0);
            renderSmsOverviewChart(delivered, failed);

            // 4. Line Chart (History Statistics: Hourly / Daily / Monthly)
            if (res.chart_labels && res.chart_data) {
                renderSmsStatisticsChart(res.chart_labels, res.chart_data, res.chart_title);
            }

            // 5. Recent Messages Table (Filtered by Date Range)
            if (res.messages_html !== undefined) {
                $('#recent-messages-tbody').html(res.messages_html);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error loading dashboard date-wise data:', error);
        },
        complete: function () {
            $('#total_sms, #pending_sms, #success_sms, #canceled_sms, #recharge_amount, #groups, #contacts, #templates, #recent-messages-tbody').css('opacity', '1');
        }
    });
}

// Year dropdown for overview chart
$('.overview-year').on('change', function () {
    let year = $(this).val();
    let url = $('#get-sms-overview').val();
    if (!url) return;
    $.ajax({
        url: url + '?year=' + year,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            $('#chart-overview-period').text(year);
            renderSmsOverviewChart(data.success_sms || 0, data.canceled_sms || 0);
        }
    });
});

// Year dropdown for statistics chart
function getMonthNameFromIndex(index) {
    var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    return months[index] || '';
}

$('.sms-statistics').on('change', function () {
    let year = $(this).val();
    var url = $('#yearly-statistics-url').val();
    if (!url) return;
    $.ajax({
        type: "GET",
        url: url + '?year=' + year,
        dataType: "json",
        success: function (res) {
            var sms = res.sms || [];
            var total_sms = [];
            for (var i = 0; i <= 11; i++) {
                var monthName = getMonthNameFromIndex(i);
                var smsData = sms.find(item => item.month === monthName);
                total_sms[i] = smsData ? smsData.total_items : 0;
            }
            $('#chart-history-period').text(year);
            renderSmsStatisticsChart(
                ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                total_sms,
                "Yearly SMS Statistics (" + year + "): " + total_sms.reduce((a, b) => a + b, 0)
            );
        }
    });
});

// Preset Buttons (Today, Yesterday, 7 Days, This Month, All Time)
$(document).on('click', '#dashboard-date-presets button', function () {
    var $btn = $(this);
    $('#dashboard-date-presets button').removeClass('active');
    $btn.addClass('active');

    var filter = $btn.data('filter');
    currentDashboardFilter = filter;
    getDashboardData(filter);
});

// Custom Date Filter Apply
$(document).on('click', '#btn-apply-custom-date', function () {
    var startDate = $('#dash_start_date').val();
    var endDate = $('#dash_end_date').val();

    if (!startDate) {
        alert('Please select a start date');
        return;
    }

    $('#dashboard-date-presets button').removeClass('active');
    currentDashboardFilter = 'custom';
    getDashboardData('custom', startDate, endDate);
});

// Notice Modal Handling
function setLocalStorageWithExpiry(key, value, expirySeconds) {
    const now = new Date();
    const item = {
        value: value,
        expiry: now.getTime() + expirySeconds * 1000,
    };
    localStorage.setItem(key, JSON.stringify(item));
}

function getLocalStorageWithExpiry(key) {
    const itemStr = localStorage.getItem(key);
    const hasNotice = $('#has-notice').val();
    if (!itemStr && hasNotice) {
        $('#notice-modal').modal('show');
        return null;
    }

    if (!itemStr) return null;

    try {
        const item = JSON.parse(itemStr);
        const now = new Date();
        if (now.getTime() > item.expiry) {
            localStorage.removeItem(key);
            return null;
        }
        return item.value;
    } catch (e) {
        return null;
    }
}

// Document Ready Initialization (Default: Today)
$(document).ready(function() {
    getDashboardData('today');
    getLocalStorageWithExpiry('notice');
});

$('.agree-btn').on('click', function() {
    setLocalStorageWithExpiry('notice', true, 7200);
});
