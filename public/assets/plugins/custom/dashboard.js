"use strict";

$('.overview-year').on('change', function () {
    let year = $(this).val();
    updateSmsOverviewChart(year)
})

$(document).ready(function() {
    getYearlyStatistics();
    updateSmsOverviewChart();
})

let smsOverView = false;

// Function to update the Sms Overview chart
function updateSmsOverviewChart(year = new Date().getFullYear()) {
    if (smsOverView) {
        smsOverView.destroy();
    }
    Chart.defaults.elements.arc.roundedCornersFor = {
        "start": 0,
        "end": 0
    };
    Chart.defaults.datasets.doughnut.cutout = '65%';
    let url = $('#get-sms-overview').val();
    $.ajax({
        url: url += '?year=' + year,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            let totalSuccessSms = data.success_sms;
            let totalCenceledSms = data.canceled_sms;
            let inMonths = $("#ShareProfit");
            smsOverView = new Chart(inMonths, {
                type: 'doughnut',
                data: {
                    labels: ["SMS Delivered: " + totalSuccessSms, "SMS Failed: " + totalCenceledSms],
                    datasets: [{
                        label: "Total Sms",
                        borderWidth: 0,
                        data: [totalSuccessSms, totalCenceledSms],
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
                                    arc.round = {
                                        x: (chart.chartArea.left + chart.chartArea
                                            .right) / 2,
                                        y: (chart.chartArea.top + chart.chartArea
                                            .bottom) / 2,
                                        radius: (arc.outerRadius + arc
                                            .innerRadius) / 2,
                                        thickness: (arc.outerRadius - arc
                                            .innerRadius) / 2,
                                        backgroundColor: arc.options.backgroundColor
                                    }
                                });
                            });
                        }
                    },
                    afterDraw: (chart) => {

                        if (chart.options.elements.arc.roundedCornersFor !== undefined) {
                            var {
                                ctx,
                                canvas
                            } = chart;
                            var arc,
                                roundedCornersFor = chart.options.elements.arc.roundedCornersFor;
                            for (var position in roundedCornersFor) {
                                var values = Array.isArray(roundedCornersFor[position]) ?
                                    roundedCornersFor[position] : [roundedCornersFor[position]];
                                values.forEach(p => {
                                    arc = chart.getDatasetMeta(0).data[p];
                                    var startAngle = Math.PI / 2 - arc.startAngle;
                                    var endAngle = Math.PI / 2 - arc.endAngle;
                                    ctx.save();
                                    ctx.translate(arc.round.x, arc.round.y);
                                    ctx.fillStyle = arc.options.backgroundColor;
                                    ctx.beginPath();
                                    if (position == "start") {
                                        ctx.arc(arc.round.radius * Math.sin(startAngle), arc
                                            .round.radius * Math.cos(startAngle), arc.round
                                            .thickness, 0, 2 * Math.PI);
                                    } else {
                                        ctx.arc(arc.round.radius * Math.sin(endAngle), arc.round
                                            .radius * Math.cos(endAngle), arc.round
                                            .thickness, 0, 2 * Math.PI);
                                    }
                                    ctx.closePath();
                                    ctx.fill();
                                    ctx.restore();
                                });

                            }
                            ;
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
                        },
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
        },
        error: function (xhr, textStatus, errorThrown) {
            console.log('Error fetching user overview data: ' + textStatus);
        }
    });
}

// PRINT TOP DATA
getDashboardData();
function getDashboardData() {
    var url = $('#get-dashboard').val();
    $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        success: function (res) {
            $('#plans').text(res.plans);
            $('#customers').text(res.customers);
            $('#subcribers').text(res.subcribers);
            $('#api_gateways').text(res.api_gateways);
            $('#andriod_gateways').text(res.andriod_gateways);
            $('#recharge_amount').text(res.recharge_amount);
            $('#sender_ids').text(res.sender_ids);
            $('#total_sms').text(res.total_sms);
            $('#pending_sms').text(res.pending_sms);
            $('#success_sms').text(res.success_sms);
            $('#canceled_sms').text(res.canceled_sms);
        }
    });
}

// Function to convert month index to month name
function getMonthNameFromIndex(index) {
    var months = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];
    return months[index - 1];
}

$('.sms-statistics').on('change', function () {
    let year = $(this).val();
    getYearlyStatistics(year)
})

function getYearlyStatistics(year = new Date().getFullYear()) {
    var url = $('#yearly-statistics-url').val();
    $.ajax({
        type: "GET",
        url: url += '?year=' + year,
        dataType: "json",
        success: function (res) {
            var sms = res.sms;
            var total_sms = [];

            for (var i = 0; i <= 11; i++) {
                var monthName = getMonthNameFromIndex(i); // Implement this function to get month name

                var smsData = sms.find(item => item.month === monthName);
                total_sms[i] = smsData ? smsData.total_items : 0;

            }
            totalGeneratesChart(total_sms)
        },
    });
}

let statiSticsValu = false;

function totalGeneratesChart(total_sms) {
    if (statiSticsValu) {
        statiSticsValu.destroy();
    }

    var ctx = document.getElementById('monthly-statistics').getContext('2d');
    var gradient = ctx.createLinearGradient(0, 100, 10, 280);
    gradient.addColorStop(0, '#4876FF');
    gradient.addColorStop(1, 'rgba(50, 130, 241, 0)');

    var gradient2 = ctx.createLinearGradient(0, 0, 0, 290);
    gradient2.addColorStop(0, 'rgba(255, 68, 5, 1)');
    gradient2.addColorStop(1, 'rgba(255, 68, 5, 0)');


    statiSticsValu = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                    backgroundColor: gradient,
                    label: "Yearly sms statistics: " + total_sms.reduce((prevVal, currentVal) => prevVal + currentVal, 0),
                    fill: true,
                    borderWidth: 1,
                    borderColor: "#4876FF",
                    data: total_sms,
                }
            ]
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
                        padding: 30
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
};
