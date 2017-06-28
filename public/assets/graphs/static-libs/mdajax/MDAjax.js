
/**
 * 
 * @param {Object} options
 *      async: true,
 beforeSend: function (xhr) {
 },
 data: new Array(),
 dataType: null,
 error: function (xhr, status, error) {
 },
 method: "GET",
 success: function (result, status, xhr) {
 },
 timeout: null,
 url: null,
 progress: function (percent) {
 },
 dontSendNow: false,
 * 
 * @returns {MDAjax}
 */
function MDAjax(options) {
    this.options = {
        async: true,
        beforeSend: function (xhr) {
        },
        data: new Array(),
        dataType: null,
        error: function (xhr, status, error) {
        },
        method: "GET",
        success: function (result, status, xhr) {
        },
        timeout: null,
        url: null,
        progress: function (percent) {
        },
        sendImmediately: true,
        statusCode: {},
        complete: function (xhr, status) {
        },
    };
    this.parameters = new Array();
    this.xhr = null;
    this.url = null;
    this.isAjaxCalled = false;
    this.postData = null;

    this.extend = function (object) {
        for (var i in object) {
            if (object.hasOwnProperty(i)) {
                this.options[i] = object[i];
            }
        }
    };

    this.setUrl = function (url) {
        this.url = url;
    };
    // inhale options
    this.extend(options);
    this.setUrl(this.options.url);

    this.addParameter = function (key, value) {
        this.parameters[key] = value;
    };


    /**
     * 
     * @param {Object} object
     * @returns {String}
     */
    function encodeParameters(object) {
        var encodedString = '';
        for (var property in object) {
            if (object.hasOwnProperty(property)) {
                if (encodedString.length > 0) {
                    encodedString += '&';
                }
                encodedString += encodeURI(property + '=' + object[property]);
            }
        }
        return encodedString;
    }

    /**
     * 
     * @returns {String|null}
     */
    this.getUrlWithParameters = function () {
        var url = this.getUrl();
        if (url !== null) {
            var qm = false;
            var param = encodeParameters(this.options.data);
            if (param.length > 0) {
                url += '?' + param;
                qm = true;
            }
            var param2 = encodeParameters(this.parameters);
            if (param2.length > 0) {
                url += (qm ? '&' : '?') + param2;
            }
            return url;
        }
        return null;
    };

    this.getUrl = function () {
        if (this.url !== null) {
            return this.url;
        }
        return null;
    };

    this.initXhr = function () {
        if (window.XMLHttpRequest) {
            this.xhr = new XMLHttpRequest();
        } else {
            this.xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }
    };

    this.prepareCallbacks = function () {
        var parent = this;

        this.xhr.onreadystatechange = function () {
            switch (this.readyState) {
                case 0:
                    // UNSENT
                    break;

                case 1:
                    // OPENED
                    break;

                case 2:
                    // HEADERS_RECEIVED
                    break;

                case 3:
                    // LOADING
                    break;

                case 4:
                    // DONE
                    this.isAjaxCalled = false;
                    if (this.status === 200) {
                        parent.successCallback(this.responseText, this.status, this);
                    }
                    else {
                        parent.errorCallback(this, this.status, this.statusText);
                    }
                    parent.statusCodeCallback(this.status);
                    parent.completeCallback(this, this.status);
                    break;

                default:
                    break;
            }
        };

        this.xhr.onprogress = function (oEvent) {
            if (oEvent.lengthComputable) {
                var percent = (oEvent.loaded / oEvent.total) * 100;
                parent.progressCallback(percent);
            } else {
                // Unable to compute progress information since the total size is unknown
            }
        };
    };

    this.beforeSend = function () {
        return this.options.beforeSend(this.xhr);
    };

    this.progressCallback = function (percent) {
        this.options.progress(percent);
    };

    this.errorCallback = function (xhr, status, error) {
        this.options.error(xhr, status, error);
    };

    this.completeCallback = function (xhr, status) {
        this.options.complete(xhr, status);
    };

    this.successCallback = function (result, status, xhr) {
        // var header = String.toLowerCase(xhr.getResponseHeader('Content-Type'));
        var header = xhr.getResponseHeader('Content-Type').toLowerCase();
        if (header === 'application/json') {
            result = JSON.parse(result);
        }
        this.options.success(result, status, xhr);
    };

    this.statusCodeCallback = function (statusCode) {
        if (this.options.statusCode.hasOwnProperty(statusCode)) {
            this.options.statusCode[statusCode]();
        }
    };

    this.preparePOSTData = function () {
        this.postData = '';
        var qm = false;
        var param = encodeParameters(this.options.data);
        if (param.length > 0) {
            this.postData += param;
            qm = true;
        }
        var param2 = encodeParameters(this.parameters);
        if (param2.length > 0) {
            this.postData += (qm ? '&' : '') + param2;
        }
    };

    /**
     * 
     * @returns {Boolean}
     */
    this.send = function () {
        if (this.xhr === null) {
            this.initXhr();
        }
        if (this.options.method === "POST") {
            return this.sendPOST();
        }
        return this.sendGET();
    };

    this.sendGET = function () {
        this.options.url = this.getUrlWithParameters();
        this.xhr.open("GET", this.options.url, this.options.async);
        if (this.options.timeout !== null) {
            try {
                this.xhr.timeout = this.options.timeout;
            } catch (e) {

            }
        }
        this.prepareCallbacks();
        if (this.beforeSend() === false) {
            this.xhr.abort();
            return false;
        }
        this.isAjaxCalled = true;
        this.xhr.send();
        return true;
    };

    this.sendPOST = function () {
        this.options.url = this.getUrl();
        this.xhr.open("POST", this.options.url, this.options.async);
        if (this.options.timeout !== null) {
            try {
                this.xhr.timeout = this.options.timeout;
            } catch (e) {

            }
        }
        this.xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        this.xhr.setRequestHeader("Content-length", this.postData.length);
        this.prepareCallbacks();
        if (this.beforeSend() === false) {
            this.xhr.abort();
            return false;
        }
        this.preparePOSTData();
        this.isAjaxCalled = true;
        this.xhr.send(this.postData);
        return true;
    };

    this.cancelXhr = function () {
        if (this.isAjaxCalled) {
            this.xhr.abort();
            this.isAjaxCalled = false;
        }
    };

    if (this.options.sendImmediately === true) {
        // posle rovnou
        return this.send();
    }
}
