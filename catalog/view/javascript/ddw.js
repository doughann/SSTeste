DDWFrontEnd = function(admin_mode) {
    var self = this;
    self.admin_mode = admin_mode;
    self.required = false;

    self.getBlockedDates = function(shipping_method_code) {
        var arrBlockedDates = Array() //of DDWCalendarBlockedDate ;
        $.ajax({
            type: 'POST',
            url: "index.php?route=module/deliverydateswizard/get_blocked_dates&token=" + token,
            async: true,
            cache: false,
            data : {
                shipping_method_code : shipping_method_code
            },
            dataType : "json",
            complete: function(d) {
            },
            success: function(jsonData) {
                self.required = jsonData.required;
                if ($("#ddw_calendar").hasClass("hasDatepicker"))
                    $("#ddw_calendar").datepicker("destroy");

                if (typeof jsonData.enabled !== "undefined")
                    if (jsonData.enabled == false) {
                        $("input[name='DDW_date']").val("");
                        $("input[name='DDW_time_slot']").val("");
                        $("#ddw_widget").fadeOut(100);
                        return false;
                    }

                $("#ddw_widget").fadeIn(100);
                $.each(jsonData.calendar_blocked_dates, function (i, blocked_date) {
                    arrBlockedDates.push(blocked_date.date);
                });


                $("#ddw_calendar").datepicker({
                    dateFormat: 'yy-mm-dd',
                    minDate: jsonData.min_date,
                    maxDate: jsonData.max_date,
                    beforeShowDay: function(date) {
                        var dateString = jQuery.datepicker.formatDate('yy-mm-dd', date);
                        return [arrBlockedDates.indexOf(dateString) == -1];
                    },
                    onSelect: function(date) {
                        var date_rockutah = new Date(date);
                        date_rockutah = new Date(date_rockutah.getTime() + date_rockutah.getTimezoneOffset()*60000);
                        $("#DDW_text").html(date_rockutah.getMonth()+1 + "/" + date_rockutah.getDate() + "/" + date_rockutah.getFullYear());
                        //$("#DDW_text").html(date);
                        $("input[name='DDW_date']").val(date);
                        $("input[name='DDW_date']").trigger("change");
                    }
                });
            }
        });

    }

    self.reloadCalender = function(shipping_method_code) {
        self.getBlockedDates(shipping_method_code);
    }

    self.reload_time_slots = function(shipping_method_code) {
        $("input[name='DDW_time_slot']").val("");
        $('input.ddw_time_slot').prop('checked', false);
        $("div.delivery-time-widget").hide();
        $("div.delivery-time-widget[data-shipping-method-code='"+shipping_method_code+"']").show();
    }

    self.reload_translations = function(shipping_method_code) {
        $(".ddw_texts").hide();
        $(".ddw_texts-"+shipping_method_code).show();
    }


    self.blockDates = function(jsonData) {
        for (var d = new Date(startDate); d <= new Date(endDate); d.setDate(d.getDate() + 1)) {
            dateRange.push($.datepicker.formatDate('yy-mm-dd', d));
        }
    }

    $("input[name='shipping_method']").change(function() {
        var shipping_method_code = $(this).attr("id").split(".")[0];
        self.reloadCalender(shipping_method_code);
        self.reload_time_slots(shipping_method_code);
        self.reload_translations(shipping_method_code);
    });

    /* for order entry system */
    $("select[name='shipping']").change(function() {
        var shipping_method_code = $(this).val().split(".")[0];
        self.reloadCalender(shipping_method_code);
        self.reload_time_slots(shipping_method_code);
        self.reload_translations(shipping_method_code);
    });


    $("input.ddw_time_slot").change(function() {
        $("input[name='DDW_time_slot']").val($(this).val());
    });

    /* Validate on add to basket */
    $("#button-shipping-method").bind('click', function(e) {
        if (self.required) {
            error = false;
            //if ($("input[name='DDW_time_slot']").length > 0 && $("input[name='DDW_time_slot']").val() == '') error = true;
            if ($("input[name='DDW_date']").length > 0 && $("input[name='DDW_date']").val() == '') error = true;
			if ($("input[name='ddw_time_slot']:checked").length == 0) error = true;
            if (error) {
                alert(required_error);
                return false;
            }
        }
        return true;
    })


    self.init = function() {
        if (self.admin_mode == true)
            var shipping_method_code = $("select[name='shipping']").val().split(".")[0];
        else
            var shipping_method_code = $("input[name='shipping_method']:checked").val().split(".")[0];

        self.reloadCalender(shipping_method_code);
        self.reload_time_slots(shipping_method_code);
        self.reload_translations(shipping_method_code)

        if (typeof default_time != "undefined") {
            $("input[name='DDW_time_slot']").val(default_time);
            $('input.ddw_time_slot[value="'+default_time+'"]').prop('checked', true);

        }
    }
    self.init();
}


