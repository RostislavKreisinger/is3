/**
 * Initiating class
 * @constructor
 */
function ClassStoreLab(graphId) {
    var storeLab = StoreLab;

    storeLab.init(graphId);

    return storeLab;
}

var StoreLab = {
    graphId: null,
    selector: ".select-segments:not(.md-select-header):not(#select-segment-template)",
    selectElements: null,
    selectElementsLimit: 3,
    selectTemplate: null,
    selectKeywords: [],
    selectRecommended: null,
    segmentWrapper: null,
    helper: null,
    recommended: [
        {
            text: "Products by Customers",
            defaultValues: ["product", "customer"]
        },
        {
            text: "Products by Countries & Customers",
            defaultValues: ["product", "country", "customer"]
        },
        {
            text: "Customers by Countries & Cities",
            defaultValues: ["customer", "country", "city"]
        },
        {
            text: "Orders by weeks",
            defaultValues: ["order_id", "day"]
        },
        {
            text: "Customers by Order Date",
            defaultValues: ["customer", "day"]
        }
    ],
    /**
     * Should be executed as first
     */
    init: function (graphId) {
        this.helper = storeLabHelper;
        this.graphId = graphId;

        // Load template
        this.loadTemplate();
        this.loadSegmentWrapper();
        this.initiateRecommended();
        this.addSegment('product', true);

        // Load active selects and
        this.loadActiveSelects();

        this.initiateSelects();
    },
    loadSegmentWrapper: function () {
        this.segmentWrapper = $("#chart-segments");
    },
    loadActiveSelects: function () {
        this.selectElements = $(this.selector);
        return this.selectElements;
    },
    hasEmptyActiveSelects: function () {
        return ($(this.selector + ':not([data-selected-name])').length > 0);
    },
    loadTemplate: function () {
        var template = $("#select-segment-template");
        template.hide();
        template.removeAttr('data-default-value');
        template.removeAttr('data-select-close-show');
        this.selectTemplate = template.clone(true);
        return this.selectTemplate;
    },
    /**
     * Initialization of recommended select
     */
    initiateRecommended: function () {
        this.selectRecommended = $("#" + this.helper.getRecommendedHtmlId());
        this.helper.refreshMDSelect(this.selectRecommended);
    },
    /**
     * Initialization of selects
     */
    initiateSelects: function () {
        for (var i = 0; i < this.loadActiveSelects().length; i++) {
            $("#" + this.selectElements[i].id).MDSelect();
        }
    },
    /**
     * Update selected select's keywords
     * @param refreshRecommended
     * @param dontRefreshGraph
     */
    updateSelectKeywords: function (refreshRecommended, dontRefreshGraph) {
        this.selectKeywords = [];
        var length = this.loadActiveSelects().length;
        for (var i = 0; i < length; i++) {

            var element = $("#" + this.selectElements[i].id);

            if (element.length) {
                if ((length > 1 && !this.hasEmptyActiveSelects()) || !element.attr('data-selected-name')) {
                    element.attr('data-select-close-show', "true");
                } else {
                    element.removeAttr('data-select-close-show');
                }
                this.selectKeywords[i] = element.attr('data-selected-name');
            }
        }

        if (refreshRecommended) {
            this.resetSelectRecommended()
        }

        this.synchronizeOptions();
        this.refreshSelects();
        if (dontRefreshGraph === undefined) {
            this.refreshGraph();
        }
        this.checkAddSegmentButton();
    },
    /**
     * Reset recommended option to placeholder
     */
    resetSelectRecommended: function () {
        this.selectRecommended.removeAttr('data-selected-name');
        this.helper.refreshMDSelect(this.selectRecommended);
    },
    /**
     * Hides specific segment
     * @param elementId
     */
    hideSegment: function (elementId) {
        var element =$("#" + elementId);
        var isEmpty = (element.attr('data-selected-name') === undefined);
        element.remove();
        $("#" + elementId + "-base").remove();

        if(!isEmpty) {
            this.updateSelectKeywords(true);
        } else {
            this.updateSelectKeywords(true,true);
        }
    },
    /**
     * Add segment (empty or with previous selection)
     * @param segment
     * @param refreshRecommended
     */
    addSegment: function (segment, refreshRecommended) {
        if (segment === undefined && refreshRecommended === undefined && $("#add-segment-button").hasClass('disabled')) {
            return;
        }

        var newSegment = this.selectTemplate;
        var newId = this.helper.getNextAvailableHtmlId();
        newSegment.attr('id', newId);
        newSegment.attr('data-select-close-callback', 'storeLab.hideSegment("' + newId + '")');


        if (segment !== undefined) {
            newSegment.attr('data-selected-name', segment);
        } else {
            newSegment.removeAttr('data-selected-name');
        }

        var clone = newSegment.clone(true);
        clone.appendTo(this.segmentWrapper);

        if (segment === undefined && refreshRecommended === undefined) {
            this.updateSelectKeywords(true, true);
            return;
        }

        if (refreshRecommended === undefined) {
            this.updateSelectKeywords(false, true);
        } else {
            this.updateSelectKeywords(refreshRecommended);
        }
    },
    refreshSelects: function () {
        for (var i = 0; i < this.loadActiveSelects().length; i++) {
            var element = $("#" + this.selectElements[i].id);
            this.helper.refreshMDSelect(element);
        }
    },
    /**
     * Shows/hides Add Segment button according to number of shown segments
     */
    checkAddSegmentButton: function () {
        if (this.loadActiveSelects().length >= this.selectElementsLimit) {
            $("#add-segment-button").hide();
        } else {
            if (this.hasEmptyActiveSelects()) {
                $("#add-segment-button").addClass('disabled');
            } else {
                $("#add-segment-button").removeClass('disabled');
            }
            $("#add-segment-button").show();
        }
    },
    /**
     * Refresh Graph with new dimensions
     */
    refreshGraph: function () {

        // var graph = Graphs.array[this.graphId];
        var graph = Graphs.getGraphById(this.graphId);
        // returns array of non-empty and defined members
        graph.dimensions = this.selectKeywords.filter(function (e) {
            return e;
        });

        graph.reload(true);
    },
    /**
     * Gets recommended setting from this and automatically chooses selects
     * @param recommendedIndex
     */
    setRecommendedSegments: function (recommendedIndex) {
        var recommended = this.recommended[recommendedIndex];
        this.clearWrapper();

        for (var i = 0; i < recommended.defaultValues.length; i++) {
            var defaultValue = recommended.defaultValues[i];
            this.addSegment(defaultValue);
        }
        this.refreshGraph();
        //this.updateSelectKeywords();

    },
    clearWrapper: function () {
        this.segmentWrapper.html('');
    },
    /**
     * Synchronizes options (duplication, marked selected synchronized form other selects.
     */
    synchronizeOptions: function () {

        for (var i = 0; i < this.loadActiveSelects().length; i++) {

            var elementId = this.selectElements[i].id;
            var options = $("#" + elementId + " option");
            var that = this;
            options.each(function () {

                var option = that.helper.inArray(that.selectKeywords, $(this).attr('data-name'));

                if (option) {
                    $(this).attr('data-visible', "false")
                } else {
                    $(this).attr('data-visible', "true")
                }

            });

        }
    }
};

/**
 * Helper for storeLab Selects
 */
var storeLabHelper = {
    /**
     * Returns id of Select
     * @param number
     * @returns {string}
     */
    getHtmlId: function (number) {
        return "select-segment-" + number;
    },
    getNextAvailableHtmlId: function () {
        var id = 1;
        var element = $("#" + this.getHtmlId(id));
        while (element.length) {
            id++;
            element = $("#" + this.getHtmlId(id));
        }

        return this.getHtmlId(id);

    },
    /**
     * Returns id of Recommended Segment Combinations select
     */
    getRecommendedHtmlId: function () {
        return "select-recommended";
    },

    /**
     * Returns if is member in array
     * @param stack
     * @param needle
     * @returns {boolean}
     */
    inArray: function (stack, needle) {
        // More readable than one line statement
        if (stack.indexOf(needle) > -1) {
            return true;
        } else {
            return false;
        }
    },
    /**
     * ReRenders element
     */
    refreshMDSelect: function (element) {
        var plugin = element.MDSelect();
        plugin.reRender();
    }

};