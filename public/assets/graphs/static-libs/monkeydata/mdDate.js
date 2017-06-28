var mdDate = {
    format: 'y-m-d',
    date: new Date(),
    englishWeeks: false,
    cloned: false,
    /**
     * 
     * @param {int} timestamp timestamp * 1000, js has got miliseconds in timestamp
     * @returns {mdDate}
     */
    setDateTimestamp: function (timestamp) {
        if (this.cloned === false) {
            console.error('mdDate try to set up origin object');
        }
        // this.date = new Date(timestamp*1000);
        this.date = new Date(timestamp);
        return this;
    },
    /**
     * 
     * @param {String} format ex. y-m-d
     * @returns {String}
     */
    getFormatDate: function (format) {
        if (format === undefined) {
            format = this.format;
        }

        format = format.replace('y', this.date.getFullYear());

        var m = this.date.getMonth() + 1;
        format = format.replace('m', (m < 10) ? "0" + m : m);

        var w = this.week();
        format = format.replace('w', (w < 10) ? "0" + w : w);
        
        var d = this.date.getDate();
        format = format.replace('d', (d < 10) ? "0" + d : d);

        var hour = this.date.getHours();
        format = format.replace('h', (hour < 10) ? "0" + hour : hour);

        var minutes = this.date.getMinutes();
        format = format.replace('i', (minutes < 10) ? "0" + minutes : minutes);

        var seconds = this.date.getSeconds();
        format = format.replace('s', (seconds < 10) ? "0" + seconds : seconds);

        return format;
    },
    /**
     * 
     * @returns {timestamp}
     */
    getTimestamp: function () {
        return this.date.getTime();
    },
    /**
     * 
     * @returns {mdDate}
     */
    clone: function (date) {
        var obj = this;
        if (null == obj || "object" != typeof obj)
            return obj;
        var copy = obj.constructor();
        for (var attr in obj) {
            if (obj.hasOwnProperty(attr))
                copy[attr] = obj[attr];
        }
        // vd(copy);
        copy.cloned = true;
        if (date !== undefined) {
            if (date instanceof Date) {
                copy.setDateTimestamp(date.getTime());
            } else if (typeof date === 'number') {
                copy.setDateTimestamp(date);
            }
        }
        return copy;
    },
    week: function () {
        var d = this.date;
        var ew = this.englishWeeks;
        
        if (ew == undefined) {
            ew = false;
        }

        // Create a copy of this date object
        var target = new Date(d.valueOf());

        // ISO week date weeks start on monday
        // so correct the day number
        var dayShift = 6;
        if (ew) {
            dayShift = 0;
        }
        var dayNr = (d.getDay() + dayShift) % 7;

        // Set the target to the thursday of this week so the
        // target date is in the right year
        target.setDate(target.getDate() - dayNr + 3);

        // ISO 8601 states that week 1 is the week
        // with january 4th in it
        var jan4 = new Date(target.getFullYear(), 0, 4);

        // Number of days between target date and january 4th
        var dayDiff = (target - jan4) / 86400000;

        // Calculate week number: Week 1 (january 4th) plus the
        // number of weeks between target date and january 4th
        var weekNr = 1 + Math.ceil(dayDiff / 7);

        return weekNr;

    }
}

