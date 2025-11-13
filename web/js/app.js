var app = {
    changeStatus: function (url, trigger, id) {
        $(".global-loader").show();
        var status = 0;
        if ($('#' + trigger.id).is(":checked")) {
            status = 1;
        }

        $.ajax({
            type: "GET",
            url: baseUrl + url,
            data: {
                "id": id
            },
            success: function (res) {
                $(".global-loader").hide();
                if (res == 0) {
                    location.reload();
                } else {
                    var growlType = $(this).data('growl');
                    $.growl.notice({ title: "Success", message: "Status Updated Successfully!" });
                    // toastr["success"]("Success", "Status Updated Successfully");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(".global-loader").hide();
                alert(jqXHR.responseText);
            }
        });
    },
    addValidation: function (form_id, id, name, container, help_block, message, validation_type, operator, compare_value, compare_attribute, compare_attribute_name, compare_type) {
        //var validationInterval =  setInterval(() => {
//            if (typeof $('#w0').data('yiiActiveForm') !== 'undefined') {
        jQuery("#" + form_id).yiiActiveForm("add", {
            "id": id,
            "name": name,
            "container": container,
            "input": "#" + id,
            "error": help_block,
            "validate": function (attribute, value, messages, deferred, $form) {
                if (validation_type == "required") {
                    yii.validation.required(value, messages, {
                        "message": message
                    });
                } else if (validation_type == "compareValue") {
                    yii.validation.compare(value, messages, {
                        "operator": operator,
                        "type": "string",
                        "compareValue": compare_value,
                        "skipOnEmpty": 1,
                        "message": message
                    }, $form);
                } else if (validation_type == "compareAttribute") {
                    if (typeof compare_type == 'undefined') {
                        compare_type = "string";
                    }

                    yii.validation.compare(value, messages, {
                        "operator": operator,
                        "type": compare_type,
                        "compareAttribute": compare_attribute,
                        "compareAttributeName": compare_attribute_name,
                        "skipOnEmpty": 1,
                        "message": message
                    }, $form);
                } else if (validation_type == "number") {
                    yii.validation.number(value, messages, {
                        "pattern": /^\s*[+-]?\d+\s*$/,
                        "message": message,
                        "skipOnEmpty": 1
                    }, $form);
                } else if (validation_type == "double") {
                    yii.validation.number(value, messages, {
                        "pattern": /^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/,
                        "message": message,
                        "skipOnEmpty": 1
                    }, $form);
                }
            }
        });
        //clearInterval(validationInterval);
//            } else {
//                console.log('form not initializing');
//            };
        //}, 100);
    },
    removeValidation: function (form_id, id,) {
        jQuery("#" + form_id).yiiActiveForm('remove', id);
    },
}