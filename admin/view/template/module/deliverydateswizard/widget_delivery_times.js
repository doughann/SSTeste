DDW_Time_Slot = function() {
    language_id = -1,
    text = ""
}

DDW_Time_Slots = {
    collection : Array()
};

DDW_Delivery_Times_Widget = function() {
    var self = this;
    self.widget = $("#widget-delivery-times");
    self.ddw_time_slots = Object.create(DDW_Time_Slots);

    self.widget.find("input.btn-delivery-time-slot-add").click(function() {
        var language_id = $(this).attr("data-language-id");
        var slot_text = $("input[name='delivery-time-slot-"+language_id+"']").val();
        var $active_sort_list = $("ul#delivery-times-"+language_id);

        var $cloned_item = $("ul#deliverytimes-sortlist-template li").clone();
        $cloned_item.find("span.text").html(slot_text);
        $active_sort_list.append($cloned_item);
    });

    /* Copy all time slots in the sortable list to json format which loosely resembles data struct on server side */
    self.transcribe_lists_to_json = function() {
        var language_id = -1;
        var slot_text = "";
        self.ddw_time_slots.collection.length = 0;

        $(self.widget.find(".deliverytimes-widget li")).each(function(i, obj) {
            language_id = $(this).parent("ul").attr("data-language-id");
            slot_text = $(obj).find("span.text").html();

            var time_slot = new DDW_Time_Slot();
            time_slot.language_id = language_id;
            time_slot.text = slot_text;
            self.ddw_time_slots.collection.push(time_slot);
        });
    }

    self.clear = function() {
        $("ul.deliverytimes-widget li").remove();
    }

    /* render items based on objects current data collection */
    self.render = function() {
        self.clear();
        if (typeof self.ddw_time_slots.collection !== "undefined") {
            for (i=0;i<self.ddw_time_slots.collection.length;i++) {
                var $active_sort_list = $("ul#delivery-times-"+self.ddw_time_slots.collection[i].language_id);
                var $cloned_item = $("ul#deliverytimes-sortlist-template li").clone();
                $cloned_item.find("span.text").html(self.ddw_time_slots.collection[i].text);
                $active_sort_list.append($cloned_item);
            }
        }
    }


    $("#btnSave").click(function() {
        self.transcribe_lists_to_json();
    })

    $("ul.deliverytimes-widget .remove").live("click", function() {
        $(this).parents("li").remove();
        return false;
    })

    self.init = function() {
        $(function() {
            $( ".sortable" ).sortable();
            $( ".sortable" ).disableSelection();
        });
    }
    self.init();

}

delivery_times_widget = new DDW_Delivery_Times_Widget();