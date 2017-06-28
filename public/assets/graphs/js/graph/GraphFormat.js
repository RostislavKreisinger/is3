
DevExpress.ui.setTemplateEngine('underscore');

window.mapData = {};

function GraphFormatException(message) {
    this.message = message;
    this.name = "GraphFormatException";
}

GraphFormatException.prototype.toString = function () {
    return this.name + " : " + this.message;
}




/**
 * 
 * @param {Graph} graph
 * @param {type} options
 * @returns {Array|Function|normalyzeOptions.options}
 */
function normalyzeOptions(graph, options) {
    if (typeof options === 'string') {
        if (options.includes("fnc:")) {
            var fcn = options.replace("fnc:", "");
            return new Function('arg0', 'arg1', 'arg2', 'arg3', 'arg4', fcn);
        }

        if (options.includes("eval:")) {
            var fcn = options.replace("eval:", "");
            return eval(fcn);
        }
        

        if (options.includes("selector:")) {
            var fcn = options.replace("selector:", "");
            return graph.getGraphContainer().find(fcn);
        }
        return options;
    }
    
    if (typeof options === 'number') {
        return options;
    }

    var finalOptions = {};
    for (var name in options) {
//        vd(name + ":: " + options[name]);
//        vd(options[name]);

        if (typeof options[name] === 'string') {
//            vd('string recursion');
            finalOptions[name] = normalyzeOptions(graph, options[name]);
            continue;
        }

        if (Array.isArray(options[name])) {
            // vd(name);
            finalOptions[name] = new Array();
            $.each(options[name], function (index, value) {
//                vd('array recursion');
                // finalOptions[name][index] = normalyzeOptions(graph, value);
                //vd(value);
                finalOptions[name].push(normalyzeOptions(graph, value));
            });
            continue;
        }

        if (typeof options[name] === 'object') {
//            vd('object recursion');
            finalOptions[name] = normalyzeOptions(graph, options[name]);
            continue;
        }
//        vd('property');
        finalOptions[name] = options[name];

    }
//    vd('return from function');
    return finalOptions;
}

function getGraphButton(text, background) {
    var $button = $('<a></a>');
    var $sign = $('<span></span>').addClass('graph-button-sign');
    var $text = $('<span></span>').addClass('graph-button-label');
    $button.append($sign, $text);

    $text.text(text);

    // $button.css('background', background);
    // $button.css('color', getTextColorByBackground(background));
    $sign.css('background', background);
    return $button;
}

function dxButtons(arg0, arg1) {
    var valueBox = $(arg0.element[0]).closest('.chart-value-box'); // .addClass('chart-with-buttons');
    var buttons = valueBox.find('.chart-value-box-content-buttons');
    buttons.html('');
    $.each(arg0.component.series, function (si, serie) {
        var point = serie;

        var button = getGraphButton(point.name, point.getColor());
        button.data('point', point);
        button.click(function () {
            if ($(this).data('point').isVisible()) {
                $(this).addClass('graph-button-hidden');
                $(this).data('point').hide();
            } else {
                $(this).removeClass('graph-button-hidden');
                $(this).data('point').show();
            }
        });
        button.hover(function () {
            $(this).data('point').select();
        }, function () {
            $(this).data('point').clearSelection();
        });
        buttons.append(button);
    });
}







var GraphFormat = {
    /**
     * 
     * @param {string} type
     * @returns {BaseFormat}
     */
    getFormat: function (type) {
        var camelType = type.replace(/(\_\w)/g, function (m) {
            return m[1].toUpperCase();
        });
        camelType += 'Format';
        camelType = camelType.charAt(0).toUpperCase() + camelType.slice(1);
        try {
            // vd(camelType);
            return new window[camelType];
        } catch (exception) {
            console.error("GraphFormat: Class " + camelType + " not found!");
        }
        return new BaseFormat();
    }
}





function BaseFormat() {
}

/**
 * 
 * @param {Graph} graph
 * @returns {none}
 */
BaseFormat.prototype.emptyGraph = function (graph) {
    var switchValue = false;
    graph.switchGraphValue(switchValue);
    graph.switchGraphCompare(switchValue);
    graph.switchGraphLabel(switchValue);
    graph.switchGraphEmpty(!switchValue);
    graph.getGraphContainer().addClass('chart-empty');

}

BaseFormat.prototype.prepareGraphView = function (graph) {
    var switchValue = true;
    graph.switchGraphValue(switchValue);
    graph.switchGraphCompare(switchValue);
    graph.switchGraphLabel(switchValue);
    graph.switchGraphEmpty(!switchValue);
    graph.getGraphContainer().removeClass('chart-empty');
    
    if(!graph.isDxInit){
        graph.getDxInit();
    }
}

/**
 * 
 * @param {Graph} graph
 * @param {Object} data
 * @returns {undefined}
 */
BaseFormat.prototype.detailAjaxSuccess = function (graph, data) {
    if(graph.getGraphCompare().length > 0) {
        var lastPeriod = graph.fillGraphCompare('.chart-comapre-last-period', data.periods.last_period);
        var lastYear = graph.fillGraphCompare('.chart-comapre-last-year', data.periods.last_year);
        var expacted = graph.fillGraphCompare('.chart-comapre-expected', data.periods.expected, true);

        console.log(data.extras.is_custom_period);
        console.log(!(lastPeriod || lastYear || expacted));

        if (!(lastPeriod || lastYear || expacted) || data.extras.is_custom_period) {
            graph.switchGraphCompare(false);
//        graph.getGraphCompare().hide();
//        graph.getGraphContainer().removeClass('show-compare');
        } else {
            graph.switchGraphCompare(true);
//        graph.getGraphContainer().addClass('show-compare');
        }
    }else{
        graph.switchGraphCompare(false);
    }

    $(".detail-chart-agregation-content-li").removeClass('selected');
    $(".detail-chart-agregation-content-li[data-groupby='"+data.extras.group_by+"']").addClass('selected');
}

/**
 * 
 * @param {Graph} graph
 * @param {Object} format
 * @returns {undefined}
 */
BaseFormat.prototype.useFormatOptions = function (graph, format) {
    if (!$.isEmptyObject(format.htmlOptions)) {
        if (!$.isEmptyObject(format.htmlOptions.height)) {
            graph.getGraphValueContent().height(format.htmlOptions.height);
        }
    } else {
        graph.getGraphValueContent().height('');
    }
}

BaseFormat.prototype.ajaxSuccessGeneral = function (graph, data) {
    var isEmpty = true;
    if(data.extras.is_empty === null){
        isEmpty = !(data.data.length > 0);
    }else{
        isEmpty = data.extras.is_empty;
    }

    if (!isEmpty) {
        this.prepareGraphView(graph);
        this.useFormatOptions(graph, data.format);
        this.ajaxSuccess(graph, data);
        if(graph.detail === true){
            this.detailAjaxSuccess(graph, data);
        }
    }else {
        this.emptyGraph(graph);
    }
}

/**
 * 
 * @param {Graph} graph
 * @param {object} Data from ajax calling
 * @returns {undefined}
 */
BaseFormat.prototype.ajaxSuccess = function (graph, data) {
    console.warn('BaseFormat.ajaxSuccess must be overwrite: ' + graph.type);
}





function JoinFormat() {
    BaseFormat.call(this);
}
JoinFormat.prototype = new BaseFormat();
JoinFormat.prototype.constructor = JoinFormat;

/**
 * 
 * @param {Graph} graph
 * @param {object} Data from ajax calling
 * @returns {undefined}
 */
JoinFormat.prototype.ajaxSuccess = function (graph, data) {
}

/**
 * 
 * @param {Graph} graph
 * @param {type} data
 * @returns {undefined}
 */
JoinFormat.prototype.setLabel = function (graph, data) {
}




function ContainerFormat() {
    BaseFormat.call(this);
}
ContainerFormat.prototype = new BaseFormat();
ContainerFormat.prototype.constructor = ContainerFormat;

/**
 *
 * @param {Graph} graph
 * @param {object} Data from ajax calling
 * @returns {undefined}
 */
ContainerFormat.prototype.ajaxSuccess = function (graph, data) {
}

/**
 *
 * @param {Graph} graph
 * @param {type} data
 * @returns {undefined}
 */
ContainerFormat.prototype.setLabel = function (graph, data) {
}


























function DxFormat() {
    BaseFormat.call(this);
}
DxFormat.prototype = new BaseFormat();
DxFormat.prototype.constructor = DxFormat;

/**
 * 
 * @param {Graph} graph
 * @param {object} Data from ajax calling
 * @returns {undefined}
 */
DxFormat.prototype.ajaxSuccess = function (graph, data) {

    this.setLabel(graph, data);

    var dxInstance = graph.getDxInstance();

    if(graph.dxOnDoneSet === false){
        graph.dxOnDoneSet = true;
        this.setOnDoneEvent(graph, data);
    }


    var finalOption = normalyzeOptions(graph, data.format.options);
    
    dxInstance.option(finalOption);
    
    dxInstance.option('animation', Graphs.allowAnimation);

    // vd(data.data)
    dxInstance.option('dataSource', data.data);
}

/**
 * 
 * @param {Graph} graph
 * @param {type} data
 * @returns {undefined}
 */
DxFormat.prototype.setLabel = function (graph, data) {
    if (data.periods.current.isSet) {
        graph.switchGraphLabel(true);
        graph.setGraphLabel(data.periods.current.label);
    } else {
        graph.switchGraphLabel(false);
    }
}


/**
 * 
 * @param {Graph} graph
 */
DxFormat.prototype.setOnDoneEvent = function (graph, data) {}






















function IcoFormat() {
    BaseFormat.call(this);

}
IcoFormat.prototype = new BaseFormat();
IcoFormat.prototype.constructor = IcoFormat;


/**
 * 
 * @param {Graph} graph
 * @param {type} data
 * @returns {undefined}
 */
IcoFormat.prototype.ajaxSuccess = function (graph, data) {

    var html = tmpl(graph.getGraphTemplate('.graph-template').html(), data);
    graph.getGraphValueContent().html(html);

};









function IcoHeurekaFormat() {
    BaseFormat.call(this);

}
IcoHeurekaFormat.prototype = new BaseFormat();
IcoHeurekaFormat.prototype.constructor = IcoHeurekaFormat;


/**
 *
 * @param {Graph} graph
 * @param {type} data
 * @returns {undefined}
 */
IcoHeurekaFormat.prototype.ajaxSuccess = function (graph, data) {
    graph.getGraphValueContent().html( mdFormat.value(data.data[0].value, "%") );
    graph.getGraphContainer().find('.chart-value-box-content-all').html( mdFormat.value(data.data[0].all, "%"));
}















function TableFormat() {
    DxFormat.call(this);

}
TableFormat.prototype = new DxFormat();
TableFormat.prototype.constructor = TableFormat;

/**
 *
 * @param {Graph} graph
 * @param {object} Data from ajax calling
 * @returns {undefined}
 */
TableFormat.prototype.ajaxSuccess = function (graph, data) {

    this.setLabel(graph, data);

    var dxInstance = graph.getDxInstance();

    var finalOption = normalyzeOptions(graph, data.format.options);

    dxInstance.option(finalOption);
    dxInstance.option('dataSource', data.data);


}








function DailyReportTableFormat() {
    DxFormat.call(this);

}
DailyReportTableFormat.prototype = new DxFormat();
DailyReportTableFormat.prototype.constructor = DailyReportTableFormat;

/**
 *
 * @param {Graph} graph
 * @param {object} Data from ajax calling
 * @returns {undefined}
 */
DailyReportTableFormat.prototype.ajaxSuccess = function (graph, data) {

    this.setLabel(graph, data);

    var dxInstance = graph.getDxInstance();

    var finalOption = normalyzeOptions(graph, data.format.options);

    dxInstance.option(finalOption);
    dxInstance.option('dataSource', data.data);
}
























function DailyReportFormat() {
    BaseFormat.call(this);

}
DailyReportFormat.prototype = new BaseFormat();
DailyReportFormat.prototype.constructor = DailyReportFormat;


/**
 *
 * @param {Graph} graph
 * @param {type} data
 * @returns {undefined}
 */
DailyReportFormat.prototype.ajaxSuccess = function (graph, data) {
    var html = tmpl(graph.getGraphTemplate().html(), data);
    graph.getGraphValueContent().html(html);
    graph.initiateInfoToolBox();
    graph.initDownload();
    // console.log(tmpl(graph.getGraphTemplate().html(), data));
}





















function PieFormat() {
    DxFormat.call(this);

}
PieFormat.prototype = new DxFormat();
PieFormat.prototype.constructor = PieFormat;

PieFormat.prototype.ajaxSuccess = function (graph, data) {
    DxFormat.prototype.ajaxSuccess.call(this, graph, data);
}


/**
 * 
 * @param {Graph} graph
 */
PieFormat.prototype.setOnDoneEvent = function (graph, data) {
    if (graph.getGraphValue().find('.chart-value-box-content-buttons').length) {
        graph.getDxInstance().on('done', function (arg0, arg1) {
            var buttons = $(arg0.element[0]).closest('.chart-value-box').find('.chart-value-box-content-buttons');
            buttons.html('');
            $.each(arg0.component.series, function (si, serie) {
                $.each(serie.pointsByArgument, function (i, e) {
                    var point = e[0];
                    var visiblePoint = true;
                    if (point._styles === null) {
                        visiblePoint = false;
                    }
                    var button = getGraphButton(point.originalArgument, point.getColor());
                    // var button = $('<a></a>');
                    // var sign = $('<span></span>').addClass('graph-button-sign');
                    // var text = $('<span></span>').addClass('graph-button-label');
                    // button.append(sign, text);
                    //
                    // text.text(point.originalArgument);
                    // button.text(point.originalArgument);
                    button.data('point', point);
                    if (visiblePoint) {
                        button.click(function () {
                            if ($(this).data('point').isVisible() || !visiblePoint) {
                                $(this).addClass('graph-button-hidden');
                                $(this).data('point').hide();
                            } else {
                                $(this).removeClass('graph-button-hidden');
                                $(this).data('point').show();
                            }
                        });
                        button.hover(function () {
                            $(this).data('point').select();
                        }, function () {
                            $(this).data('point').clearSelection();
                        });
                        // var background = point.getColor();
                        // button.css('background', background);
                        // button.css('color', getTextColorByBackground(background));
                    } else {
                        button.addClass('graph-button-hidden');
                    }
                    buttons.append(button);
                });
            });
        });

        // drawn
        graph.getDxInstance().on('drawn', function (arg0, arg1) {
            $.each(arg0.component.series, function (si, serie) {
                $.each(serie.pointsByArgument, function (i, e) {
                    var point = e[0];
                    if (point._label !== null) {
                        point._label._text._$element.css('fill', point.getColor());
                    }
                });
            });
        });

    }
}























function SplineFormat() {
    DxFormat.call(this);

}
SplineFormat.prototype = new DxFormat();
SplineFormat.prototype.constructor = SplineFormat;

/**
 * 
 * @param {Graph} graph
 * @param {object} Data from ajax calling
 * @returns {undefined}
 */
SplineFormat.prototype.ajaxSuccess = function (graph, data) {
    DxFormat.prototype.ajaxSuccess.call(this, graph, data);

    // this.setCompareLabels(graph, data);
}

/**
 * 
 * @param {Graph} graph
 */
SplineFormat.prototype.setOnDoneEvent = function (graph, data) {
    if (graph.getGraphValue().find('.chart-value-box-content-buttons').length) {
        graph.dxOnDoneSet = true;
        graph.getDxInstance().on('done', dxButtons);
    }
}



/**
 * 
 * @param {Graph} graph
 * @param {object} Data from ajax calling
 * @returns {undefined}
 */
SplineFormat.prototype.setCompareLabels = function (graph, data) {
    var emptyCompare = {value: '--', percent: '--', sign: string(data.sign), periodName: '--', periodRange: '', visible: false}
    if (data.periods.last_period.isSet) {
        var last_period = {
            value: string(data.periods.last_period.label),
            percent: string(data.periods.last_period.percent),
            sign: string(data.sign),
            periodName: string(data.periods.last_period.name),
            periodRange: string(data.periods.last_period.from + ' - ' + data.periods.last_period.to)
        };
        graph.getGraphCompare().find(".chart-compare-last-period").wCompareStatus(last_period);
    } else {
        graph.getGraphCompare().find(".chart-compare-last-period").wCompareStatus(emptyCompare);
    }


    if (data.periods.last_year.isSet) {
        var last_year = {
            value: string(data.periods.last_year.label),
            percent: string(data.periods.last_year.percent),
            sign: string(data.sign),
            periodName: string(data.periods.last_year.name),
            periodRange: string(data.periods.last_year.from + ' - ' + data.periods.last_year.to)
        };
        graph.getGraphCompare().find(".chart-compare-last-year-period").wCompareStatus(last_year);
    } else {
        graph.getGraphCompare().find(".chart-compare-last-year-period").wCompareStatus(emptyCompare);
    }
}












function StackbarFormat() {
    DxFormat.call(this);

}
StackbarFormat.prototype = new DxFormat();
StackbarFormat.prototype.constructor = StackbarFormat;

/**
 * 
 * @param {Graph} graph
 * @param {object} Data from ajax calling
 * @returns {undefined}
 */
StackbarFormat.prototype.ajaxSuccess = function (graph, data) {
    DxFormat.prototype.ajaxSuccess.call(this, graph, data);

    // this.setCompareLabels(graph, data);
}


/**
 * 
 * @param {Graph} graph
 */
StackbarFormat.prototype.setOnDoneEvent = function (graph, data) {
    if (graph.getGraphValue().find('.chart-value-box-content-buttons').length) {
        graph.dxOnDoneSet = true;
        graph.getDxInstance().on('done', function (arg0, arg1) {

            var buttons = $(arg0.element[0]).closest('.chart-value-box').find('.chart-value-box-content-buttons');
            buttons.html('');
            $.each(arg0.component.series, function (si, serie) {
                var point = serie;
                var button = getGraphButton(point.name, point.getColor());
                // var button = $('<a></a>');
                // var sign = $('<span></span>').addClass('graph-button-sign');
                // var text = $('<span></span>').addClass('graph-button-label');
                // button.append(sign, text);
                //
                // text.text(point.name);
                button.data('point', point);
                button.click(function () {
                    if ($(this).data('point').isVisible()) {
                        $(this).addClass('graph-button-hidden');
                        $(this).data('point').hide();
                    } else {
                        $(this).removeClass('graph-button-hidden');
                        $(this).data('point').show();
                    }
                    var visibleSerieCount = new Array();
                    var option = {
                        commonSeriesSettings:{
                            type:graph.getDxInstance().option().commonSeriesSettings.type,
                        },
                        series: graph.getDxInstance().option().series,
                    };
                    
                    $.each(graph.getDxInstance().getAllSeries(), function(index, serieItem){
                        option.series[index].visible = false;
                        if(serieItem.isVisible()){
                            option.series[index].visible = true;
                            visibleSerieCount.push(index);
                        }
                    });
                    
                    
                    var newSeriesType = 'bar';
                    if(visibleSerieCount.length > 1){
                        newSeriesType = 'fullStackedBar';
                    }
                    if(newSeriesType != option.commonSeriesSettings.type){
                        option.commonSeriesSettings.type = newSeriesType;
                        graph.getDxInstance().option(option);                  
                    }
                    // graph.getDxInstance().render();
                    
                });
                button.hover(function () {
                    $(this).data('point').select();
                }, function () {
                    $(this).data('point').clearSelection();
                });
                // var background = point.getColor();
                if(!point.isVisible()){
                    button.addClass('graph-button-hidden');
                }
                // button.css('background', background);
                // button.css('color', getTextColorByBackground(background));
                buttons.append(button);
            });
        });
    }
}













function StarFormat() {
    BaseFormat.call(this);

    this.test = 1;
}
StarFormat.prototype = new BaseFormat();
StarFormat.prototype.constructor = StarFormat;

/**
 * 
 * @param {Graph} graph
 * @returns {undefined}
 */
StarFormat.prototype.emptyGraph = function (graph) {
    graph.getGraphValue().find('.graph-star-value-label-percent').html('--');
}

























function VerticalbarFormat() {
    DxFormat.call(this);

}
VerticalbarFormat.prototype = new DxFormat();
VerticalbarFormat.prototype.constructor = VerticalbarFormat;

/**
 * 
 * @param {Graph} graph
 * @param {object} Data from ajax calling
 * @returns {undefined}
 */
VerticalbarFormat.prototype.ajaxSuccess = function (graph, data) {
    DxFormat.prototype.ajaxSuccess.call(this, graph, data);

    // this.setCompareLabels(graph, data);
}



/**
 * 
 * @param {Graph} graph
 */
VerticalbarFormat.prototype.setOnDoneEvent = function (graph, data) {
    if (graph.getGraphValue().find('.chart-value-box-content-buttons').length) {
        graph.dxOnDoneSet = true;
        graph.getDxInstance().on('done', dxButtons);
    }
}
















function HorizontalbarFormat() {
    DxFormat.call(this);

}
HorizontalbarFormat.prototype = new DxFormat();
HorizontalbarFormat.prototype.constructor = HorizontalbarFormat;


/**
 * 
 * @param {Graph} graph
 * @param {object} Data from ajax calling
 * @returns {undefined}
 */
HorizontalbarFormat.prototype.ajaxSuccess = function (graph, data) {
    
    // var graph = dxChartBox.dxChart(param);
            

    DxFormat.prototype.ajaxSuccess.call(this, graph, data);

    // this.setCompareLabels(graph, data);
}


/**
 * 
 * @param {Graph} graph
 */
HorizontalbarFormat.prototype.setOnDoneEvent = function (graph, data) {
    if (graph.getGraphValue().find('.chart-value-box-content-buttons').length) {
        graph.dxOnDoneSet = true;
        graph.getDxInstance().on('done', dxButtons);
    }
}






function MapFormat() {
    DxFormat.call(this);

}
MapFormat.prototype = new DxFormat();
MapFormat.prototype.constructor = MapFormat;


/**
 * 
 * @param {Graph} graph
 * @param {object} Data from ajax calling
 * @returns {undefined}
 */
MapFormat.prototype.ajaxSuccess = function (graph, data) {

    // DxFormat.prototype.ajaxSuccess.call(this, graph, data);
    this.setLabel(graph, data);

    graph.getGraphValueContent().remove();
    graph.getGraphValue().prepend('<div class="chart-value-box-content"></div>');
    graph.getDxInstance(true);
    var dxInstance = graph.getDxInstance();

    if (graph.dxOnDoneSet === false) {
        graph.dxOnDoneSet = true;
        this.setOnDoneEvent(graph, data);
    }

    dxInstance.on({
        centerChanged: function (map) {
            graph.mapCenter = map.center;
        },
        zoomFactorChanged: function (map) {
            graph.mapZoomFactor = map.zoomFactor;
        }
    });


    var finalOption = normalyzeOptions(graph, data.format.options);

    window.mapData[graph.getId()] = data;

    if (graph.mapCenter != undefined) {
        finalOption.center = graph.mapCenter;
    }

    if (graph.mapZoomFactor != undefined) {
        finalOption.zoomFactor = graph.mapZoomFactor;
    }

    dxInstance.option('animation', Graphs.allowAnimation);
    dxInstance.option(finalOption);
}
 

/**
 * 
 * @param {Graph} graph
 */
MapFormat.prototype.setOnDoneEvent = function (graph, data) {
    if (false && graph.getGraphValue().find('.chart-value-box-content-buttons').length) {
        graph.dxOnDoneSet = true;
        graph.getDxInstance().on('done', dxButtons);
    }
}













function TachometrFormat() {
    DxFormat.call(this);
    
}
TachometrFormat.prototype = new DxFormat();
TachometrFormat.prototype.constructor = TachometrFormat;


/**
 * 
 * @param {Graph} graph
 * @param {object} Data from ajax calling
 * @returns {undefined}
 */
TachometrFormat.prototype.ajaxSuccess = function (graph, data) {

    this.setLabel(graph, data);

    var dxInstance = graph.getDxInstance();
    dxInstance.__subvalues = new Array();

    vd(data.format.options, "data.format.options");
    var finalOption = normalyzeOptions(graph, data.format.options);
    vd(finalOption, "finalOption");
    dxInstance.option(finalOption);


    var value = mdFormat.number(data.data[0].value, data.extras.sign);
    if(data.format.htmlOptions.is_currency){
        value = mdFormat.value(data.data[0].value, data.extras.sign);
    }
    graph.getGraphValue().find(".chart-value-box-label").html(value);
    
    graph.getGraphContainer().find(".chart-value-box-all .chart-value-box-content-all").html(mdFormat.value(data.data[0].all, data.extras.sign));
    
    if(finalOption.subvalues !== undefined){
        dxInstance.subvalues(finalOption.subvalues);
    }
    
    if(data.data[0] !== undefined && data.data[0].value !== undefined ){
        dxInstance.option('value', data.data[0].value);
    }
}

/**
 * 
 * @param {Graph} graph
 */
TachometrFormat.prototype.setOnDoneEvent = function (graph, data) {}





function BarGaugeFormat() {
    DxFormat.call(this);
    
}
BarGaugeFormat.prototype = new DxFormat();
BarGaugeFormat.prototype.constructor = BarGaugeFormat;


/**
 * 
 * @param {Graph} graph
 * @param {object} Data from ajax calling
 * @returns {undefined}
 */
BarGaugeFormat.prototype.ajaxSuccess = function (graph, data) {
    this.setLabel(graph, data);

    var dxInstance = graph.getDxInstance();
    
    this.addButtons(graph, data);
    
    var finalOption = normalyzeOptions(graph, data.format.options);
    dxInstance.option(finalOption);
}

/**
 * 
 * @param {Graph} graph
 */
BarGaugeFormat.prototype.setOnDoneEvent = function (graph, data) {
    
}



/**
 * 
 * @param {Graph} graph
 */
BarGaugeFormat.prototype.addButtons = function (graph, data) {
    if (graph.getGraphValue().find('.chart-value-box-content-buttons').length) {
        var buttons = graph.getGraphValue().find('.chart-value-box-content-buttons');
        buttons.html("");
        $.each(data.data, function (i, row) {

            var button = getGraphButton(row.category, data.format.options.palette[i]);
            // var button = $('<a></a>');
            // var sign = $('<span></span>').addClass('graph-button-sign');
            // var text = $('<span></span>').addClass('graph-button-label');
            // button.append(sign, text);

            // text.text(row.category);

            // var background = data.format.options.palette[i];//point.getColor();
            // button.css('background', background);
            // button.css('color', getTextColorByBackground(background));
            buttons.append(button);
        });
    }
}








function FunnelFormat() {
    BaseFormat.call(this);
}
FunnelFormat.prototype = new BaseFormat();
FunnelFormat.prototype.constructor = FunnelFormat;

/**
 * 
 * @param {Graph} graph
 * @param {object} Data from ajax calling
 * @returns {undefined}
 */
FunnelFormat.prototype.ajaxSuccess = function (graph, data) {
    var html = tmpl(graph.getGraphContainer().find('.graph-template').html(), data);
    // vd(data);
    graph.getGraphValueContent().html(html);
}






function SocialMediaFormat() {
    BaseFormat.call(this);
}
SocialMediaFormat.prototype = new BaseFormat();
SocialMediaFormat.prototype.constructor = SocialMediaFormat;

/**
 * 
 * @param {Graph} graph
 * @param {object} Data from ajax calling
 * @returns {undefined}
 */
SocialMediaFormat.prototype.ajaxSuccess = function (graph, data) {
    var html = ''; // tmpl(graph.getGraphContainer().find('.graph-template').html(), data);
    for (var i = 0; i < data.data.length; i++) {
        var selector = graph.getGraphContainer().find('.graph-template.' + data.data[i].category);
        if (selector.length) {
            data.data[i].period_name = data.periods.last_period.name;
            html += tmpl(selector.html(), data.data[i]);
        }
    }
    graph.getGraphValueContent().html(html);
}




