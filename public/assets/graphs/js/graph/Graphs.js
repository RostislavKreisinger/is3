// DevExpress.ui.setTemplateEngine('underscore');

window.Graphs = {
    date_from: 'this_month',
    date_to: '',
    currentDate: "NOW",
    group_by: '',
    compare: new Array(),
    detail: '',
    token: 'token',
    language: null,
    token_lifetime: null,
    token_expires_at: null,
    token_being_refreshed: false,
    token_url: getServerAddress() + "/graph-api/access-token",
    token_refresh_url: getServerAddress() + "/graph-api/new-access-token",
    refreshTokenTimeout: null,
    project_id: '0',
    project_or_projectgroup_type: 'p',
    /**
     * @property Graph[]
     */
    array: new Array(),
    urlBase: null,
    ajaxurl: null,
    onReloadAll: null,
    allowAnimation: true,
    disabledLoadingFromApi: false,
    ajax: new Array(),
    paramsArray: Array('date_from', 'date_to', 'group_by', 'detail'),
    alreadyInit: false,
    /**
     * @param graphId
     * @returns {Graph}
     */
    getGraphById: function(graphId){
        var graphs = new Array();
        /**
         * @property {Graph} graph
         */
        var graphsArray = this.array;
        graphsArray.each(function (index, graph) {
            if(graph.getId() == graphId){
                graphs.push(graph);
            }
        });
        if(graphs.length == 0){
            return null;
        }
        if(graphs.length == 1){
            return graphs[0];
        }
        return graphs;
    },
    /**
     *
     * @param {Graph} graph
     * @returns {Window.Graphs}
     */
    addGraph: function (graph) {
        var id = graph.getUniqueId();
        if (graph.detail === true) {
            id = 0;
        }
        this.array[id] = graph;
        return this;
    },
    /**
     *
     * @param {Graph} graph
     * @returns {Window.Graphs}
     */
    addDetailGraph: function (graph) {
        this.array[0] = graph;
        return this;
    },
    init: function () {
        var graphsArray = this.array;
        graphsArray.each(function (index, graph) {
            graph.lateInit();
        });
        this.alreadyInit = true;
    },
    /**
     * @param {Boolean}strictReload
     */
    reload: function (strictReload) {
        if(!this.alreadyInit){
            this.init();
        }
        var graphsArray = this.array;
        this.checkIfTokenIsExpiredAndRefresh(function () {
            graphsArray.each(function (index, graph) {
                graph.reload(strictReload);
            });
        });
    },
    redraw: function () {
        this.array.each(function (index, graph) {
            graph.redraw();
        });
    },
    /**
     * Set graph property
     * @param {string} key 'date_from'|'date_to'|'agregate'|'compare'
     * @param {mixin} value Property value
     */
    set: function (key, value) {
        if (this.paramsArray.inArray(key)) {
            this[key] = value;
        } else {
            console.warn("Graphs: trying to set undefined property")
        }
        return this;
    },
    /**
     * Set graph property and reload data
     * @param {string} key 'date_from'|'date_to'|'agregate'|'compare'
     * @param {mixin} value Property value
     */
    setAndRealod: function (key, value) {
        this.set(key, value);
        this.reload();
    },
    /**
     * 
     * @param {Graph} graph
     * @returns {string}
     */
    getAjaxURL: function (graph) {
        var ajaxurl = this.getBaseGraphApiUrl();
        ajaxurl += 'a' + '/';
        if(!this.token) {
            this.token = "token";
        }
        ajaxurl += this.token + '/';
        ajaxurl += this.project_or_projectgroup_type + '/';
        ajaxurl += this.project_id + '/';
        if (graph !== false) {
            ajaxurl += graph.getId();
        }
        return ajaxurl;
    },
    /**
     * @returns {string}
     */
    getBaseGraphApiUrl: function(){
        var ajaxurl = this.urlBase;
        if ($.isEmptyObject(ajaxurl)) {
            // production
            ajaxurl = 'https://graph-api.monkeydata.com/';
        }
        if (ajaxurl[ajaxurl.length - 1] != '/') {
            ajaxurl = ajaxurl + '/';
        }
        return ajaxurl;
    },
    /**
     * 
     * @returns {Object}
     */
    getAjaxParams: function () {
        var params = {};
        $.each(Graphs.paramsArray, function (i, value) {
            if (!$.isEmptyObject(Graphs[value])) {
                params[value] = Graphs[value];
            }
        });
        return params;
    },
    /**
     *
     * @param {String} element
     */
    realodByDateButton: function (element) {
        this.setAndRealod('date_from', $(element).data('period'));
    },
    refreshToken: function (callback) {
        if (this.token_being_refreshed) {
            return;
        }
        if (callback === undefined) {
            callback = function () {
            };
        }
        this.token_being_refreshed = true;
        new MDAjax({
            url: this.token_refresh_url,
            timeout: 5000,
            beforeSend: function (xhr) {
                vd(this.url);
            },
            success: function (result) {
                if (result.success) {
                    Graphs.replaceTokenByNewToken(result);
                }
                callback();
            },
            error: function () {
                console.error("refreshToken error");
                // TODO error message for user
            },
            complete: function () {
                Graphs.token_being_refreshed = false;
            },
        });
    },
    checkIfTokenIsExpiredAndRefresh: function (callback) {
        if (window.location.hostname === 'localhost') {
            callback();
            return;
        }

        if(this.disabledLoadingFromApi === true){
            callback();
            return;
        }

        var time = getTimestamp();
        if (this.token_expires_at <= time) {
            vd('token is expired, trying to refresh it');
            Graphs.refreshToken(callback);
        } else {
            var diff = this.token_expires_at - time;
            vd('token is still valid for another ' + diff + ' seconds');
            callback();
        }
    },
    replaceTokenByNewToken: function (result) {
        this.token = result.access_token;
        this.token_expires_at = result.expires_at;
        this.token_lifetime = result.lifetime;
        this.setRefreshTokenTimeout();
    },
    setRefreshTokenTimeout: function () {
        var time = getTimestamp();
        var intervalTimeout = (this.token_expires_at - time - 30) * 1000;
        this.refreshTokenTimeout = setTimeout(function () {
            Graphs.refreshToken();
        }, intervalTimeout);
    },
    showGraphs: function () {
        console.log(this.array);
    },
    trigger: function (name) {
        $(document).trigger(name);
    },
    on: function (name, fnc) {
        $(document).on(name, fnc);
    },
    onDateChange: function(fnc){
        this.on('md-graph-date-change', fnc);
    },
    triggerDateChange: function(){
        console.log('trigger date change');
        this.trigger('md-graph-date-change');
    },
    setCurrency: function(currency){
        var graphsArray = this.array;        
        graphsArray.each(function (index, graph) {
            graph.setCurrency(currency);
        });
    },
    
    cancelAllRequest: function(){
        Graphs.alreadyInit = false;
        // console.log('cancelAllRequest');
        $.each(window.Graphs.ajax, function(key, value){
            if(typeof value === 'object' && typeof value.cancelXhr === 'function'){
                setInterval(function(){
                    value.cancelXhr();
                    delete window.Graphs.ajax[key];
                }, 10);
            }
        });
    },
    switchGraphCompare: function (setOn) {
        if (setOn === false) {
            $('.chart-detail-box').removeClass('show-compare').addClass('hide-compare');
        } else {
            $('.chart-detail-box').removeClass('hide-compare').addClass('show-compare');
            // this.getGraphCompare().show();
        }
    },
    setShowLoading: function (showLoading) {
        var graphsArray = this.array;
        graphsArray.each(function (index, graph) {
            graph.setShowLoading(showLoading);
        });
    },
    setDataFromApi: function (graphId, data) {
        this.disabledLoadingFromApi = true;
        var graph = this.array[graphId];
        graph.setDataFromApiAndDisableApiCall(data);
        this.array[graphId] = graph;
    },
}


