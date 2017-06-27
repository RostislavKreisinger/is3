// var MonkeyDataPallete = ['#6682BB', '#60A69F', '#78B6D9', '#A37182', '#EEBA69', '#A797D6'];
// try {
//     DevExpress.viz.core.registerPalette('MonkeyDataPallete', MonkeyDataPallete);
// } catch (e) {
//     console.error(e);
// }

window.debug = true;
if (!Array.prototype.get) {
    Array.prototype.get = function (index) {
        if (index < 0) {
            return this[this.length + index];
        }
        return this[index];
    };
}
;

if (!Array.prototype.unset) {
    Array.prototype.unset = function (key) {
        for (var k in this) {
            if (this[k] == key) {
                this.splice(k, 1);
            }
        }
    };
}
;

Number.prototype.formatMoney = function (c, d, t) {
    var n = this,
        c = isNaN(c = Math.abs(c)) ? 2 : c,
        d = d == undefined ? "." : d,
        t = t == undefined ? "," : t,
        s = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};


function graphPrepareString(string) {
    if (isEmpty(string)) {
        return "--";
    }
    return string;
}

function shorten(text, maxLength) {
    var ret = text;
    if (ret.length > maxLength) {
        ret = ret.substr(0, maxLength - 3) + "...";
    }
    return ret;
}


function formatTimeFromSeconds(seconds) {
    var second = seconds % 60;
    seconds -= second;
    if (second < 10) {
        second = '0' + second;
    }

    var minute = (seconds % 3600) / 60;
    seconds -= minute * 60;
    if (minute < 10) {
        minute = '0' + minute;
    }

    var hour = seconds / 3600;
    if (hour < 10) {
        hour = '0' + hour;
    }

    return hour + ":" + minute + ":" + second;
}


function vd() {
    if (window.debug) {
        console.log.apply(null, arguments);
    }
}

function getServerAddress() {
    if ($.isEmptyObject(window.serveraddress)) {
        window.serveraddress = window.location.protocol + "//" + window.location.host;
        if (window.location.hostname === "localhost") {
            if (window.location.port != "8000") {
                window.serveraddress = window.serveraddress + window.urlPrefix;
            }
        } else {
            window.serveraddress = window.serveraddress + window.urlPrefix;
        }
    }
    return window.serveraddress;
}


// init getServerAddress
getServerAddress();


if (!Date.now) {
    Date.now = function () {
        return new Date().getTime();
    }
}

function getTimestamp() {
    return Date.now() / 1000 | 0;
}

function isEmpty(object) {
    if (object === undefined) {
        return true;
    }
    if (object === null) {
        return true;
    }
    if (object === "") {
        return true;
    }
    if (object === []) {
        return true;
    }
    return false;
}


/**
 *
 * @param {Object} extended
 * @param {Object} obj
 * @returns {Object}
 */
function mdExtend(extended, obj) {
    for (var i in obj) {
        if (obj.hasOwnProperty(i)) {
            extended[i] = obj[i];
        }
    }
    return extended;
}
;


/**
 *
 * @param {function} callback
 * @returns {undefined}
 */
Array.prototype.each = function (callback) {
    for (var i in this) {
        if (typeof this[i] !== 'function') {
            callback(i, this[i]);
        }
    }
};

Array.prototype.inArray = function (value) {
    if (this.indexOf(value) !== -1) {
        return true;
    }
    return false;
}

function string(object) {
    if (object === undefined || object == null) {
        return "";
    }
    return object;
}


/**
 *
 * @param {String} findedString
 * @param {Boolean} keySensitive
 * @returns {Boolean}
 */
String.prototype.includes = function (findedString, keySensitive) {
    var str = this;
    if (keySensitive !== true) {
        str = this.toLowerCase();
        findedString = findedString.toLowerCase();
    }
    return (str.indexOf(findedString) == -1) ? false : true;
}

var mdLocalStorageDates = {
    key: 'mdLocalStorageDates',
    name: 'this_month',
    from: '',
    to: ''
}

/**
 *
 * @returns {mdLocalStorageDates}
 */
function getMdLocalStorageDates() {
    if (window.localStorage.getItem(mdLocalStorageDates.key) !== null) {
        return JSON.parse(window.localStorage.getItem(mdLocalStorageDates.key));
    }
    return mdLocalStorageDates;
}

function setMdLocalStorageDates(name, from, to) {
    if (!isEmpty(name))
        mdLocalStorageDates.name = name;
    if (!isEmpty(from))
        mdLocalStorageDates.from = from;
    if (!isEmpty(to))
        mdLocalStorageDates.to = to;

    window.localStorage.setItem(mdLocalStorageDates.key, JSON.stringify(mdLocalStorageDates));
}

function mdDownloadText(filename, text) {
    var pom = document.createElement('a');
    pom.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
    pom.setAttribute('download', filename);

    if (document.createEvent) {
        var event = document.createEvent('MouseEvents');
        event.initEvent('click', true, true);
        pom.dispatchEvent(event);
    }
    else {
        pom.click();
    }
}


function getLightnessOfHexColor(backgroundColor) {
    try {
        if (backgroundColor.length != 4 && backgroundColor.length != 7) {
            return 201;
        }
    } catch (e) {
        return 201;
    }

    var color = backgroundColor.substring(1);      // strip #
    var c = '';
    if (color.length == 3) {
        c += color.substr(0, 1) + color.substr(0, 1);
        c += color.substr(1, 1) + color.substr(1, 1);
        c += color.substr(2, 1) + color.substr(2, 1);
    } else {
        c = color;
    }
    var rgb = parseInt(c, 16);   // convert rrggbb to decimal
    var r = (rgb >> 16) & 0xff;  // extract red
    var g = (rgb >> 8) & 0xff;  // extract green
    var b = (rgb >> 0) & 0xff;  // extract blue

    var luma = 0.2126 * r + 0.7152 * g + 0.0722 * b; // per ITU-R BT.709
    return luma;
}

function getTextColorByBackground(color) {
    if (window.lumaRange === undefined) {
        window.lumaRange = 200;
    }

    var luma = getLightnessOfHexColor(color);
    // vd(color + " - " + luma);
    if (luma > window.lumaRange) {
        return '#000';
    } else {
        return '#FFF';
    }
}