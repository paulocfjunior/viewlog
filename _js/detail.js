

var chart = AmCharts.makeChart("chartdiv", {
    "type": "serial",
    "theme": "light",
    "legend": {
        "equalWidths": true,
        "useGraphSettings": true,
        "valueAlign": "left",
        "valueWidth": 120
    },
    "dataProvider": chartData,
    "valueAxes": [{
            "id": "ipAxis",
            "axisAlpha": 0,
            "gridAlpha": 0,
            "position": "left",
            "title": "IPs"
        }, {
            "id": "viewsAxis",
            "axisAlpha": 0,
            "gridAlpha": 0,
            "inside": false,
            "position": "right",
            "title": "Acessos"
        }],
    "graphs": [{
            "alphaField": "alpha",
            "balloonText": "[[ipSTR]]",
            "dashLengthField": "dashLength",
            "fillAlphas": 0.5,
            "legendPeriodValueText": "",
            "legendValueText": "[[ips]]",
            "title": "IPs",
            "type": "column",
            "valueField": "ips",
            "valueAxis": "ipAxis"
        }, {
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "useLineColorForBulletBorder": true,
            "bulletColor": "#FFFFFF",
            "bulletSizeField": "townSize",
            "legendValueText": "[[value]]",
            "title": "Visualizações",
            "fillAlphas": 0,
            "valueField": "views",
            "valueAxis": "viewsAxis"
        }],
    "chartScrollbar": {
        "autoGridCount": true,
        "graph": "g1",
        "scrollbarHeight": 40
    },
    "chartCursor": {
        "categoryBalloonDateFormat": "DD",
        "cursorAlpha": 0.1,
        "cursorColor": "#000000",
        "fullWidth": true,
        "valueBalloonsEnabled": true,
        "zoomable": true
    },
    "dataDateFormat": "YYYY-MM-DD HH:NN:SS",
    "categoryField": "date",
    "categoryAxis": {
        "dateFormats": [{
                "period": "HH",
                "format": "DD HH"
            }, {
                "period": "DD",
                "format": "DD"
            }, {
                "period": "WW",
                "format": "MMM DD"
            }, {
                "period": "MM",
                "format": "MMM"
            }, {
                "period": "YYYY",
                "format": "YYYY"
            }],
        "parseDates": true,
        "autoGridCount": false,
        "axisColor": "#555555",
        "gridAlpha": 0.1,
        "gridColor": "#FFFFFF",
        "gridCount": 50
    },
    "mouseWheelZoomEnabled": false,
    "export": {
        "enabled": true
    }
});

chart.addListener("rendered", zoomChart);
zoomChart();

// this method is called when chart is first inited as we listen for "rendered" event
function zoomChart() {
    // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
    chart.zoomToIndexes(chartData.length - 40, chartData.length - 1);
}

var chart2 = AmCharts.makeChart("chartdiv2", {
    "type": "radar",
    "theme": "light",
    "dataProvider": radarData,
    "valueAxes": [{
            "axisTitleOffset": 20,
            "minimum": 0,
            "axisAlpha": 0.15
        }],
    "startDuration": 1,
    "graphs": [{
            "balloonText": "Média de [[value]] acessos de dia",
            "bullet": "round",
            "lineThickness": 2,
            "valueField": "day"
        },
        {
            "balloonText": "Média de [[value]] acessos de noite",
            "bullet": "round",
            "lineThickness": 2,
            "valueField": "night"
        }],
    "categoryField": "hour",
    "export": {
        "enabled": true
    }
});
