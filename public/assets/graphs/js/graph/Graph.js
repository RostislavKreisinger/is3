
window.start_time = new Array();

function wlog(object) {
    console.log("%c " + object, "color: green;");
}

function wlogTimeout(object) {
    console.log("%c " + object, "color: red;");
}

var GRAPHS_TYPE = {
    join: 'join',
    container: 'container',
    ico: 'ico',
    list: 'list',
    pie: 'pie',
    spline: 'spline',
    star: 'star',
    verticalbar: 'verticalbar',
    horizontalbar: 'horizontalbar',
    stackbar: 'stackbar',
    dailyReport: 'daily_report',
    table: 'table',
    dailyReportTable: 'daily_report_table',
    socialMedia: "social_media",
    tachometr: "tachometr",
    barGauge: "bar_gauge",
    map: "map",

    /**
     *
     * @returns {Array}
     */
    inArray: function (_value) {
        var result = false;
        $.each(GRAPHS_TYPE, function (key, value) {
            if (_value == value) {
                result = true;
                return;
            }
        });
        return result;
    }
};

var GRAPH_DX = new Array(GRAPHS_TYPE.pie, GRAPHS_TYPE.spline, GRAPHS_TYPE.verticalbar, GRAPHS_TYPE.horizontalbar, GRAPHS_TYPE.stackbar);
var GRAPH_SPECIAL = new Array(GRAPHS_TYPE.ico, GRAPHS_TYPE.list, GRAPHS_TYPE.star);



var GRAPH_DX_dxChart = new Array(GRAPHS_TYPE.spline, GRAPHS_TYPE.verticalbar, GRAPHS_TYPE.horizontalbar, GRAPHS_TYPE.stackbar);
var GRAPH_DX_dxPieChart = new Array(GRAPHS_TYPE.pie);
var GRAPH_DX_dxDataGrid = new Array(GRAPHS_TYPE.table, GRAPHS_TYPE.dailyReportTable);
var GRAPH_DX_dxCircularGauge = new Array(GRAPHS_TYPE.tachometr);
var GRAPH_DX_dxBarGauge = new Array(GRAPHS_TYPE.barGauge);
var GRAPH_DX_dxVectorMap = new Array(GRAPHS_TYPE.map);


/**
 * @class Graph
 * @param {int} id
 * @returns {Graph}
 */
function Graph(id, type, reloadEnable, detail) {

    this.uniqueId = id;
    id = id.replace(/[a-zA-Z0-9]+-/g,"");
    this.id = id;
    this.caption = "Graph " + id;
    this.type = type;
    this.isDxInit = false;
    this.detail = false;
    if (detail === true) {
        this.detail = true;
    }
    if (type !== GRAPHS_TYPE.join) {
        this.format = GraphFormat.getFormat(this.type);
    }
    this.graphContainer = $(".chart-" + type + ".chart-unique-" + this.uniqueId);

    this.graphContainer.data('graph', this);

    this.metrics = new Array();
    this.dimensions = new Array();

    this.reloadEnable = true;

    if (reloadEnable === false) {
        this.reloadEnable = false;
    }

    if (!this.detail) {
        this.getDxInit();
    }
    if (type !== GRAPHS_TYPE.join) {
        this.setEmptyView();
    }

    this.inReload = false;

    this.lastReloadedURL = null;
    this.currency = null;

    this.dxOnDoneSet = false;
    this.currentData = null;

    this.showLoading = true;

    this.graphAjaxSuccess = false;

    this.disabledLoadingFromApi = false;

    this.mdSelectDimensions = null;

    this.mdSelectMetrics = null;

    this.firstInit();
}

/**
 * runs at instance creation
 */
Graph.prototype.firstInit = function () {

    if(this.reloadEnable) {
        this.switchLoading();
    }

    var localgraph = this;
    this.initDownload();

    this.getGraphContainer().find('a.detail').on('click', function(e){
        if($(this).data("origin-href") === undefined){
            $(this).data("origin-href", this.href);
        }
        var url = $(this).data("origin-href");

        if(localgraph.metrics.length > 0){
            url += url.includes('?', false)?'&':'?';
            url += "metrics="+localgraph.metrics.join(";");
        }

        if(localgraph.dimensions.length > 0){
            url += url.includes('?', false)?'&':'?';
            url += "dimensions="+localgraph.dimensions.join(";");
        }
        this.href = url;
    });

    if(this.getGraphControlsDimensions().length || this.getGraphControlsMetrics().length){
        this.reloadEnable = false;
    }

    this.initiateInfoToolBox();

};

Graph.prototype.initDownload = function(){
    var localgraph = this;
    if (localgraph.isJoinGraph() === false) {
        var downloadlinkTXT = localgraph.getGraphContainer().find(".chart-top-menu").find('.chart-tool-options .chart-tool-options-link.download-txt');
        if (downloadlinkTXT.length) {
            downloadlinkTXT.on('click', function () {
                localgraph.downloadLikeTxt();
            });
        }

        var downloadlinkCSV = localgraph.getGraphContainer().find(".chart-top-menu").find('.chart-tool-options .chart-tool-options-link.download-csv');
        if (downloadlinkCSV.length) {
            downloadlinkCSV.on('click', function () {
                localgraph.downloadLikeCsv();
            });
        }
    }
}

/**
 * runs before first graph reload
 */
Graph.prototype.lateInit = function(){
    this.initControls();
}

Graph.prototype.isJoinGraph = function(){
    if (this.type == GRAPHS_TYPE.join){
        return true;
    }

    if (this.type == GRAPHS_TYPE.container){
        return true;
    }
    return false;
}

Graph.prototype.downloadLikeTxt = function(){
    vd("downloadLikeTxt");
    var localgraph = this;
    var name = localgraph.getGraphContainer().find(".chart-top-menu").find('.chart-caption h2').text().trim() + ".txt";
    if(localgraph.type == GRAPHS_TYPE.table) {
        localgraph.getDxInstance().selectAll();
    }
    window.setTimeout(function(){
        var data = localgraph.downloadGetData();
        var text = transformGraphDataToTXT(data);
        mdDownloadText(name, text);
    }, 100);
};

Graph.prototype.downloadLikeCsv = function(){
    vd("downloadLikeCsv");
    var localgraph = this;
    var name = localgraph.getGraphContainer().find(".chart-top-menu").find('.chart-caption h2').text().trim() + ".csv";
    if(localgraph.type == GRAPHS_TYPE.table) {
        localgraph.getDxInstance().selectAll();
    }
    window.setTimeout(function(){
        var data = localgraph.downloadGetData();
        var text = transformGraphDataToCSV(data);
        mdDownloadText(name, text);
    }, 100);
};

Graph.prototype.downloadGetData = function(){
    var localgraph = this;
    if(localgraph.type == GRAPHS_TYPE.table){
        var data = localgraph.getDxInstance().getSelectedRowsData();
        localgraph.getDxInstance().deselectAll();
        return data;
    }else {
        vd("downloadGetData", localgraph);
        return localgraph.currentData.data;
    }
};



/**
 * Get base container of graph
 * @returns {jQuery|$|@exp;window@pro;$|Window.$}
 */
Graph.prototype.getGraphContainer = function () {
    return this.graphContainer;
};


/**
 * Get base container of graph
 * @returns {jQuery|$|@exp;window@pro;$|Window.$}
 */
Graph.prototype.redraw = function () {
    var instance = this.getDxInstance();
    if( instance === null ){
        return;
    }
    if (GRAPH_DX_dxChart.inArray(this.type)) {
        instance.render();
        return;
    }
    if (GRAPH_DX_dxPieChart.inArray(this.type)) {
        instance.render();
        return;
    }
    if (GRAPH_DX_dxDataGrid.inArray(this.type)) {
        instance.resize();
        return;
    }
    if (GRAPH_DX_dxCircularGauge.inArray(this.type)) {
        instance.render();
        return;
    }
    if (GRAPH_DX_dxBarGauge.inArray(this.type)) {
        instance.render();
        return;
    }
    if (GRAPH_DX_dxVectorMap.inArray(this.type)) {
        instance.render();
        return;
    }
};

Graph.prototype.prepareParameters = function(){
    var params = Graphs.getAjaxParams();

    if (this.metrics.length) {
        params.metrics = this.metrics.join(';');
    }
    if (this.dimensions.length) {
        params.dimensions = this.dimensions.join(';');
    }

    if(this.currency !== null){
        params.currency = this.currency;
    }

    if (this.detail) {
        params.graph_type = this.type;
        params.detail = this.detail;
        if (Graphs.compare.length) {
            params.compare = Graphs.compare.join(";");
        }
    }

    if(Graphs.language !== null){
        params.language = Graphs.language;
    }

    if(Graphs.currentDate !== null){
        params.now = Graphs.currentDate;
    }

    return params;

}

Graph.prototype.getGraphControlsUrl = function(){
    return Graphs.getAjaxURL(this) + "/controls";
}


Graph.prototype.reload = function (strictReload) {

    var localGraph = this;

    if (!this.reloadEnable) {
        this.switchLoading(false);
        return;
    }

    if(Graphs.disabledLoadingFromApi === true) {
        if(localGraph.currentData !== null) {
            localGraph.ajaxSuccess(localGraph.currentData);
        }
        return;
    }

    this.inReload = true;

    window.start_time[localGraph.id] = Date.now();
    localGraph.graphAjaxSuccess = false;

    var params = this.prepareParameters();

    // TTL set to 3
    this.callApi(strictReload, params, 2);
};


Graph.prototype.initiateInfoToolBox = function () {
    var tooltipElement = this.getGraphContainer().find("#" + this.getUniqueId() + "-tooltip");
    if (tooltipElement.length) {
        tooltipElement.MDTooltip({
            vPosition: "bottom",
            hPosition: "left"
        });
    }

};


Graph.prototype.initControls = function () {
    var selectDimensions = this.getGraphControlsDimensions();
    var selectMetrics = this.getGraphControlsMetrics();
    var self = this;


    if (selectDimensions.length) {
        this.mdSelectDimensions = selectDimensions.MDSelectSearch({
            onInit: function () {
                self.setReloadEnabled();
                this.triggerOnChange();
            },
            onChange: function () {
                var searchSelf = this;

                var graphList = new Array();
                if(selectDimensions.data('chart') !== undefined) {
                    $.each(selectDimensions.data('chart').toString().split(';'), function (i, e) {
                        graphList.push($('#chart-' + e).data('graph'));
                    });
                }else{
                    graphList.push(self);
                }
                $.each(graphList, function(i, graph){
                    graph.dimensions = searchSelf.getSelectedOptionsObjects().map(function (a) {
                        return a.value;
                    });

                    graph.reload(true);
                });
            },
            onChangeTime: 1000
        });
    }

    if (selectMetrics.length) {
        self.mdSelectMetrics = selectMetrics.MDSelectSearch({
            onInit: function () {
                self.setReloadEnabled()
                this.triggerOnChange();
            },
            onChange: function () {
                var searchSelf = this;

                var graphList = new Array();
                if(selectMetrics.data('chart') !== undefined) {
                    $.each(selectMetrics.data('chart').toString().split(';'), function (i, e) {
                        graphList.push($('#chart-' + e).data('graph'));
                    });
                }else{
                    graphList.push(self);
                }
                $.each(graphList, function(i, graph){
                    graph.metrics = searchSelf.getSelectedOptionsObjects().map(function (a) {
                        return a.value;
                    });

                    graph.reload(true);
                });
            },
            onChangeTime: 1000
        });
    }
};

Graph.prototype.callApi = function (strictReload, params, ttl) {
    console.log("api call");
    var localGraph = this;
    this.initiatedHeadlineTooltips = false;
    var ajax = new MDAjax({
        url: Graphs.getAjaxURL(this),
        data: params,
        timeout: 30000,
        beforeSend: function (xhr) {
            if (localGraph.lastReloadedURL == this.url && strictReload !== true) {
                return false;
            } else {
                localGraph.switchLoading();
                localGraph.lastReloadedURL = this.url;
                setTimeout(function (url) {
                    if (!localGraph.graphAjaxSuccess) {
                        wlogTimeout(url + " - timeout");
                    }
                }, 10000, this.url);
            }
        },
        success: function (data) {
            localGraph.graphAjaxSuccess = true;
            if (window.jsDebug) {
                wlog(this.url + " - " + (Date.now() - window.start_time[localGraph.id]));
                vd(data);
                console.log(data);
            }
            localGraph.currentData = data.graphApiData;
            localGraph.ajaxSuccess(localGraph.currentData);
        },
        error: function (xhr, status, error) {
            console.log("fail");
            if(ttl > 0){
                setTimeout(function (strictReload, params, ttl) {
                    localGraph.callApi(true, params, ttl - 1);
                }, 1000, true, params, ttl - 1);
            } else {
                localGraph.ajaxFail();
            }
        },
        statusCode: {
            401: function () {
                Graphs.refreshToken();
            }
        },
    });
    Graphs.ajax.push(ajax);
};

Graph.prototype.ajaxSuccess = function (data) {
    this.format.ajaxSuccessGeneral(this, data);
    this.switchLoading(false);
    this.setInReload(false);
};

Graph.prototype.ajaxFail = function () {
    // this.setEmptyView();
    this.setInReload(false);
    this.switchLoading(false);
    this.switchFail(true);
};

Graph.prototype.setCurrency = function (currency) {
    this.currency = currency;
};


Graph.prototype.setEmptyView = function () {
    this.format.emptyGraph(this);
    this.switchLoading(false);
};


Graph.prototype.switchLoading = function (setOn) {
    if (setOn === false) {
        this.getGraphContainer().removeClass('chart-loading');
    } else {
        if(this.showLoading){
            this.getGraphContainer().addClass('chart-loading');
        }
        this.switchFail(false);
    }
};

Graph.prototype.switchFail = function (setOn) {
    if (setOn === false) {
        this.getGraphContainer().removeClass('chart-fail');
    } else {
        this.getGraphContainer().addClass('chart-fail');
    }
};



Graph.prototype.getGraphMenu = function () {
    return this.getGraphContainer().find("> .chart-top-menu");
};

Graph.prototype.getGraphControlsDimensions = function () {
    var selector = ".graph-select-search-control-dimensions";
    if (this.detail) {
        return $(selector);
    }
    return  this.getGraphMenu().find(selector);
};

Graph.prototype.getGraphControlsMetrics = function () {
    var selector = ".graph-select-search-control-metrics";
    if (this.detail) {
        return $(selector);
    }
    return  this.getGraphMenu().find(selector);
};






Graph.prototype.getGraphLabel = function () {
    return this.getGraphContainer().find(".chart-label-box");
};

Graph.prototype.setGraphLabel = function (value, sign) {
    var label = this.getGraphLabel();
    if (value != undefined) {
        label.find('.chart-label-value').html(mdFormat.number(value));
    }
    if (sign != undefined) {
        label.find('.chart-label-sign').html(sign);
    }
};


Graph.prototype.switchGraphLabel = function (setOn) {
    if (setOn === false) {
        this.getGraphLabel().hide();
    } else {
        this.getGraphLabel().show();
    }
};


Graph.prototype.getGraphEmpty = function () {
    return this.getGraphContainer().find(".chart-empty-box");
};

Graph.prototype.switchGraphEmpty = function (setOn) {
    if (setOn === false) {
        this.getGraphEmpty().hide();
    } else {
        this.getGraphEmpty().show();
    }
};


Graph.prototype.getGraphValue = function () {
    return this.getGraphContainer().find(".chart-value-box");
};

Graph.prototype.switchGraphValue = function (setOn) {
    if (setOn === false) {
        this.getGraphValue().hide();
    } else {
        this.getGraphValue().show();
    }
};



Graph.prototype.getGraphCompare = function () {
    return this.getGraphContainer().find(".chart-compare-box");
};

Graph.prototype.fillGraphCompare = function (selector, periodData, expected) {
    var periodElement = this.getGraphCompare().find(selector);
    var empty = "--";
    if(expected === true){
        empty = "";
    }
    var emptyDate = false;


    if (!periodData.isSet) {
        periodData.label = empty;
        periodData.percent = empty;
        if(expected === true){
            emptyDate = true;
        }

    }

    periodElement.find('.period-info .period-name').html(periodData.name);

    var from = mdDate.clone().setDateTimestamp(periodData.from * 1000).getFormatDate();
    var to = mdDate.clone().setDateTimestamp(periodData.to * 1000).getFormatDate();
    if(!emptyDate){
        periodElement.find('.period-info .period-date-range').html(from + " - " + to);
    }else{
        periodElement.find('.period-info .period-date-range').html("");
    }

    periodElement.find('.period-value-box .period-value').html(mdFormat.number(periodData.label, empty));

    var periodPercent = periodElement.find('.period-value-box .period-percent');
    periodPercent.html(mdFormat.value(periodData.percent, '%', empty));
    periodPercent.removeClass('positive negative');
    if (periodData.percent < 0) {
        periodPercent.addClass('negative');
    } else {
        periodPercent.addClass('positive');
    }

    return true;
};


Graph.prototype.switchGraphCompare = function (setOn) {
    if (setOn === false) {
        this.getGraphContainer().removeClass('show-compare').addClass('hide-compare');
        // this.getGraphCompare().hide();
    } else {
        this.getGraphContainer().removeClass('hide-compare').addClass('show-compare');
        // this.getGraphCompare().show();
    }
};


Graph.prototype.getGraphTemplate = function (selector) {
    if (selector === undefined) {
        selector = ".graph-template-" + this.id;
    }
    return this.getGraphContainer().find(selector);
};


Graph.prototype.getGraphValueContent = function () {
    return this.getGraphValue().find(".chart-value-box-content");
};


Graph.prototype.getDxInstance = function (init) {
    var dxChartBox = this.getGraphValueContent();

    var param = "instance";
    if (init === true) {
        param = {dataSource: []};
    }

    if (GRAPH_DX_dxChart.inArray(this.type)) {
        return dxChartBox.dxChart(param);
    }

    if (GRAPH_DX_dxPieChart.inArray(this.type)) {
        return dxChartBox.dxPieChart(param);
    }

    if (GRAPH_DX_dxDataGrid.inArray(this.type)) {
        return dxChartBox.dxDataGrid(param);
    }

    if (GRAPH_DX_dxCircularGauge.inArray(this.type)) {
        return dxChartBox.dxCircularGauge(param);
    }

    if (GRAPH_DX_dxBarGauge.inArray(this.type)) {
        return dxChartBox.dxBarGauge(param);
    }

    if (GRAPH_DX_dxVectorMap.inArray(this.type)) {
        return dxChartBox.dxVectorMap(param);
    }

    if (init === true && !GRAPHS_TYPE.inArray(this.type)) {
        // console.warn("Uknow graph type name: " + this.type);
    } else {
        return null;
    }
};

Graph.prototype.setInReload = function (value) {
    this.inReload = value;
    if (!value) {
        var finish = true;
        Graphs.array.each(function (index, graph) {
            if (graph.inReload) {
                finish = false;
            }
        });
        if (finish) {
            if (typeof Graphs.onReloadAll === "function") {
                Graphs.onReloadAll();
            }
        }
    }
}

Graph.prototype.getDxInit = function () {
    var init = false;
    if (this.isDxInit !== true) {
        init = true;
        this.isDxInit = true;
    }
    return this.getDxInstance(init);
};


Graph.prototype.setShowLoading = function (show) {
    this.showLoading = show;
};

Graph.prototype.setDataFromApiAndDisableApiCall = function (data) {
    this.disabledLoadingFromApi = true;
    this.currentData = data;
};

Graph.prototype.setReloadEnabled = function (reloadEnable) {
    if(isEmpty(reloadEnable)){
        reloadEnable = true;
    }
    if(this.isJoinGraph()){
        reloadEnable = false;
    }
    this.reloadEnable = reloadEnable;
};

Graph.prototype.getId = function () {
    return this.id
};

Graph.prototype.getUniqueId = function () {
    return this.uniqueId;
};



// HELPERS


function transformGraphDataToTXT(data) {
    var text = "";
    for (var key in data) {
        if (data.hasOwnProperty(key)) {
            var obj = data[key];
            for (var prop in obj) {
                if (obj.hasOwnProperty(prop)) {
                    if (obj[prop] > 1000000000000) {
                        var val = mdDate.clone().setDateTimestamp(obj[prop]).getFormatDate();
                        text += prop + ":" + val + "; ";
                    } else {
                        text += prop + ":" + obj[prop] + "; ";
                    }
                }
            }

            text += "\n";
        }
    }
    return text;
}

function transformGraphDataToCSV(data) {
    var text = "";
    var first = true;
    for (var key in data) {
        if (first) {
            if (data.hasOwnProperty(key)) {
                var obj = data[key];
                for (var prop in obj) {
                    if (obj.hasOwnProperty(prop)) {
                        text += prop + ";";
                    }
                }
            }
            text += "\n";
            first = false;
        }

        if (data.hasOwnProperty(key)) {
            var obj = data[key];
            for (var prop in obj) {
                if (obj.hasOwnProperty(prop)) {
                    if (obj[prop] > 1000000000000) {
                        var val = mdDate.clone().setDateTimestamp(obj[prop]).getFormatDate();
                        text += val + ";";
                    } else {
                        text += obj[prop] + ";";
                    }
                }
            }
            text += "\n";
        }
    }
    return text;
}