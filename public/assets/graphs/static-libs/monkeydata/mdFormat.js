var mdFormat = {

    /**
     * 1 - before
     * 0 - after
     */
    currency_placement: 1,

    currency_thousands_separator: "",
    currency_decimal_point: "",

    number_thousands_separator: "",
    number_decimal_point: "",

    default_empty_value: '--',


    delimiter: " ",
    decimal: ",",
    formatGraphValue: function(number, sign, isCurrency, empty){
        if(isEmpty(empty)){
            empty = this.default_empty_value;
        }

        if (isEmpty(number)) {
            return empty;
        }

        var formatedNumber = formatNumbers(number, this.currency_thousands_separator, this.currency_decimal_point);

        if(!isEmpty(sign)){
            var signSpace = "&nbsp;";
            if(this.currency_placement == 1 && isCurrency){
                return sign + signSpace + formatedNumber;
            }else{
                return formatedNumber + signSpace + sign;
            }
        }

        return formatedNumber;
    },
    number: function (number, sign, empty) {
        return this.formatGraphValue(number, sign, false, empty);
    },
    value: function(number, sign, empty){
        return this.formatGraphValue(number, sign, true, empty);
    },
    string: function (string) {
        if (isEmpty(string)) {
            return "--";
        }
        return string;
    },
    formatSecondToTime: function (seconds, empty) {
        if(empty == undefined){
            empty = "--";
        }
        if (isEmpty(seconds)) {
            return empty;
        }
        var date = new Date(null);
        date.setHours(0); date.setMinutes(0); 
        date.setSeconds(seconds);
        
        var hours = date.getHours() < 10 ? '0'+date.getHours():date.getHours();
        var minutes = date.getMinutes() < 10 ? '0'+date.getMinutes():date.getMinutes();
        var seconds = date.getSeconds() < 10 ? '0'+date.getSeconds():date.getSeconds();
        
        return hours+":"+minutes+":"+seconds;
    },
    loadSetting: function (setting) {

        this.number_thousands_separator = setting.number_thousands_separator;
        this.number_decimal_point = setting.number_decimal_point;

        this.currency_thousands_separator = setting.currency_thousands_separator;
        this.currency_decimal_point = setting.currency_decimal_point;

        this.currency_placement = setting.currency_placement;

    }

}

function formatNumbers(str, delimiter, decimal) {
    var parts = (str + "").split("."),
            main = parts[0],
            output = "",
            minus = false;
    
    if(main.charAt(0) === '-'){
        main = main.substr(1);
        minus=true;
    }
    var len = main.length,
            i = len - 1;
    
    while (i >= 0) {
        output = main.charAt(i) + output;
        if ( ((len - i) % 3 === 0) && (i > 0) && (i !== (len-1)) ) {
            output = delimiter + output;
        }
        --i;
    }
    // put decimal part back
    if (parts.length > 1) {
        output += decimal + parts[1];
    }
    if(minus){
        output = '-'+output;
    }
    return output;
}