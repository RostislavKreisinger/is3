/**
 * Created by tomw on 5/23/17.
 */
function isEmpty(value){
    if(value === undefined){
        return true;
    }

    if(value === null){
        return true;
    }

    if(value === ""){
        return true;
    }

    if(value instanceof Array){
       if(value.length == 0) {
           return true;
       }
    }

    return false;
}

var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;

// Firefox 1.0+
var isFirefox = typeof InstallTrigger !== 'undefined';



// Safari 3.0+ "[object HTMLElementConstructor]"
var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || safari.pushNotification);

// Internet Explorer 6-11
var isIE = /*@cc_on!@*/false || !!document.documentMode;

// Edge 20+
var isEdge = !isIE && !!window.StyleMedia;

// Chrome 1+
var isChrome = !!window.chrome && !!window.chrome.webstore;

// Blink engine detection
var isBlink = (isChrome || isOpera) && !!window.CSS;

var isWebkit = isSafari || isIE || isEdge || isChrome || isBlink;