var dateRange = {
    ddwd_id : -1,
    ddw_id : -1,
    date_start : "",
    date_end : "",
    type : "",
    recurring : false
}

var settings = {
    ddw_id : -1,
    shipping_method_code : "",
    enabled : false,
    required : false,
    weekdays : "",
    min_days : 0,
    max_days : 30,
    cut_off_time_enabled : false,
    cut_off_time_hours : 0,
    cut_off_time_minutes : 0,
    datesCollection : Array(), //Of Dates
    text : "",
    delivery_times : DDW_Time_Slots
};


/* Ajax structures (Loosely match server side structs */

JsonResultCode = {
    Undefined : -1,
    GeneralError : 0,
    OK : 1
}

var JsonResult = {
    errors : Array(),
    result_code : 1,
    data : JsonResultCode.Undefined
}

DDWEditClass = function() {
    var self = this;
    self.frmSettings = $("form[name='frmSettings']");
    self.frmDate = $("#frmDate");
    self.objSettings = Object.create(settings); //the global page settings prior to saving server side

    self.load_start = function() {
        $("div#settings").addClass("loading");
    }

    self.load_end = function() {
        $("div#settings").removeClass("loading");
    }

    self.rebindElements = function() {
        self.frmDate = $("#frmDate");
    }

    self.success_show = function(json_result) {
        $('div.success').show(200).delay(3000).fadeOut(200);
    }

    /* Load the block dates dynamically via Ajax/html Request */
    self.refreshDateList = function() {
        $.ajax({
            type: 'POST',
            url: ajaxURL + "&action=refresh_date_list",
            async: true,
            cache: false,
            data : {
              shipping_method_code : self.frmSettings.find("input[name='shipping_method_code']").val()
            },
            dataType : "html",
            complete: function(d) {
                self.rebindElements();
            },
            success: function(html) {
                $("#widgetDaysBlocked").html(html);
            }
        });
    }

    self.updateDateListItem = function(objDDWDate) {
        $.ajax({
            type: 'POST',
            url: ajaxURL + "&action=updateDDWDate",
            async: true,
            cache: false,
            data : objDDWDate,
            //dataType : "html",
            complete: function(d) {
            },
            success: function(jsonData) {
                self.refreshDateList();
                self.success_show();
            }
        });
    }

    self.deleteDateListItem = function(ddwd_id) {
        $.ajax({
            type: 'POST',
            url: ajaxURL + "&action=deleteDDWDate",
            async: true,
            cache: false,
            data : "ddwd_id="+ddwd_id,
            //dataType : "html",
            complete: function(d) {
            },
            success: function(jsonData) {
                self.rebindElements();
                self.refreshDateList();
                self.success_show();
            }
        });
    }

    self.update_ck_editors = function() {
        for(var instanceName in CKEDITOR.instances){
            CKEDITOR.instances[instanceName].updateElement();
        }
    }

    self.reset_form = function() {
        self.frmSettings.find("select").prop("selectedIndex",0);
        self.frmSettings.find("input[type='checkbox']").prop("checked", false);
        self.frmSettings.find("input[type='text']").val("");
        self.frmSettings.find("textarea").html("");
        self.frmSettings.find("tr.date-collection-item").remove();
        delivery_times_widget.clear();
    }

    self.displaySettings = function(objSettings) {
        self.reset_form();

        self.frmSettings.find("input[name='shipping_method_code']").val(objSettings.shipping_method_code);

        if (objSettings.ddw_id == "") return false;

        self.frmSettings.find("select[name='required']").val(Number(objSettings.required));
        self.frmSettings.find("select[name='enabled']").val(Number(objSettings.enabled));

        if (objSettings.shipping_method_code != "")
            self.frmSettings.find("input[name='shipping_method_code']").val(objSettings.shipping_method_code);

        self.frmSettings.find("input[name='min_days']").val(objSettings.min_days);
        self.frmSettings.find("input[name='max_days']").val(objSettings.max_days);
        if (objSettings.cut_off_time_enabled == 1)
            self.frmSettings.find("input[name='cut_off_time_enabled']").prop("checked", true);
        else
            self.frmSettings.find("input[name='cut_off_time_enabled']").prop("checked", false);

        self.frmSettings.find("select[name='cut_off_time_hours']").val(objSettings.cut_off_time_hours);
        self.frmSettings.find("select[name='cut_off_time_minutes']").val(objSettings.cut_off_time_minutes);

        /* set the weekdays */
        var arrWeekdays = objSettings.weekdays.split(",");
        for (i=0; i < arrWeekdays.length; i++) {
            self.frmSettings.find("input[name='weekdays[]'][id='" + arrWeekdays[i] + "']").attr("checked", "checked");
        }

        /* Translations */
        if (typeof objSettings.translations.text_collection !== "undefined") {
            $.each( objSettings.translations.text_collection, function( key, item ) {
                var language_id = key;
                $.each(item, function( key_2, value_2 ) {
                    $("#language"+language_id).find($("input[name='" + key_2 + "_" + language_id + "']")).val(value_2.text);
                    $("#language"+language_id).find($("textarea[name='" + key_2 + "_" + language_id + "']")).html(value_2.text);
                    instance_name = key_2 + "_" + language_id;
                    if (typeof CKEDITOR.instances[instance_name] !== "undefined") {
                        CKEDITOR.instances[instance_name].setData(value_2.text);
                    }
                });
            });
        } else {
            for(var instance_name in CKEDITOR.instances)
                CKEDITOR.instances[instance_name].setData("");
        }

        /* Copy time slots from model to the widgets collection property, ready for rendering by the widget */
        if (typeof objSettings.delivery_times !== "undefined") {
            delivery_times_widget.ddw_time_slots.collection.length = 0;
            for (i=0;i<objSettings.delivery_times.length;i++) {
                var ddw_time = new DDW_Time_Slot();
                ddw_time.language_id = objSettings.delivery_times[i].language_id;
                ddw_time.text = objSettings.delivery_times[i].text;
                delivery_times_widget.ddw_time_slots.collection.push(ddw_time);
            }
            delivery_times_widget.render();
        }


        $("input[name='cut_off_time_enabled']").change();
        self.refreshDateList();
    }

    self.createSettingsObjectFromForm = function() {
        var objSettings = Object.getPrototypeOf(self.objSettings);
        objSettings.shipping_method_code = self.frmSettings.find("input[name='shipping_method_code']").val();
        objSettings.enabled = self.frmSettings.find("select[name='enabled']").val();
        objSettings.required = self.frmSettings.find("select[name='required']").val();
        objSettings.min_days = self.frmSettings.find("input[name='min_days']").val();
        objSettings.max_days = self.frmSettings.find("input[name='max_days']").val();

        if (self.frmSettings.find("input[name='cut_off_time_enabled']").is(":checked"))
            objSettings.cut_off_time_enabled = 1;
        else
            objSettings.cut_off_time_enabled = 0;

        objSettings.cut_off_time_hours = self.frmSettings.find("select[name='cut_off_time_hours']").val();
        objSettings.cut_off_time_minutes = self.frmSettings.find("select[name='cut_off_time_minutes']").val();

        objSettings.weekdays = self.frmSettings.find('input[name="weekdays[]"]:checked').map(function(_, el) {
            return $(el).val();
        }).get();

        /* serialiise translations */
        for(var instanceName in CKEDITOR.instances)
            CKEDITOR.instances[instanceName].updateElement(); //force all ckeditors to copy text to their respective textareas
        objSettings.text = $('td.text input[type="text"], td.text textarea').serialize();

        if (typeof delivery_times_widget.delivery_times !== "undefined") {
            objSettings.delivery_times = delivery_times_widget.delivery_times;
        }
        return self.objSettings;
    }

    self.saveSettings = function(objSettings) {
        self.load_start();
        $.ajax({
            type: 'POST',
            url: ajaxURL + "&action=saveSettings",
            async: true,
            cache: false,
            //dataType : "json",
            data: objSettings,
            complete: function(d) {
                objSettings.datesCollection = Array();
            },
            success: function(jsonData) {
                self.refreshDateList();
                self.success_show();
                self.load_end();
                return false;
                /*var jsonResult = Object.create(JsonResult);
                jsonResult = jsonData;
                self.loadSettings(jsonResult.data);*/
            }
        });
    }

    self.loadSettings = function(shipping_method_code) {
        self.load_start();
        $.ajax({
            type: 'POST',
            url: ajaxURL,
            async: true,
            cache: false,
            dataType : "json",
            data: 'action=loadSettings&shipping_method_code=' + shipping_method_code,
            complete: function() {
            },
            success: function(jsonData) {
                var shippingSettings = Object.create(settings).jsonData;
                shippingSettings = jsonData;
                shippingSettings.shipping_method_code = shipping_method_code;
                self.displaySettings(shippingSettings);
                self.load_end();
            }
        });
    }


    /* Events */

    $("div#listShippingMethods a").click(function() {
        $("#listShippingMethods a").removeClass("selected");
        $(this).addClass("selected");
        self.frmSettings.find("input[name='shipping_method_code']").val($(this).attr("id"));
        self.loadSettings($(this).attr("id"));
        return false;
    });

    $("#widgetDaysBlocked a.update").live("click", function() {
        var i = 0;
        var item = $(this).parents("tr");
        var objDate = Object.create(dateRange);
        objDate.ddwd_id = item.attr("id");
        objDate.recurring = Number(item.find("input[name='recurring']").is(":checked"));
        objDate.type = item.find("select[name='type']").val();
        objDate.date_start = item.find("input[name='date_start']").val();
        objDate.date_end = item.find("input[name='date_end']").val();
        self.updateDateListItem(objDate);
        return false;
    })

    $("#widgetDaysBlocked a.delete").live("click", function() {
        var item = $(this).parents("tr");
        self.deleteDateListItem(item.attr("id"));
        return false;
    })

    /*
    Add new date range toi Dates collection object in the Settings Object
     */
    $(self.frmDate).find("a.add").live("click", function() {
        var objDate = Object.create(dateRange);
        objDate.date_start = self.frmDate.find("input[name='date_start']").val();
        objDate.date_end = self.frmDate.find("input[name='date_end']").val();
        objDate.type = self.frmDate.find("select[name='type']").val();
        objDate.recurring = self.frmDate.find("input[name='recurring']").is(":checked");
        self.objSettings.datesCollection.push(objDate);

        self.saveSettings(self.createSettingsObjectFromForm());
        self.refreshDateList();
        return false;
    })

    $("#btnSave").click(function() {
        self.saveSettings(self.createSettingsObjectFromForm());
        return false;
    })

    $("input[name='cut_off_time_enabled']").change(function() {
        if ($(this).is(":checked")) {
            $("#cut_off_time_panel").css({opacity:1});
            $("#cut_off_time_panel").find("*").attr("disabled", false);
        } else {
            $("#cut_off_time_panel").css({opacity:0.5});
            $("#cut_off_time_panel").find("*").attr("disabled", true);
        }
    })

    $("#widgetDaysBlocked select[name='type']").live("change", function() {
        var $txt_date_end = $("input[name='date_end']");
        if ($(this).val() == "single") $txt_date_end.hide();
        else $txt_date_end.show();
    });

    self.init = function() {
        self.loadSettings("");
        $('#languages a').tabs();
        $('#widget-deliverytimes-languages a').tabs();
    }
    self.init();
}