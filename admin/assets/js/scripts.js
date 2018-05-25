jQuery(document).ready(function ($) {
    console.log(postviews_obj);
    //featured tab
    $(".featured-tab-container .tab-menu-content").hide(); //hide all contenet of tab
    $("ul.featured-head li:first").addClass("active");//show first tab is active
    $(".featured-tab-container .tab-menu-content:first").show();//show first content is of tab
    $("ul.featured-head li").click(function () {
        var attr = $(this).find("a").attr("href");
        $(".featured-tab-container .tab-menu-content").hide(); //hide all contenet of tab
        $("ul.featured-head li").removeClass("active");//remove all active class
        $(this).addClass("active");//active the tab that you click this
        $(attr).fadeIn();//fade in the content of tab you click on this
        return false;
    });
    //daily chart
    var daily_canvas = $('#ja-daily-chart');
    var data = {
        labels: postviews_obj.days_period,
        datasets: [{
                label: 'PostViews',
                data: postviews_obj.days_postviews,
                fill: false,
                borderColor: 'rgba(0, 156, 208, 1)',
                lineTension: 0,
                // borderDash: [5]
                pointBorderColor: 'rgba(0, 156, 208, 1)',
                pointBackgroundColor: 'rgba(0, 156, 208, 1)',
                pointRadius: 3,
            }]
    };
    option = {
        scales: {
            yAxes: [{
//                    stacked: true,
                    gridLines: {
//                        display: false,
                    },
                }],
            xAxes: [{
//                    stacked: true,
                    gridLines: {
//                        display: false,
                    },
                    ticks: {
                        minRotation: 60
                    }
                }]
        },
        legend: false
    };
    if (daily_canvas.length) {
        var dayChart = new Chart(daily_canvas, {
            type: 'line',
            data: data,
            options: option
        });
    }
    //weekly chart
    var weekly_canvas = $('#ja-weekly-chart');
    var data = {
        labels: postviews_obj.weeks_period,
        datasets: [{
                label: 'PostViews',
                data: postviews_obj.weeks_postviews,
                fill: false,
                borderColor: 'rgba(0, 156, 208, 1)',
                lineTension: 0,
                // borderDash: [5]
                pointBorderColor: 'rgba(0, 156, 208, 1)',
                pointBackgroundColor: 'rgba(0, 156, 208, 1)',
                pointRadius: 3,
            }]
    };
    option = {
        scales: {
            yAxes: [{
//                    stacked: true,
                    gridLines: {
//                        display: false,
                    },
                }],
            xAxes: [{
//                    stacked: true,
                    gridLines: {
//                        display: false,
                    },
                    ticks: {
                        minRotation: 60
                    }
                }]
        },
        legend: false
    };
    if (weekly_canvas.length) {
        var weekChart = new Chart(weekly_canvas, {
            type: 'line',
            data: data,
            options: option
        });
    }
});