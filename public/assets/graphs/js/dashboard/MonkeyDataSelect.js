(function ($) {

    /**
     * Initiation at the bottom
     *
     * Converts <select><option> into <ul><li>
     * All data are stored in Html element <select> which has initialized plugin in
     */

    $.fn.MDSelect = function (options) {

        var $this = $(this);
        var thisElement = $this[0];
        var isFatalError = false;

        //If tooltip doesnot exist
        if (thisElement == null) {
            console.log("Select Error[thisElement] does not exist");
            isFatalError = true;
            return;
        }

        // Default values
        var settings = $.extend({
            renderSettings: null
        }, options);


        /**
         * Select Helper
         * @type {{
         * htmlElement: string,
         * htmlId: string,
         * currentOptions: Array,
         * renderSettings:
         * {showOkIcons: boolean},
         * constructor: constructor,
         * renderSelect: renderSelect,
         * removeCheckedOptions: removeCheckedOptions,
         * markOptionAsSelected: markOptionAsSelected,
         * checkIfIsOptionAvailable: checkIfIsOptionAvailable,
         * selectOption: selectOption,
         * getIndexFromArrayByKey: getIndexFromArrayByKey,
         * setHeader: setHeader,
         * selectHeader: selectHeader
         * }}
         */
        var SelectHelper = {

            htmlElement: '',
            htmlId: '',
            currentOptions: [],
            renderSettings: {
                showOkIcons: false
            },

            /**
             *  Fake constructor
             */
            constructor: function () {

                var that = this;
                this.htmlElement = thisElement;
                this.htmlId = this.htmlElement.id;
                that.currentOptions = [];

                $("#" + thisElement.id + " option").each(function () {

                    var visible = $(this).attr('data-visible');
                    if (visible) {
                        visible = (visible === 'true');
                    } else {
                        visible = true;
                    }
                    // Load all options from select
                    var option = {
                        'text': $(this).text(),
                        'value': $(this).val(),
                        'callback': $(this).attr('data-callback'),
                        'name': $(this).attr('data-name'),
                        'visible': visible
                    };

                    that.currentOptions.push(option);
                });


                // Html id
                this.renderSettings.htmlId = this.htmlId;
                this.renderSettings.className = this.htmlElement.className;
                this.renderSettings.options = this.currentOptions;


                var jqueryElement = $("#" + this.htmlId);

                // Get Select callback what will happen after select option
                this.renderSettings.callback = jqueryElement.attr('data-callback');

                // If is Select visible
                var visible = jqueryElement.attr('data-visible');
                if (visible) {
                    this.renderSettings.visible = (visible === 'true');
                } else {
                    this.renderSettings.visible = true;
                }

                // Placeholder when is value not set
                var placeholder = jqueryElement.attr('data-placeholder');
                if (placeholder) {
                    this.renderSettings.placeholder = placeholder;
                } else {
                    this.renderSettings.placeholder = "Select option";
                }
                // Default set value (overrides placeholder)
                var defaultValue = jqueryElement.attr('data-default-value');

                this.renderSettings.defaultValue = defaultValue;

                // Css zIndex of element
                var zIndex = jqueryElement.attr('data-z-index');

                this.renderSettings.zIndex = zIndex;

                // Show select icon
                var showSelectIcon = jqueryElement.attr('data-select-icon-show');

                if (showSelectIcon) {
                    this.renderSettings.showSelectIcon = (showSelectIcon === 'true');
                } else {
                    this.renderSettings.showSelectIcon = true;
                }

                // Show close icon which hides select
                var showCloseIcon = jqueryElement.attr('data-select-close-show');

                if (showCloseIcon) {
                    this.renderSettings.showCloseIcon = (showCloseIcon === 'true');
                } else {
                    this.renderSettings.showCloseIcon = false;
                }
                // Callback of close btn, what will happen after click
                this.renderSettings.selectCloseCallback = jqueryElement.attr('data-select-close-callback');


                var selectIconClass = jqueryElement.attr('data-select-icon-class');

                if (selectIconClass) {
                    this.renderSettings.selectIconClass = selectIconClass;
                } else {
                    this.renderSettings.selectIconClass = "md-icon-Triangle-select-icon-16";
                }

                // Allow select only nonselected
                var selectOnlyNonSelected = jqueryElement.attr('data-select-only-non-selected');

                if (selectOnlyNonSelected) {
                    this.renderSettings.selectOnlyNonSelected = (selectOnlyNonSelected === 'true');
                } else {
                    this.renderSettings.selectOnlyNonSelected = false;
                }


                var currentValue = jqueryElement.attr('data-selected-name');

                // Render select
                this.renderSelect();

                // If has already set value render it into select
                if (currentValue) {
                    this.selectHeader(currentValue);
                    return;
                }
                // If is default value and not placeholder, render it into select
                if (defaultValue) {
                    this.selectHeader(defaultValue);
                    return;
                }

                this.selectPlaceholder(placeholder);


            },
            /**
             * Render Select <ul>
             */
            renderSelect: function () {
                this.htmlElement.style.display = "none";

                Render.init(this.renderSettings);

            },
            /**
             * Remove check icons from options and unmark them as selected
             */
            removeCheckedOptions: function () {
                $("#" + thisElement.id + "-base-ul li").each(function () {
                    $(this).removeClass("selected");
                });
            },
            /**
             * Mark option as selected
             * @param id
             */
            markOptionAsSelected: function (id) {
                if (id !== undefined) {
                    if (!isNaN(id)) {
                        id = thisElement.id + "-select-" + id;
                    }
                }
                var options = this.renderSettings.options;
                for (var i = 0; i < options.length; i++) {
                    var htmlId = thisElement.id + "-select-" + i;
                    if (!options[i].visible) {
                        $("#" + htmlId).addClass("disabled");
                    }
                }
                if (id !== undefined) {
                    $("#" + id).removeClass("disabled").addClass("selected");
                }
            },
            /**
             * Check if can be selected option
             * @param htmlId
             * @returns {boolean}
             */
            checkIfIsOptionAvailable: function (htmlId) {

                var status = $("#" + htmlId).hasClass('disabled');
                if (status) {
                    return false;
                }
                return true;
            },
            /**
             * Select option and mark it as selected
             * @param id
             */
            selectOption: function (id) {

                var ul = $("#" + thisElement.id + "-base-ul");

                if (this.checkIfIsOptionAvailable(id)) {

                    this.removeCheckedOptions();

                    this.markOptionAsSelected(id);

                    this.selectHeader(document.getElementById(id).dataName)
                }
                ul.hide();

            },
            /**
             * Get index from array by key
             * @param name
             * @param value
             * @returns {number}
             */
            getIndexFromArrayByKey: function (name, value) {
                var options = this.renderSettings.options;
                for (var i = 0; i < options.length; i++) {
                    if (options[i][name] == value) {
                        return i;
                    }
                }
                return -1;
            },
            /**
             * Render text into header
             * @param selectHeader
             * @param element
             * @param name
             * @param text
             */
            setHeader: function (selectHeader, element, name, text) {
                element.attr('data-selected-name', name);

                selectHeader.html(text);

            },

            selectPlaceholder: function (placeholder) {

                var element = $("#" + thisElement.id);
                var selectHeader = $("#" + thisElement.id + "-select-text");
                var select = $("#" + thisElement.id + "-select");

                this.setHeader(selectHeader, element, null, placeholder);

                select.removeClass("selected-value");
                this.markOptionAsSelected();
            },
            /**
             * Decide what to do with header text
             * @param name
             */
            selectHeader: function (name) {

                var element = $("#" + thisElement.id);
                var selectHeader = $("#" + thisElement.id + "-select-text");


                var index = this.getIndexFromArrayByKey("name", name);
                var text = this.renderSettings.options[index].value;
                if (selectHeader.text() != text) {

                    $("#" + thisElement.id + "-select").addClass("selected-value");
                    this.setHeader(selectHeader, element, name, text);
                    this.markOptionAsSelected(index);
                } else {
                    var select = $("#" + thisElement.id + "-select");
                    var defaultValue = this.renderSettings.defaultValue;
                    this.removeCheckedOptions();

                    if (defaultValue) {
                        select.addClass("selected-value");
                        this.setHeader(selectHeader, element, defaultValue, defaultValue);
                        this.markOptionAsSelected(index);
                    } else {
                        this.setHeader(selectHeader, element, null, this.renderSettings.placeholder);
                        select.removeClass("selected-value");
                    }
                }


            }

        };


        var Render = {
            config: '',

            init: function (config) {
                this.config = config;

                this.select();
                this.options();
                this.fixWidth();
            },
            /**
             * Render select
             */
            select: function () {

                var rootElement = document.getElementById(this.config.htmlId);
                var baseId = this.config.htmlId + "-base";
                var oldBase = document.getElementById(baseId);

                if (oldBase) {
                    oldBase.remove();
                }
                var base = document.createElement('div');

                base.className = "md-select md-select-base-initiated";
                base.id = baseId;

                if (this.config.zIndex) {
                    base.style.zIndex = this.config.zIndex;
                }

                if (!this.config.visible) {
                    base.style.display = "none";
                }


                var span = document.createElement('span');
                span.innerHTML = this.config.placeholder;
                span.id = this.config.htmlId + "-select-text";

                if (this.config.showSelectIcon) {
                    var icon = document.createElement('span');
                    icon.className = this.config.selectIconClass + " ico";
                    icon.id = this.config.htmlId + "-select-icon";
                }

                var select = document.createElement('div');
                select.className = this.config.className + " md-select-header";
                select.id = this.config.htmlId + "-select";


                select.appendChild(span);

                if (this.config.showSelectIcon) {
                    select.appendChild(icon);
                }

                base.appendChild(select);

                if (this.config.showCloseIcon) {
                    var closeIcon = document.createElement('span');
                    closeIcon.className = "md-icon-delete select-remove";
                    closeIcon.onclick = (function (rootElement, config) {
                        return function () {
                            rootElement.setAttribute('data-visible', 'false');
                            SelectHelper.constructor();

                            if (config.selectCloseCallback) {
                                var fun = new Function(config.selectCloseCallback);
                                return (fun());
                            }


                        }
                    })(rootElement, this.config);

                    base.appendChild(closeIcon);
                }

                rootElement.parentNode.appendChild(base);
            },
            /**
             * Set fixed width
             */
            fixWidth: function () {
                var longestOption = this.getLongestOption();

                var baseElement = document.getElementById(this.config.htmlId + "-base");
                baseElement.style.minWidth = ((longestOption.length * 10) + 40) + "px";

            },
            getLongestOption: function () {
                var option = "";
                for (var i = 0; i < this.config.options.length; i++) {
                    var currentOption = this.config.options[i].value;
                    if (option.length <= currentOption.length) {
                        option = currentOption;
                    }
                }

                return option;
            },
            /**
             * Render options
             */
            options: function () {

                var baseElement = document.getElementById(this.config.htmlId + "-base");

                var ul = document.createElement('ul');
                ul.id = baseElement.id + "-ul";
                ul.className = "md-select-base-wrapper md-select-select md-select-select-ul";
                ul.style.display = "none";

                for (var i = 0; i < this.config.options.length; i++) {
                    var option = this.config.options[i];

                    var li = document.createElement('li');
                    var htmlId = this.config.htmlId;
                    var id = htmlId + "-select-" + i;
                    li.id = id;
                    li.innerHTML = option.text;


                    li.dataName = option.name;
                    li.onmouseup = (function (i, id, htmlId, options, selectOnlyNonSelected, baseElement) {
                        return function () {
                            var newOption = document.getElementById(id);
                            if (selectOnlyNonSelected) {
                                if (newOption.className == 'disabled' || newOption.className == 'selected') {
                                    return;
                                }
                            }

                            baseElement.classList.remove("md-select-opened");
                            SelectHelper.selectOption(id);
                            if (options.callback) {
                                var fun = new Function(options.callback);
                                return (fun());
                            }
                        }
                    })(i, id, htmlId, option, this.config.selectOnlyNonSelected, baseElement);

                    var checkIcon = document.createElement('span');
                    checkIcon.className = "md-icon-check check-ico";
                    checkIcon.id = this.config.htmlId + "-icon";
                    li.appendChild(checkIcon);
                    ul.appendChild(li);

                }

                baseElement.appendChild(ul);
            }

        };


        // Initialize only once
        if (!this.hasClass('md-select-initiated')) {
            this.addClass('md-select-initiated');

            /* Executing part */
            SelectHelper.constructor();


            function addOnceClickFunctionToDocument() {
                $(document).mousedown(function (e) {

                    var target = e.target.id;
                    var element = target;

                    element = element.substring(0, element.indexOf("-select"));
                    var base = document.getElementById(element + "-base");

                    if (base != null) {

                        var current = $("#" + target);
                        if (current.hasClass('selected') || current.hasClass('disabled')) {
                            return;
                        }
                        var ul = document.getElementById(base.id + "-ul");
                        var jBase = $("#" + element + "-base");

                        var wasSelected = jBase.hasClass('md-select-opened');

                        $(".md-select-base-initiated").removeClass("md-select-opened");

                        if (base.contains(e.target) && !wasSelected) {
                            jBase.addClass("md-select-opened");
                        }


                    } else {
                        $(".md-select-base-initiated").removeClass("md-select-opened");
                    }


                    $(".md-select-base-initiated .md-select-base-wrapper").fadeOut(150);
                    $(".md-select-base-initiated.md-select-opened .md-select-base-wrapper").fadeIn(150);

                });

            }

            if (!window.mdSelectInitiated) {
                window.mdSelectInitiated = true;
                addOnceClickFunctionToDocument();
            }

            /* !Executing part */

        }

        // Returned back as fluent function to render
        return {
            reRender: function () {
                SelectHelper.constructor();
            }
        };
    };

}(jQuery));