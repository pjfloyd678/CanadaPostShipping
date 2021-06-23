function isValidNumber(value) {
    var check = /^[0-9]{1,12}$/.test(value);
    return check;
}
function isValidDecimal(value) {
    var check = /^\s*(?=.*[1-9])\d*(?:\.\d{1,2})?\s*$/.test(value);
    return check;
}
function isValidDate(value) {
    var check = /^(19|20)\d\d[/](0[1-9]|1[012])[/](0[1-9]|[12][0-9]|3[01])$/.test(value);
    return check;
}
function isValidString(value) {
    var check = /^[a-zA-Z0-9 -]*$/.test(value);
    return check;
}
function isValidDescription(value) {
    var check = /^[a-zA-Z0-9 -.,/]*$/.test(value);
    return check;
}
function isValidAddress(value) {
    var check = /^(\d*|)([ -]|).*(\d*|)$/.test(value);
    return check;
}
function isValidPostalOrZIP(value) {
    var check = /^[ABCEGHJKLMNPRSTVXY]{1}\d{1}[A-Z]{1}\d{1}[A-Z]{1}\d{1}$/.test(value);
    if (!check) {
        check = /^\d{5}(-\d{4})?$/.test(value);
    }
    return check;
}
function isValidPhone(value) {
    var check = /^(\(|)\d{3}(\)|)([ -]|)\d{3}([ -]|)\d{4}|$/.test(value);
    return check;
}
function isPersonsName(value) {
    var check = /^[\w\.\']{2,}([\s][\w\.\']{2,})+$/.test(value);
    return check;
}
function isValidEmail(value) {
    var check =/^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/.test(value);
    return check;
}

function validateForm(inFormID) {
    var count = 0;
    
    var formID = $("#" + inFormID).attr("id");
    $('#'+formID).find(':input').each(function() {
        var valid = true;
        var itemID = $(this).attr("id");
        var value = $(this).val();
        var required = $(this).data("required");
        
        if (value === "") {
            if (required) {
                valid = false;
            } else {
                valid = true;
            }
        }
        if (valid) {
            if (value !== "") {
                if ((itemID === "email") || (itemID === "new-email")) {
                    valid = isValidEmail(value);
                } else if ((itemID === "startpostal") 
                        || (itemID === "endpostal")) {
                    valid = isValidPostalOrZIP(value);
                } else if (itemID === "phone") {
                    valid = isValidPhone(value);
                } else if (itemID === "weight") {
                    valid = isValidNumber(value);
                } else {
                    valid = isValidAddress(value);
                }
            }
        }
        if (valid) {
            $("#" + itemID).css("border-color", "lightgrey");
            $("#" + itemID).css("border-width", "1px");
            $("#" + itemID).css("border-style", "solid");
        } else {
            $("#" + itemID).css("border-color", "red");
            $("#" + itemID).css("border-width", "2px");
            $("#" + itemID).css("border-style", "solid");
            count++;
        }
    });
    if (count > 0) {
        return false;
    }
    return true;
}

$(document).ready(function () {
    $("#submitform").on("click", function (evt) {
        if ( validateImgCaptcha() ) {
            if ( validateForm("form") ) {
                $("#form").submit();
            } else {
                $("#form").submit(false);
            }
        } else {
            return false;
        }
    });
});
