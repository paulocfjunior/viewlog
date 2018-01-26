var OPACITY_LIGHT = '0.40';
var OPACITY_MEDIUM = '0.70';
var OPACITY_STRONG = '0.85';
var OPACITY_PURE = '1.00';
/**
 *
 * @param {params} params
 * @returns {Object}
 */
 function line_dataset(params) {
    var color_qtd, color_offset;
    color_qtd = params.data.length;
    if (typeof params.color === 'undefined') {
        color_offset = parseInt(Math.random() * 70);
    } else {
        color_offset = params.color;
    }

    if (typeof params.type === 'undefined') {
        params.type = false;
    }

    var backgroundColors = [];
    var borderColors = [];
    var hoverBackgroundColors = [];
    var hoverBorderColors = [];
    if (typeof color_offset === 'object') {
        for (var i = 0; i < color_offset.length; i++) {
            backgroundColors.push(getColors(1, OPACITY_MEDIUM, color_offset[i]));
            borderColors.push(getColors(1, OPACITY_STRONG, color_offset[i]));
            hoverBackgroundColors.push(getColors(1, OPACITY_PURE, color_offset[i]));
            hoverBorderColors.push(getColors(1, OPACITY_PURE, color_offset[i]));
        }
    } else {
        backgroundColors = getColors(color_qtd, OPACITY_MEDIUM, color_offset);
        borderColors = getColors(color_qtd, OPACITY_STRONG, color_offset);
        hoverBackgroundColors = getColors(color_qtd, OPACITY_PURE, color_offset);
        hoverBorderColors = getColors(color_qtd, OPACITY_PURE, color_offset);
    }

    if (typeof params.type === 'undefined') {
        params.type = false;
    }

    if (typeof params.fill === 'undefined') {
        params.fill = false;
    }

    return {
        data: params.data,
        label: params.label,
        xAxisID: null,
        yAxisID: null,
        backgroundColor: backgroundColors,
        borderColor: borderColors,
        borderWidth: 1,
        borderDash: [],
        borderDashOffset: 0,
        borderCapStyle: 'buff', // butt || round || square
        borderJoinStyle: 'miter', // miter || round || bevel
        cubicInterpolationMode: 'default', // default, monotone
        fill: params.fill, // 1, 2, 3 || 'start', 'end', 'origin' || '-1', '-2', '+1' || false
        lineTension: 0.3, // 0 straight
        pointBackgroundColor: backgroundColors, // Color|Colors[]
        pointBorderColor: hoverBorderColors, // Color|Colors[]
        pointBorderWidth: 1, // px|px[]
        pointRadius: 5, // px/px[]
        pointStyle: 'circle', // 'cross' 'crossRot' 'dash' 'line' 'rect' 'rectRounded' 'rectRot' 'star' 'triangle'
        pointHitRadius: 7, // Number/Number[]
        pointHoverBackgroundColor: hoverBackgroundColors, // Color|Colors[]
        pointHoverBorderColor: hoverBorderColors, // Color|Colors[]
        pointHoverBorderWidth: 5, // Number/Number[]
        pointHoverRadius: 10, // Number/Number[]
        showLine: true,
        spanGaps: false,
        steppedLine: false, // false, true, 'before', 'after'
        type: params.type
    };
}

/**
 *
 * @param {params} params
 * @returns {Object}
 */
 function bar_dataset(params) {
    var color_qtd, color_offset;
    color_qtd = params.data.length;
    if (typeof params.color === 'undefined') {
        color_offset = parseInt(Math.random() * 70);
    } else {
        color_offset = params.color;
    }

    if (typeof params.type === 'undefined') {
        params.type = false;
    }

    var backgroundColors = [];
    var borderColors = [];
    var hoverBackgroundColors = [];
    var hoverBorderColors = [];
    if (typeof color_offset === 'object') {
        for (var i = 0; i < color_offset.length; i++) {
            backgroundColors.push(getColors(1, OPACITY_MEDIUM, color_offset[i]));
            borderColors.push(getColors(1, OPACITY_STRONG, color_offset[i]));
            hoverBackgroundColors.push(getColors(1, OPACITY_STRONG, color_offset[i]));
            hoverBorderColors.push(getColors(1, OPACITY_STRONG, color_offset[i]));
        }
    } else {
        backgroundColors = getColors(color_qtd, OPACITY_MEDIUM, color_offset);
        borderColors = getColors(color_qtd, OPACITY_STRONG, color_offset);
        hoverBackgroundColors = getColors(color_qtd, OPACITY_STRONG, color_offset);
        hoverBorderColors = getColors(color_qtd, OPACITY_STRONG, color_offset);
    }

    return {
        data: params.data,
        label: params.label,
        xAxisID: null,
        yAxisID: null,
        backgroundColor: backgroundColors,
        borderColor: borderColors,
        borderWidth: 1,
        borderSkipped: 'bottom', // bottom, top, left, right
        hoverBackgroundColor: hoverBackgroundColors, // Color|Colors[]
        hoverBorderColor: hoverBorderColors, // Color|Colors[]
        hoverBorderWidth: 5, // Number/Number[]
        type: params.type
    };
}

/**
 *
 * @param {params} params
 * @returns {Object}
 */
 function pie_dataset(params) {
    var color_qtd, color_offset;
    color_qtd = params.data.length;
    var backgroundColors = [];
    var borderColors = [];
    var hoverBackgroundColors = [];
    var hoverBorderColors = [];
    if (typeof params.color === 'undefined') {
        color_offset = parseInt(Math.random() * 70);
    } else {
        color_offset = params.color;
    }

    if (typeof color_offset === 'object') {
        for (var i = 0; i < color_offset.length; i++) {
            backgroundColors.push(getColors(1, OPACITY_MEDIUM, color_offset[i]));
            borderColors.push(getColors(1, OPACITY_STRONG, color_offset[i]));
            hoverBackgroundColors.push(getColors(1, OPACITY_STRONG, color_offset[i]));
            hoverBorderColors.push(getColors(1, OPACITY_STRONG, color_offset[i]));
        }
    } else {
        backgroundColors = getColors(color_qtd, OPACITY_MEDIUM, color_offset);
        borderColors = getColors(color_qtd, OPACITY_STRONG, color_offset);
        hoverBackgroundColors = getColors(color_qtd, OPACITY_STRONG, color_offset);
        hoverBorderColors = getColors(color_qtd, OPACITY_STRONG, color_offset);
    }

    if (typeof params.type === 'undefined') {
        params.type = false;
    }

    return {
        data: params.data,
        label: params.label,
        xAxisID: null,
        yAxisID: null,
        backgroundColor: backgroundColors,
        borderColor: borderColors,
        borderWidth: 1,
        borderSkipped: 'bottom', // bottom, top, left, right
        hoverBackgroundColor: hoverBackgroundColors,
        hoverBorderColor: hoverBorderColors,
        hoverBorderWidth: 0, // Number/Number[]
        type: params.type
    };
}

/**
 *
 * @param {string} type
 * @param {string|object} canvas
 * @param {array} array_labels
 * @param {array} array_datasets
 * @param {function} callback
 * @returns {Chart}
 */
 function draw_chart(title_text, type, canvas, array_labels, array_datasets, callback) {
    // var progress = document.getElementById("progress");

    if (typeof canvas === 'string') {
        canvas = document.getElementById(canvas);
    }

    var datasets = [];
    var functions = {
        'line': line_dataset,
        'bar': bar_dataset,
        'pie': pie_dataset,
        'doughnut': pie_dataset,
        'polar': pie_dataset
    };
    var graph_type = type.split("-")[0];
    for (var i = 0; i < array_datasets.length; i++) {
        datasets.push(functions[graph_type](array_datasets[i]));
    }

    if (title_text !== false) {
        title = {
            display: true,
            text: title_text,
            position: 'top',
            fontSize: 14,
            lineHeight: 1.5
        };
    } else {
        title = {
            display: false
        };
    }

    var global_options = {
        title: title,
        responsive: false,
        maintainAspectRatio: false,
        animation: {
            onProgress: function (animation) {
                // progress.innerHTML = animation.animationObject.currentStep / animation.animationObject.numSteps;
            }
        },
        // onClick: callback
    };
    switch (type) {
        case 'line':
        return new Chart(canvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: array_labels,
                datasets: datasets
            },
            options: Object.assign({}, global_options, {
                    showLines: true, // default: true
                    spanGaps: false, // default: false
                    scales: {
                        yAxes: [{
                                stacked: false // default: false
                            }]
                        },
                        elements: {
                            line: {
                            tension: 0 // 0 for better performance
                        }
                    }
                })
        });
        break;
        case 'line-stacked':
        return new Chart(canvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: array_labels,
                datasets: datasets
            },
            options: Object.assign({}, global_options, {
                    showLines: true, // default: true
                    spanGaps: false, // default: false
                    scales: {
                        yAxes: [{
                                stacked: true // default: false
                            }]
                        },
                        elements: {
                            line: {
                            tension: 0 // 0 for better performance
                        }
                    }
                })
        });
        break;
        case 'bar':
        return new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: array_labels,
                datasets: datasets
            },
            options: Object.assign({}, global_options, {
                barPercentage: 0.5,
                categoryPercentage: 1.0,
                scales: {
                    xAxes: [{
                        stacked: false
                    }],
                    yAxes: [{
                        stacked: false
                    }]
                }
            })
        });
        break;
        case 'bar-stacked':
        return new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: array_labels,
                datasets: datasets
            },
            options: Object.assign({}, global_options, {
                barPercentage: 0.5,
                categoryPercentage: 1.0,
                scales: {
                    xAxes: [{
                        stacked: true
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            })
        });
        break;
        case 'bar-stackedX':
        return new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: array_labels,
                datasets: datasets
            },
            options: Object.assign({}, global_options, {
                barPercentage: 0.5,
                categoryPercentage: 1.0,
                scales: {
                    xAxes: [{
                        stacked: true
                    }],
                    yAxes: [{
                        stacked: false
                    }]
                }
            })
        });
        break;
        case 'bar-stackedY':
        return new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: array_labels,
                datasets: datasets
            },
            options: Object.assign({}, global_options, {
                barPercentage: 0.5,
                categoryPercentage: 1.0,
                scales: {
                    xAxes: [{
                        stacked: false
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            })
        });
        break;
        case 'bar-horizontal':
        return new Chart(canvas.getContext('2d'), {
            type: 'horizontalBar',
            data: {
                labels: array_labels,
                datasets: datasets
            },
            options: Object.assign({}, global_options, {
                responsive: true,
                maintainAspectRatio: false,
                barPercentage: 0.5,
                categoryPercentage: 1.0,
                title: title,
                scales: {
                    xAxes: [{
                        stacked: false
                    }],
                    yAxes: [{
                        stacked: false
                    }]
                }
            })
        });
        break;

        case 'bar-horizontal-stacked':
        return new Chart(canvas.getContext('2d'), {
            type: 'horizontalBar',
            data: {
                labels: array_labels,
                datasets: datasets
            },
            options: Object.assign({}, global_options, {
                barPercentage: 0.5,
                categoryPercentage: 1.0,
                scales: {
                    xAxes: [{
                        stacked: true
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            })
        });
        break;
        case 'pie':
        return new Chart(canvas.getContext('2d'), {
            type: 'pie',
            data: {
                labels: array_labels,
                datasets: datasets
            },
            options: Object.assign({}, global_options, {
                cutoutPercentage: 0,
                animation: {
                    animateRotate: true,
                    animateScale: true
                }
            })
        });
        break;
        case 'doughnut':
        return new Chart(canvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: array_labels,
                datasets: datasets
            },
            options: Object.assign({}, global_options, {
                cutoutPercentage: 60,
                animation: {
                    animateRotate: true,
                    animateScale: true
                },
                layout: {
                    padding: {
                        left: 15,
                        right: 15,
                        top: 10,
                        bottom: 10
                    }
                }
            })
        });
        break;
        case 'polar':
        return new Chart(canvas.getContext('2d'), {
            type: 'polarArea',
            data: {
                labels: array_labels,
                datasets: datasets
            },
            options: Object.assign({}, global_options, {
                cutoutPercentage: 0,
                animation: {
                    animateRotate: true,
                    animateScale: true
                }
            })
        });
        break;
    }
}

function chart_callback(event) {
    console.log(event);
}