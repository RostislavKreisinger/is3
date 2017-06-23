(function ($) {


    /**
     * Initiation at the bottom
     *
     * Converts <select><option> into <ul><li>
     * All data are stored in Html element <select> which has initialized plugin in
     */

    $.fn.MDSelectSearch = function (options) {

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
            loadFromTemplate: false,
            onlyInitSettings: false,
            renderSettings: null,
            beforeInit: null,
            onInit: null,
            onInitTime: 0,
            onInitAfterSearch: null,
            onChange: null,
            onChangeTime: 500
        }, options);

        if (settings.onlyInitSettings) {
            this.data("pluginsettings", settings);
            return;
        }


        var pluginSettings = this.data("pluginsettings");
        // if (pluginSettings) {
        //     this.data("pluginsettings", settings);
        //     return;
        // }

        if (pluginSettings) {
            for (var index in settings) {
                if (settings[index] == null) {
                    settings[index] = pluginSettings[index];
                }
            }
        }


        /**
         *
         * @type
         * {{htmlElement: string,
         * htmlId: string,
         * timer: null,
         * renderSettings: {options: Array},
         * constructor: constructor,
         * reloadOptions: reloadOptions,
         * setRenderSetting: setRenderSetting,
         * getRenderSetting: getRenderSetting,
         * addOptionsByKey: addOptionsByKey,
         * clearNonSelectedSettings: clearNonSelectedSettings,
         * addToOption: addToOption,
         * addOption: addOption,
         * renderSelect: renderSelect,
         * selectOption: selectOption,
         * printSelectedOptions: printSelectedOptions,
         * getSpecificOption: getSpecificOption,
         * getNonSelectedOptionsObjects: getNonSelectedOptionsObjects,
         * getSelectedOptionsObjects: getSelectedOptionsObjects,
         * filterOptionByKeyValue: filterOptionByKeyValue
         * }}
         */
        var SelectHelper = {

            htmlElement: '',
            htmlId: '',
            timer: null,
            renderSettings: {
                options: []
            },

            /**
             *  Fake constructor
             */
            constructor: function () {

                this.htmlElement = thisElement;

                if (isEmpty(this.htmlElement.id)) {
                    this.htmlElement.id = "select-search-rand-id-" + Math.round(Math.random() * 1000000);
                }
                this.htmlId = this.htmlElement.id;

                // Html id
                this.renderSettings.htmlId = this.htmlId;
                this.renderSettings.className = this.htmlElement.className;


                var jqueryElement = $("#" + this.htmlId);

                // Get Select callback what will happen after select option / Can be passed as option into options as well
                this.setRenderSetting('callback', Helper.getHtmlAttribute(jqueryElement, 'data-callback', 'string'));

                // If is Select visible
                this.setRenderSetting('visible', Helper.getHtmlAttribute(jqueryElement, 'data-visible', 'boolean', true));

                // Placeholder when is value not set
                this.setRenderSetting('placeholder', Helper.getHtmlAttribute(jqueryElement, 'data-placeholder', 'string', "Select option"));

                // Default set value (overrides placeholder)
                this.setRenderSetting('defaultValue', Helper.getHtmlAttribute(jqueryElement, 'data-default-value', 'string'));

                // Css zIndex of element
                this.setRenderSetting('zIndex', Helper.getHtmlAttribute(jqueryElement, 'data-z-index', 'number'));

                // Show select icon
                this.setRenderSetting('showSelectIcon', Helper.getHtmlAttribute(jqueryElement, 'data-select-icon-show', 'boolean', true));

                // Show close icon which hides select
                this.setRenderSetting('showCloseIcon', Helper.getHtmlAttribute(jqueryElement, 'data-select-close-show', 'boolean', false));

                // Callback of close btn, what will happen after click
                this.setRenderSetting('selectCloseCallback', Helper.getHtmlAttribute(jqueryElement, 'data-select-close-callback', 'string'));

                // Select header icon
                this.setRenderSetting('selectIconClass', Helper.getHtmlAttribute(jqueryElement, 'data-select-icon-class', 'string', 'md-icon-Triangle-select-icon-16'));

                // Allow select only nоn selected
                this.setRenderSetting('selectOnlyNоnSelected', Helper.getHtmlAttribute(jqueryElement, 'data-select-only-non-selected', 'boolean', false));

                // Curent selected value
                this.setRenderSetting('currentValue', Helper.getHtmlAttribute(jqueryElement, 'data-selected-name', 'string'));

                // Is enabled search input
                this.setRenderSetting('searchEnabled', Helper.getHtmlAttribute(jqueryElement, 'data-search-enabled', 'boolean', false));

                // If is search filtering from api or options
                this.setRenderSetting('apiEnabled', Helper.getHtmlAttribute(jqueryElement, 'data-api-enabled', 'boolean', false));

                // Api url where will be called after change search input value
                this.setRenderSetting('searchApiUrl', Helper.getHtmlAttribute(jqueryElement, 'data-search-api-url', 'string'));

                // If is search filtering from api or options
                this.setRenderSetting('callApiOnInit', Helper.getHtmlAttribute(jqueryElement, 'data-call-api-on-init', 'boolean', true));

                // If can be selected only one
                // .is('[multiple]')
                this.setRenderSetting('simpleSelect', !jqueryElement.is('[multiple]'));
                // this.setRenderSetting('simpleSelect', Helper.getHtmlAttribute(jqueryElement, 'data-simple-select', 'boolean', false));

                // If should be selected options first in list
                this.setRenderSetting('selectedFirst', Helper.getHtmlAttribute(jqueryElement, 'data-selected-first', 'boolean', false));

                // Items per scroll, if current items count is less than itemsPerScroll (overflow-y is hidden)
                this.setRenderSetting('itemsPerScroll', Helper.getHtmlAttribute(jqueryElement, 'data-items-per-scroll', 'number', 6));

                // Amount of min. selected items | Not yet
                this.setRenderSetting('minSelectedItems', Helper.getHtmlAttribute(jqueryElement, 'data-min-selected-items', 'number'));


                // Translated word "Select Option"
                this.setRenderSetting('dataTranslationSelect', Helper.getHtmlAttribute(jqueryElement, 'data-translation-select', 'string'));

                // Translated word "Selected" + number of selected
                this.setRenderSetting('dataTranslationSelected', Helper.getHtmlAttribute(jqueryElement, 'data-translation-selected', 'string'));


                // Select default first value
                if (this.getRenderSetting('selectedFirst')) {
                    this.setRenderSetting('options', Helper.sortByBool(this.getRenderSetting('options'), 'visible'));
                }

                // Render select
                vd('render seelct');
                this.renderSelect();

                this.loadFromTemplate();


            },
            /**
             * Render options
             */
            reloadOptions: function () {
                Render.options();
            },
            loadFromTemplate: function () {
                var that = this;
                $("#" + thisElement.id + " option").each(function () {

                    var selected = $(this).is(":selected"); // Helper.getHtmlAttribute($(this), 'data-selected', 'boolean', true);
                    var value = $(this).val(); // Helper.getHtmlAttribute($(this), 'data-value', 'string');
                    var text = $(this).text();
                    var visible = Helper.getHtmlAttribute($(this), 'data-visible', 'boolean', true);
                    var callback = Helper.getHtmlAttribute($(this), 'data-callback', 'string');
                    $(this).data("value", value);
                    $(this).data("selected", selected);

                    that.addOption(value, text, selected, callback, visible);
                });
            },
            /**
             * Setter for renderSettings
             * @param key
             * @param value
             * @returns {*}
             */
            setRenderSetting: function (key, value) {
                this.renderSettings[key] = value;
                return this.renderSettings[key];
            },
            /**
             * Getter for renderSettings
             * @param key
             * @returns {*}
             */
            getRenderSetting: function (key) {
                return this.renderSettings[key];
            },
            /**
             * Same as at the bottom in return.
             * @param url
             * @param data
             */
            getDataFromApiUrl: function (url, data) {
                SearchHelper.downloadFromApi(url, data);
            },
            /**
             * Add options by array (find existing options)
             * @param array
             * @param key
             */
            addOptionsByKey: function (array, key) {
                var options = this.getRenderSetting('options');
                this.getRenderSetting('options').map(function (member) {
                    return member.selected = false;
                });
                for (var i = 0; i < array.length; i++) {
                    var obj = Helper.getObjectFromArrayByKeyValue(options, key, array[i]);
                    if (obj) {
                        obj.selected = true;
                    }
                }
                this.reloadOptions();
            },
            /**
             * Remove non-selected options from options
             * @returns {*}
             */
            clearNonSelectedSettings: function () {
                var selectedOptions = this.getSelectedOptionsObjects();

                return this.setRenderSetting('options', selectedOptions);
            },
            /**
             *
             * @param option
             */
            addToOption: function (array, option) {
                this.renderSettings[array].push(option);
            },
            addOption: function (value, text, selected, callback, visible) {
                this.addToOption('options', Helper.getOptionObject(value, text, selected, callback, visible));

                Render.options();
            },
            /**
             * Render Select <ul>
             */
            renderSelect: function () {
                EventHandler.callBeforeInitEvent();
                this.htmlElement.style.display = "none";
                Render.init(this.renderSettings);
                EventHandler.callOnInitEvent();
            },
            /**
             * Toggle Selected/NoSelected item in list
             * @param e
             */
            selectOption: function (e) {

                var item = e.closest('.item');
                var that = this;


                if (this.getRenderSetting('simpleSelect')) {
                    if (item.classList.contains('selected')) {
                        return;
                    }

                    $("#" + this.htmlId + "-base-wrapper-list li").each(function () {
                        var option = that.getSpecificOption('value', this.getAttribute('data-value'));
                        option.selected = false;
                    });
                }


                item.classList.toggle('selected');
                var option = this.getSpecificOption('value', Helper.getHtmlAttribute($(item), 'data-value', 'string'));
                option.selected = !option.selected;
                item.setAttribute('data-selected', !Helper.getHtmlAttribute($(item), 'data-selected', 'boolean', true));

                Render.options();
                this.printSelectedOptions();
                EventHandler.callOnChangeEvent();

            },
            /**
             * print selected items into header
             */
            printSelectedOptions: function () {
                var selectedOptions = this.getSelectedOptionsObjects();

                var htmlId = document.getElementById(this.htmlId + "-select-text");

                var selectOption = this.getRenderSetting('dataTranslationSelect');
                var selectedOption = this.getRenderSetting('dataTranslationSelected');

                if (selectedOptions.length == 0) {
                    if (selectOption) {
                        htmlId.innerHTML = selectOption;
                    }
                }
                else if (selectedOptions.length == 1) {
                    htmlId.innerHTML = selectedOptions[0].text;
                } else {
                    htmlId.innerHTML = selectedOption + " " + selectedOptions.length;
                }
            },
            /**
             * returns specific option
             * @param key
             * @param value
             */
            getSpecificOption: function (key, value) {
                return this.getRenderSetting('options').find(function (member) {
                    if (member[key] == value) {
                        return member;
                    }
                });
            },
            /**
             * get NonSelectedOptions
             */
            getNonSelectedOptionsObjects: function () {
                return this.getRenderSetting('options').filter(function (member) {
                    if (!member.selected) {
                        return member;
                    }
                });
            },
            /**
             * get SelectedOptions
             */
            getSelectedOptionsObjects: function () {
                return this.getRenderSetting('options').filter(function (member) {
                    if (member.selected) {
                        return member;
                    }
                });
            },
            /**
             * filter options by key/value (when is api disabled, searching in options)
             * @param key
             * @param pattern
             */
            filterOptionByKeyValue: function (key, pattern) {
                this.setRenderSetting('options', Helper.sortByBool(this.getRenderSetting('options'), 'selected'));
                return this.getRenderSetting('options').filter(function (member) {
                    if (member[key].indexOf(pattern) >= 0 || member.selected) {
                        return member;
                    }
                });
            },
            /**
             * trigger on change event
             */
            triggerOnChange: function () {
                vd("triggerd on change");
                EventHandler.callback('onChange', SelectHelper);
                // EventHandler.callOnChangeEvent();
            }
        };

        var Helper = {
            /**
             * Parse Attribute values from html element
             * @param element
             * @param attributeName
             * @param type
             * @param defaultValue
             * @returns {*}
             */
            getHtmlAttribute: function (element, attributeName, type, defaultValue) {
                var result = element.attr(attributeName);

                if (result !== undefined) {
                    if (type === 'boolean') {
                        result = (result === 'true');

                    } else if (type === 'number') {
                        result = parseInt(result);
                    }
                } else {
                    if (defaultValue !== undefined) {
                        result = defaultValue;
                    }
                }

                return result;
            },
            /**
             * Sort by true values
             * @param array
             * @param key
             * @returns {*}
             */
            sortByBool: function (array, key) {
                return array.sort(function (a, b) {
                    return b[key] - a[key];
                })
            },
            /**
             * Returns object of option/selectedOption
             * @param name
             * @param text
             * @param visible
             * @param callback
             * @param value
             * @returns {{text: *, value: *, callback: *, name: *, visible: *}}
             */
            getOptionObject: function (value, text, selected, callback, visible) {

                return {
                    'text': text,
                    'value': value,
                    'callback': callback,
                    'selected': selected,
                    'visible': visible
                };

            },
            /**
             * Determines whether key in array is equal to value
             * @param array
             * @param key
             * @param value
             * @returns {boolean}
             */
            checkIfArrayContainsKeyValue: function (array, key, value) {
                if (this.getObjectFromArrayByKeyValue(array, key, value)) {
                    return true;
                }
                return false;
            },
            getObjectFromArrayByKeyValue: function (array, key, value) {
                for (var i = 0; i < array.length; i++) {
                    if (array[i][key] == value) {
                        return array[i];
                    }
                }
                return null;
            }
        };

        var SearchHelper = {
            oldSuccessPattern: "",
            timer: null,
            /**
             * SearchPattern executed by onKeyUp Event filtering in options
             * @param htmlId
             */
            searchPatternFromOptions: function (htmlId) {
                var that = this;
                var pattern = document.getElementById(htmlId).value;
                if (pattern.length == 0) {
                    var options = SelectHelper.getRenderSetting('options');
                } else {
                    var options = SelectHelper.filterOptionByKeyValue("text", pattern);
                }

                Render.options(options);
            },
            /**
             * SearchPattern executed by onKeyUp Event filtering in api
             * @param htmlid
             * @param apiUrl
             * @param selectFirst
             */
            searchPattern: function (htmlid, apiUrl, selectFirst) {
                var that = this;
                var pattern = document.getElementById(htmlid).value;

                if (this.oldSuccessPattern.length > 0) {

                    if (this.oldSuccessPattern.length == pattern.length && SelectHelper.getRenderSetting('options').length != 0) {
                        return;
                    }

                    if (this.oldSuccessPattern.length < pattern.length && SelectHelper.getRenderSetting('options').length == 0) {
                        return;
                    }

                }

                clearInterval(that.timer);
                that.timer = setInterval(function () {
                    that.returnDataFromApi(pattern, apiUrl, selectFirst);
                    clearInterval(that.timer);
                }, 500);
            },
            /**
             * Returns data from api
             * @param pattern
             * @param apiUrl
             * @param selectFirst
             */
            returnDataFromApi: function (pattern, apiUrl, selectFirst) {
                var url = Graphs.getAjaxURL(false) + "products";
                document.getElementById(SelectHelper.htmlId + "-base").classList.add('searching');


                if (apiUrl != undefined) {
                    url = apiUrl;
                }

                var params = {
                    pattern: pattern,
                    selectFirst: selectFirst
                };

                this.downloadFromApi(url, params);

            },
            /**
             * Download and render options from api
             * @param url
             * @param params
             */
            downloadFromApi: function (url, params) {

                var that = this;

                new MDAjax({
                    url: url,
                    data: params,
                    timeout: 20000,
                    success: function (data) {
                        data.graphApiData = that.clearFromSelected(data.graphApiData);

                        SelectHelper.clearNonSelectedSettings();

                        for (var i = 0; i < data.graphApiData.length; i++) {
                            var obj = data.graphApiData[i];

                            SelectHelper.addToOption('options', Helper.getOptionObject(obj.id, obj.name, false, undefined, true));
                        }

                        var currentOptions = SelectHelper.getRenderSetting('options');

                        Render.options();

                        if (currentOptions.length > 0) {
                            if (params.pattern) {
                                that.oldSuccessPattern = params.pattern;
                            }
                        }

                        EventHandler.callonInitAfterSearchEvent();

                        if (params.selectFirst && data.graphApiData[0]) {

                            if (SelectHelper.getSelectedOptionsObjects().length == 0) {
                                currentOptions[0].selected = true;
                                EventHandler.callOnChangeEvent();
                            }
                        }
                        Render.options();
                        SelectHelper.printSelectedOptions();
                    },
                    error: function (msg) {
                        console.log("Error", msg)
                    }
                });
            },
            /**
             * Returns array of options which are not in selectedOptions
             * @param data
             * @returns {*}
             */
            clearFromSelected: function (data) {
                return data.filter(function (member) {
                    if (!Helper.checkIfArrayContainsKeyValue(SelectHelper.getSelectedOptionsObjects(), "value", member.id)) {
                        return member;
                    }
                });
            }
        };

        var Render = {
            config: {},

            init: function (config) {
                this.config = config;

                this.select();

                if (this.config.searchEnabled) {
                    this.searchBox();
                }
                this.listBox();

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

                base.className = "md-select-search md-select-base-initiated";
                base.id = baseId;

                if (this.config.zIndex) {
                    base.style.zIndex = this.config.zIndex;
                }

                if (this.config.simpleSelect) {
                    base.classList.add("md-select-simple")
                }

                if (!this.config.visible) {
                    base.style.display = "none";
                }


                var title = document.createElement('div');
                title.innerHTML = this.config.placeholder;
                title.className = "md-select-title";
                title.id = this.config.htmlId + "-select-text";

                if (this.config.showSelectIcon) {
                    var icon = document.createElement('div');
                    icon.className = this.config.selectIconClass + " ico";
                    icon.id = this.config.htmlId + "-select-icon";
                }

                var select = document.createElement('div');
                select.className = this.config.className + " md-select-header";
                select.id = this.config.htmlId + "-select";


                select.appendChild(title);

                if (this.config.showSelectIcon) {
                    select.appendChild(icon);
                }

                base.appendChild(select);

                if (this.config.showCloseIcon) {
                    var closeIcon = document.createElement('span');
                    closeIcon.className = "md-icon-delete select-remove";
                    closeIcon.оnclick = (function (rootElement, config) {
                        return function () {
                            rootElement.setAttribute('data-visible', 'false');
                            SelectHelper.reRender();

                            if (config.selectCloseCallback) {
                                var fun = new Function(config.selectCloseCallback);
                                return (fun());
                            }


                        }
                    })(rootElement, this.config);

                    base.appendChild(closeIcon);
                }

                var baseWrapper = document.createElement('div');
                baseWrapper.id = baseId + "-wrapper";
                baseWrapper.className = "md-select-base-wrapper";
                baseWrapper.style.display = "none";
                base.appendChild(baseWrapper);

                rootElement.parentNode.appendChild(base);

            },
            /**
             * Render listbox
             */
            listBox: function () {
                var baseElement = document.getElementById(this.config.htmlId + "-base-wrapper");

                var ul = document.createElement('ul');
                ul.id = baseElement.id + "-list";
                ul.className = "list";


                baseElement.appendChild(ul);

                if (this.config.itemsPerScroll) {
                    this.fixHeight();
                }
            },
            /**
             * Render searchBox
             */
            searchBox: function () {
                var listElement = document.getElementById(this.config.htmlId + "-base-wrapper");

                var div = document.createElement('div');
                div.className = "search-box";

                var searchId = this.config.htmlId + "-search";
                var input = document.createElement('input');
                input.type = "search";
                input.id = searchId;
                input.onkeyup = (function (htmlId, config) {
                    return function () {
                        if (config.apiEnabled) {
                            SearchHelper.searchPattern(htmlId, config.searchApiUrl);
                        } else {
                            SearchHelper.searchPatternFromOptions(htmlId);
                        }
                    }
                })(searchId, this.config);

                var loadingGif = document.createElement('div');
                loadingGif.id = searchId + "-loading";
                loadingGif.className = "search-loading";

                var searchIco = document.createElement('span');
                searchIco.id = searchId + "-ico";
                searchIco.className = "md-icon-store-explorer-icon-16 search-ico";


                div.appendChild(input);
                div.appendChild(searchIco);
                div.appendChild(loadingGif);

                listElement.appendChild(div);

                if (this.config.selectedFirst && this.config.callApiOnInit) {
                    SearchHelper.searchPattern(searchId, this.config.searchApiUrl, true);
                }
            },
            /**
             * Remove all options which are not selected
             */
            clearOptions: function () {
                $("#" + this.config.htmlId + "-base-wrapper-list li").remove();
            },
            /**
             * Render options
             * @param optionList
             */
            options: function (options) {
                Render.clearOptions();
                var htmlId = this.config.htmlId;
                if (options) {
                    var optionList = options;
                } else {
                    var optionList = SelectHelper.getRenderSetting('options');
                }
                var listElement = document.getElementById(htmlId + "-base-wrapper-list");
                for (var i = 0; i < optionList.length; i++) {
                    var item = optionList[i];
                    var liId = htmlId + "-select-" + i;

                    var li = document.createElement('li');
                    li.className = "item flex";
                    li.id = liId;
                    li.setAttribute("data-value", item.value);
                    li.setAttribute("data-text", item.text);
                    li.setAttribute("data-selected", item.selected);

                    if (item.selected) {
                        li.classList.add("selected");
                    }

                    if (!this.config.simpleSelect) {
                        var check = document.createElement('div');
                        check.className = "check";
                        li.appendChild(check);
                    }

                    var label = document.createElement('label');
                    label.title = item.text;
                    label.innerHTML = item.text;

                    var foreground = document.createElement('div');
                    foreground.id = liId + "-foreground";
                    foreground.className = "item-foreground";

                    if (this.config.simpleSelect) {
                        foreground.classList.add("hide-on-click");
                    }


                    foreground.onclick = (function (liId) {
                        return function () {
                            SelectHelper.selectOption(this);
                        }
                    })(liId);


                    li.appendChild(label);

                    li.appendChild(foreground);

                    if (this.config.simpleSelect) {
                        var check = document.createElement('div');
                        // check.textContent = "TEST";
                        check.className = "simple-check";
                        li.appendChild(check);
                    }

                    listElement.appendChild(li);

                }

                document.getElementById(htmlId + "-base").classList.remove('searching');

                if (optionList.length <= SelectHelper.getRenderSetting('itemsPerScroll')) {
                    document.getElementById(htmlId + "-base-wrapper-list").style.overflowY = "hidden";
                } else {
                    document.getElementById(htmlId + "-base-wrapper-list").style.overflowY = "scroll";
                }

                SelectHelper.printSelectedOptions();
            },
            /**
             * Set fixed width
             */
            fixWidth: function () {
                var longestOption = this.getLongestOption();

                var baseElement = document.getElementById(this.config.htmlId + "-base");
                baseElement.style.minWidth = ((longestOption.length * 10) + 40) + "px";

            },
            /**
             * Set listBox fix height according to itemsPerScroll
             */
            fixHeight: function () {
                var baseElement = document.getElementById(this.config.htmlId + "-base-wrapper-list");
                baseElement.style.maxHeight = (this.config.itemsPerScroll * 36) + "px";
            },
            /**
             *
             * @returns {string}
             */
            getLongestOption: function () {
                var option = "";
                for (var i = 0; i < this.config.options.length; i++) {
                    var currentOption = this.config.options[i].value;
                    if (option.length <= currentOption.length) {
                        option = currentOption;
                    }
                }

                return option;
            }

        };

        var EventHandler = {
            /**
             * Check if callback is defined
             * @param event
             * @returns {boolean}
             */
            callbackIsDefined: function (event) {
                if (typeof settings[event] != 'function') {
                    return false;
                }
                return true;
            },
            /**
             * Execute callback and pass reference to it (is accessible in event during initiation)
             * @param event
             * @param reference
             */
            callback: function (event, reference) {
                settings[event].call(reference);
            },
            /**
             * Call beforeInit event
             * Trigger - Before is select rendered
             */
            callBeforeInitEvent: function () {
                if (!this.callbackIsDefined('beforeInit')) {
                    return;
                }

                this.callback('beforeInit', SelectHelper);
            },
            /**
             * Call onChange event
             * Trigger - When is option Changed
             */
            callOnChangeEvent: function () {
                if (!this.callbackIsDefined('onChange')) {
                    return;
                }

                var timer = null;

                var that = this;

                clearInterval(that.timer);
                that.timer = setInterval(function () {
                    that.callback('onChange', SelectHelper);
                    clearInterval(that.timer);
                }, settings.onChangeTime);

            },
            /**
             * Call onInit event
             * Trigger - When is select rendered
             */
            callOnInitEvent: function () {
                if (!this.callbackIsDefined('onInit')) {
                    return;
                }

                var timer = null;

                var that = this;

                clearInterval(that.timer);
                that.timer = setInterval(function () {
                    that.callback('onInit', SelectHelper);
                    clearInterval(that.timer);
                }, settings.onInitTime);

            },
            /**
             * Call onInitAfterSearch event
             * Trigger - When is returned from api
             */
            callonInitAfterSearchEvent: function () {
                if (!this.callbackIsDefined('onInitAfterSearch')) {
                    return;
                }

                this.callback('onInitAfterSearch', SelectHelper);
            }
        };

        /**
         * Determines whether is element child of specific parent or not
         * @param parent
         * @param child
         * @returns {boolean}
         */
        function isDescendant(parent, child) {
            var node = child.parentNode;
            while (node != null) {
                if (node == parent) {
                    return true;
                }
                node = node.parentNode;
            }
            return false;
        }

        /**
         * Determines whether target is in array of elements
         * @param elements
         * @param target
         * @returns {boolean}
         */
        function containsInClass(elements, target) {
            for (var i = 0; i < elements.length; i++) {
                if (isDescendant(elements[i], target)) {
                    return true;
                }
            }
            return false;
        }


        // Initialize only once
        if (!this.hasClass('md-select-initiated')) {
            this.addClass('md-select-initiated');

            /* Executing part */
            SelectHelper.constructor();


            function addOnceClickFunctionToDocument() {
                $(document).mousedown(function (e) {

                    var target = e.target.id;
                    var element = target;

                    element = element.substring(0, element.lastIndexOf("-select"));
                    var base = document.getElementById(element + "-base");


                    var current = $("#" + target);

                    var elements = document.getElementsByClassName('md-select-search');
                    var headers = document.getElementsByClassName('md-select-header');

                    var item = current.closest('.item');

                    // If clicked element contains md-select-search and is not child of md-select-header
                    if (!(current.hasClass("hide-on-click") && !item.hasClass('selected')) && containsInClass(elements, e.target) && current.closest('.md-select-search').hasClass('md-select-opened') && !containsInClass(headers, e.target) && !e.target.classList.contains('md-select-header')) {
                        return;
                    }
                    // Then decide whether show/hide element or not

                    if (base != null) {

                        var ul = document.getElementById(base.id + "-ul");
                        var jBase = $("#" + element + "-base");

                        var wasSelected = jBase.hasClass('md-select-opened');

                        if (current.hasClass('selected') || current.hasClass('disabled')) {
                            return;
                        }


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
                SelectHelper.reRender();
            },
            getBaseSettings: function () {
                return settings;
            },
            getSettings: function () {
                return SelectHelper.renderSettings;
            },
            setSettings: function (key, value) {
                SelectHelper.setRenderSetting(key, value);
            },
            getOptions: function () {
                return SelectHelper.getRenderSetting('options');
            },
            getNonSelectedOptions: function () {
                return SelectHelper.getNonSelectedOptionsObjects();
            },
            getSelectedOptions: function () {
                return SelectHelper.getSelectedOptionsObjects();
            },
            getDataFromApiUrl: function (url, data) {
                SearchHelper.downloadFromApi(url, data);
            },
            addOption: function (value, text, selected, callback, visible) {
                SelectHelper.addOption(value, text, selected, callback, visible);
            }

        };
    };

}(jQuery));