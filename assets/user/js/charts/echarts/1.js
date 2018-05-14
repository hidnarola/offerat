/* ------------------------------------------------------------------------------
 *
 *  # Echarts - columns and waterfalls
 *
 *  Columns and waterfalls chart configurations
 *
 *  Version: 1.0
 *  Latest update: August 1, 2015
 *
 * ---------------------------------------------------------------------------- */

$(function () {

    // Set paths
    // ------------------------------

    require.config({
        paths: {
            echarts: 'assets/js/plugins/visualization/echarts'
        }
    });


    // Configuration
    // ------------------------------

    require(
        [
            'echarts',
            'echarts/theme/limitless',
            'echarts/chart/bar',
            'echarts/chart/line'
        ],


        // Charts setup
        function (ec, limitless) {


            // Initialize charts
            // ------------------------------
            var columns_timeline = ec.init(document.getElementById('columns_timeline'), limitless);

            // Charts setup
            // ------------------------------

            columns_timeline_options = {

                // Setup timeline
                timeline: {
                    data: ['2010-01-01', '2011-01-01', '2012-01-01', '2013-01-01', '2014-01-01'],
                    x: 10,
                    x2: 10,
                    label: {
                        formatter: function(s) {
                            return s.slice(0, 4);
                        }
                    },
                    autoPlay: true,
                    playInterval: 3000
                },

                // Main options
                options: [
                    {

                        // Setup grid
                        grid: {
                            x: 55,
                            x2: 110,
                            y: 35,
                            y2: 100
                        },

                        // Add tooltip
                        tooltip: {
                            trigger: 'axis'
                        },

                        // Add legend
                        legend: {
                            data: ['GDP','Financial','Real Estate','Primary industry','Secondary industry','Third industry']
                        },

                        // Add toolbox
                        toolbox: {
                            show: true,
                            orient: 'vertical',
                            x: 'right', 
                            y: 70,
                            feature: {
                                mark: {
                                    show: true,
                                    title: {
                                        mark: 'Markline switch',
                                        markUndo: 'Undo markline',
                                        markClear: 'Clear markline'
                                    }
                                },
                                dataView: {
                                    show: true,
                                    readOnly: false,
                                    title: 'View data',
                                    lang: ['View chart data', 'Close', 'Update']
                                },
                                magicType: {
                                    show: true,
                                    title: {
                                        line: 'Switch to line chart',
                                        bar: 'Switch to bar chart',
                                        stack: 'Switch to stack',
                                        tiled: 'Switch to tiled'
                                    },
                                    type: ['line', 'bar', 'stack', 'tiled']
                                },
                                restore: {
                                    show: true,
                                    title: 'Restore'
                                },
                                saveAsImage: {
                                    show: true,
                                    title: 'Same as image',
                                    lang: ['Save']
                                }
                            }
                        },

                        // Enable drag recalculate
                        calculable: true,

                        // Horizontal axis
                        xAxis: [{
                            type: 'category',
                            axisLabel: {
                                interval: 0
                            },
                            data: ['Paris','Budapest','Prague','Madrid','Amsterdam','Berlin','Bratislava','Munich','Hague','Rome','Milan']
                        }],

                        // Vertical axis
                        yAxis: [
                            {
                                type: 'value',
                                name: 'GDP（million)',
                                max: 53500
                            },
                            {
                                type: 'value',
                                name: 'Other（million)'
                            }
                        ],

                        // Add series
                        series: [
                            {
                                name: 'GDP',
                                type: 'bar',
                                markLine: {
                                    symbol: ['arrow','none'],
                                    symbolSize: [4, 2],
                                    itemStyle: {
                                        normal: {
                                            lineStyle: {color: 'orange'},
                                            barBorderColor: 'orange',
                                            label: {
                                                position: 'left',
                                                formatter: function(params) {
                                                    return Math.round(params.value);
                                                },
                                                textStyle: {color: 'orange'}
                                            }
                                        }
                                    },
                                    data: [{type: 'average', name: 'Average'}]
                                },
                                data: dataMap.dataGDP['2010']
                            },
                            {
                                name: 'Financial',
                                yAxisIndex: 1,
                                type: 'bar',
                                data: dataMap.dataFinancial['2010']
                            },
                            {
                                name: 'Real Estate',
                                yAxisIndex: 1,
                                type: 'bar',
                                data: dataMap.dataEstate['2010']
                            },
                            {
                                name: 'Primary industry',
                                yAxisIndex: 1,
                                type: 'bar',
                                data: dataMap.dataPI['2010']
                            },
                            {
                                name: 'Secondary industry',
                                yAxisIndex: 1,
                                type: 'bar',
                                data: dataMap.dataSI['2010']
                            },
                            {
                                name: 'Third industry',
                                yAxisIndex: 1,
                                type: 'bar',
                                data: dataMap.dataTI['2010']
                            }
                        ]
                    },

                    // 2011 data
                    {
                        series: [
                            {data: dataMap.dataGDP['2011']},
                            {data: dataMap.dataFinancial['2011']},
                            {data: dataMap.dataEstate['2011']},
                            {data: dataMap.dataPI['2011']},
                            {data: dataMap.dataSI['2011']},
                            {data: dataMap.dataTI['2011']}
                        ]
                    },

                    // 2012 data
                    {
                        series: [
                            {data: dataMap.dataGDP['2012']},
                            {data: dataMap.dataFinancial['2012']},
                            {data: dataMap.dataEstate['2012']},
                            {data: dataMap.dataPI['2012']},
                            {data: dataMap.dataSI['2012']},
                            {data: dataMap.dataTI['2012']}
                        ]
                    },

                    // 2013 data
                    {
                        series: [
                            {data: dataMap.dataGDP['2013']},
                            {data: dataMap.dataFinancial['2013']},
                            {data: dataMap.dataEstate['2013']},
                            {data: dataMap.dataPI['2013']},
                            {data: dataMap.dataSI['2013']},
                            {data: dataMap.dataTI['2013']}
                        ]
                    },

                    // 2014 data
                    {
                        series: [
                            {data: dataMap.dataGDP['2014']},
                            {data: dataMap.dataFinancial['2014']},
                            {data: dataMap.dataEstate['2014']},
                            {data: dataMap.dataPI['2014']},
                            {data: dataMap.dataSI['2014']},
                            {data: dataMap.dataTI['2014']}
                        ]
                    }
                ]
            };


            //
            // Stacked clustered columns options
            //

            stacked_clustered_columns_options = {

                // Setup grid
                grid: {
                    x: 65,
                    x2: 20,
                    y: 85,
                    y2: 25
                },

                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legends
                legend: {
                    data: [
                        'Version 1.7 - 2k data','Version 1.7 - 2w data','Version 1.7 - 20w data','',
                        'Version 2.0 - 2k data','Version 2.0 - 2w data','Version 2.0 - 20w data'
                    ]
                },

                // Enable drag recalculate
                calculable: true,

                // Horizontal axis
                xAxis: [
                    {
                        type: 'category',
                        data: ['Line','Bar','Scatter','Pies','Map']
                    },
                    {
                        type: 'category',
                        axisLine: {show:false},
                        axisTick: {show:false},
                        axisLabel: {show:false},
                        splitArea: {show:false},
                        splitLine: {show:false},
                        data: ['Line','Bar','Scatter','Pies','Map']
                    }
                ],

                // Vertical axis
                yAxis: [{
                    type: 'value',
                    axisLabel: {formatter:'{value} ms'},
                    axisLine: {
                        lineStyle: {
                            color: '#dc143c'
                        }
                    }
                }],

                // Add series
                series: [
                    {
                        name: 'Version 2.0 - 2k data',
                        type: 'bar',
                        itemStyle: {
                            normal: {
                                color: '#F44336',
                                label: {
                                    show: true,
                                    textStyle: {
                                        color: '#fff'
                                    }
                                }
                            },
                            emphasis: {
                                label: {
                                    show: true
                                }
                            }
                        },
                        data: [247, 187, 95, 175, 270]
                    },
                    {
                        name: 'Version 2.0 - 2w data',
                        type: 'bar',
                        itemStyle: {
                            normal: {
                                color: '#4CAF50',
                                label: {
                                    show: true,
                                    textStyle: {
                                        color: '#fff'
                                    }
                                }
                            },
                            emphasis: {
                                label: {
                                    show: true
                                }
                            }
                        },
                        data: [488, 415, 405, 340, 328]
                    },
                    {
                        name: 'Version 2.0 - 20w data',
                        type: 'bar',
                        itemStyle: {
                            normal: {
                                color: '#2196F3',
                                label: {
                                    show: true,
                                    textStyle: {
                                        color:'#fff'
                                    }
                                }
                            },
                            emphasis: {
                                label: {
                                    show: true
                                }
                            }
                        },
                        data: [906, 911, 908, 778, 550]
                    },
                    {
                        name: 'Version 1.7 - 2k data',
                        type: 'bar',
                        xAxisIndex: 1,
                        itemStyle: {
                            normal: {
                                color: '#E57373',
                                label: {
                                    show: true,
                                    formatter: function(p) {return p.value > 0 ? (p.value +'\n'):'';}
                                }
                            },
                            emphasis: {
                                label: {
                                    show: true
                                }
                            }
                        },
                        data: [680, 819, 564, 724, 890]
                    },
                    {
                        name: 'Version 1.7 - 2w data',
                        type: 'bar',
                        xAxisIndex: 1,
                        itemStyle: {
                            normal: {
                                color: '#81C784',
                                label: {
                                    show: true
                                }
                            },
                            emphasis: {
                                label: {
                                    show: true
                                }
                            }
                        },
                        data: [1212, 2035, 1620, 955, 1300]
                    },
                    {
                        name: 'Version 1.7 - 20w data',
                        type: 'bar',
                        xAxisIndex: 1,
                        itemStyle: {
                            normal: {
                                color: '#64B5F6',
                                label: {
                                    show: true,
                                    formatter: function(p){return p.value > 0 ? (p.value +'+'):'';}
                                }
                            },
                            emphasis: {
                                label: {
                                    show: false
                                }
                            }
                        },
                        data: [2200, 3000, 2500, 3000, 2000]
                    }
                ]
            };



            // Apply options
            // ------------------------------

            columns_timeline.setOption(columns_timeline_options);



            // Resize charts
            // ------------------------------

            window.onresize = function () {
                setTimeout(function () {
                    columns_timeline.resize();
                }, 200);
            }
        }
    );
});
