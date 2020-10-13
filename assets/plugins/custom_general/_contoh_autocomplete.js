var droppoint = function(){
    this.getArea = function() {
        // clear previous autocomplete (if any)
        $("#droppoint-area").val('');
        $("#droppoint-area").autocomplete({source: []});

        // start ajax to get area
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $("input[name=_token]").val()
            },
            async: true,
            type: 'POST',
            url: '/droppoint/area',
            data : {
                city: $("#droppoint-city").val()
            },
            complete: function (data) {
                var area = $.parseJSON(data.responseText);
                var areaArray = $.map(area, function(el) { return el });
                $("#droppoint-area").html('');

                var cg = new customGeneral();
                cg.autoComplete(areaArray, "#droppoint-area");

                // auto open the dropdown
                $("#droppoint-area").autocomplete("search", "");
            }
        });
    };

    this.getDroppoint = function () {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $("input[name=_token]").val()
            },
            async: true,
            type: 'POST',
            url: '/droppoint/getHtml',
            data : {
                city: $("#droppoint-city").val(),
                area: $("#droppoint-area").val()
            },
            complete: function (data) {
                var html = data.responseText;
                $('#droppoint-result').html(html);
                $('#droppoint-result .carousel-container').effect("bounce", {}, 3000);
            }
        });
    };
}

var customGeneral = function(){
    this.autoScroll = function(className, offset) {
        if($(className).length != 0) {
            $('html, body').animate({
                scrollTop: $(className).offset().top + offset
            }, 1000);
        }
    };

    this.autoComplete = function (array, id) {
        $(id).autocomplete({
            source: array,
            autoFocus: true,
            scroll: true,
            minLength: 0,
            change: function (ev, ui) {
                if (!ui.item) {
                    var found = false;
                    jQuery.each(array, function(i,v) {
                        if($(id).val() == v.toLowerCase()) {
                            found = true;
                            $(id).val(v);
                            return false;
                        }
                    });

                    if(!found){
                        $(this).val('');
                    }
                }
            }
        }).focus(function() {
            if($(this).val() == '') {
                $(this).autocomplete("search", "");
            }else{
                $(this).autocomplete("search", $(this).val());
            }
        });
    };

    this.autoCompleteNoDropdown = function (array, id) {
        $(id).autocomplete({
            source: array,
            autoFocus: true,
            scroll: true,
            minLength: 0,
            change: function (ev, ui) {
                if (!ui.item) {
                    var found = false;
                    jQuery.each(array, function(i,v) {
                        if($(id).val() == v.toLowerCase()) {
                            found = true;
                            $(id).val(v);
                            return false;
                        }
                    });
                }
            }
        });
    };

    this.postAutoComplete = function (array, id) {
        var postIdElementName = id + '_id';
        $(id).autocomplete({
            source: array,
            autoFocus: true,
            select: function (ev, ui) {
                if(ui.item){
                    $(postIdElementName).val(ui.item.id);
                }
            },
            change: function (ev, ui) {
                if (!ui.item) {
                    var found = false;
                    jQuery.each(array, function(i,v) {
                        if($(id).val().toLowerCase() == v.value.toLowerCase()) {
                            found = true;
                            $(id).val(v.value);
                            $(postIdElementName).val(v.id);
                            return false;
                        }
                    });

                    if(!found){
                        $(this).val('');
                    }
                }
            }
        });
    }
}

var deliverForm = function(){
    this.numericValue = '';

    this.initiateCheckNumeric = function(e) {
        this.numericValue = e.value;
    };

    this.checkNumeric = function(e){
        var maxLength = e.maxLength;

        if (maxLength > 0 && e.value.length > maxLength) {
            e.value = e.value.slice(0, maxLength)
        }

        if(e.value == '') {
            this.numericValue = '';
        }else if($.isNumeric(e.value)){
            this.numericValue = e.value;
        } else {
            e.value = this.numericValue;
        }

        return true;
    };

    this.validateMinMax = function(e) {
        var maxValue = e.attr("max");
        var minValue = e.attr("min");

        if(maxValue != '' && parseInt(e.val()) > parseInt(maxValue)){
            return true;
        }else if(minValue != '' && parseInt(e.val()) < parseInt(minValue)){
            return true;
        }

        return false;
    };
}