

var dataArray = [{
        "date": "2012-07-27",
        "value": 13
    }, {
        "date": "2012-07-28",
        "value": 11
    }, {
        "date": "2012-07-29",
        "value": 15
    }, {
        "date": "2012-07-30",
        "value": 16
    }, {
        "date": "2012-07-31",
        "value": 18
    }, {
        "date": "2012-08-01",
        "value": 13
    }, {
        "date": "2012-08-02",
        "value": 22
    }];


//for (var i = 0; i < dataArray.length; i++) {
//    console.log(dataArray[i]);
//    chart.dataProvider.push(dataArray[i]);
//}

function zoomChart(chart) {
    chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
}

function draw(identifier, dataArray, dateFormat) {
    var chart = AmCharts.makeChart(identifier, defaultConfig(identifier, dataArray));
    chart.addListener("rendered", function () {
        zoomChart(chart);
    });

    if (typeof dateFormat !== 'undefined') {
        chart.dataDateFormat = dateFormat;
    }

    return chart;
}

function defaultConfig(id, dataArray) {
    if (typeof dataArray === 'undefined') {
        dataArray = [];
    }
    return {
        "type": "serial",
        "theme": "none", // none, light, dark, black, chalk, patterns
        "marginRight": 40,
        "marginLeft": 40,
        "autoMarginOffset": 20,
        "mouseWheelZoomEnabled": false,
        "dataDateFormat": "YYYY-MM-DD HH",
        "synchronizeGrid": true,
        "valueAxes": [{
                "id": id + "v1",
                "axisAlpha": 0,
                "position": "left",
                "ignoreAxisWidth": true
            }],
        "balloon": {
            "borderThickness": 1,
            "shadowAlpha": 0
        },
        "graphs": [{
                "id": id + "g1",
                "balloon": {
                    "drop": true,
                    "adjustBorderColor": false,
                    "color": "#ffffff"
                },
                "bullet": "round",
                "bulletBorderAlpha": 1,
                "bulletColor": "#FFFFFF",
                "bulletSize": 5,
                "hideBulletsCount": 50,
                "lineThickness": 2,
                "title": "red line",
                "useLineColorForBulletBorder": true,
                "valueField": "value",
                "balloonText": "<span style='font-size:18px;'>[[value]]</span>"
            }],
        "chartScrollbar": {
            "graph": "g1",
            "oppositeAxis": false,
            "offset": 30,
            "scrollbarHeight": 80,
            "backgroundAlpha": 0,
            "selectedBackgroundAlpha": 0.1,
            "selectedBackgroundColor": "#888888",
            "graphFillAlpha": 0,
            "graphLineAlpha": 0.5,
            "selectedGraphFillAlpha": 0,
            "selectedGraphLineAlpha": 1,
            "autoGridCount": true,
            "color": "#AAAAAA"
        },
        "chartCursor": {
            "pan": true,
            "valueLineEnabled": true,
            "valueLineBalloonEnabled": true,
            "cursorAlpha": 1,
            "cursorColor": "#258cbb",
            "limitToGraph": "g1",
            "valueLineAlpha": 0.2,
            "valueZoomable": true
        },
        "valueScrollbar": {
            "oppositeAxis": false,
            "offset": 50,
            "scrollbarHeight": 10
        },
        "categoryField": "date",
        "categoryAxis": {
            "parseDates": true,
            "dashLength": 1,
            "minorGridEnabled": true
        },
        "export": {
            "enabled": true
        },
        "dataProvider": dataArray
    };
}
