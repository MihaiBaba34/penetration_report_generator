/**
 * DHTML date validation script for dd/mm/yyyy. Courtesy of SmartWebby.com (http://www.smartwebby.com/dhtml/datevalidation.asp)
 */
// Declaring valid date character, minimum year and maximum year
var dtCh = "/";
var minYear = 1900;
var maxYear = 2100;

function isInteger(s) {
    var i;
    for (i = 0; i < s.length; i++) {
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function stripCharsInBag(s, bag) {
    var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++) {
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}


function daysInFebruary(year) {
    // February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ((!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28);
}

function DaysArray(n) {
    for (var i = 1; i <= n; i++) {
        this[i] = 31
        if (i == 4 || i == 6 || i == 9 || i == 11) {
            this[i] = 30
        }
        if (i == 2) {
            this[i] = 29
        }
    }
    return this
}


function isDate(dtStr) {
    var daysInMonth = DaysArray(12)
    var pos1 = dtStr.indexOf(dtCh)
    var pos2 = dtStr.indexOf(dtCh, pos1 + 1)
    var strDay = dtStr.substring(0, pos1)
    var strMonth = dtStr.substring(pos1 + 1, pos2)
    var strYear = dtStr.substring(pos2 + 1)
    strYr = strYear
    if (strDay.charAt(0) == "0" && strDay.length > 1) strDay = strDay.substring(1)
    if (strMonth.charAt(0) == "0" && strMonth.length > 1) strMonth = strMonth.substring(1)
    for (var i = 1; i <= 3; i++) {
        if (strYr.charAt(0) == "0" && strYr.length > 1) strYr = strYr.substring(1)
    }
    month = parseInt(strMonth)
    day = parseInt(strDay)
    year = parseInt(strYr)
    if (pos1 == -1 || pos2 == -1) {
        alert("The date format should be : dd/mm/yyyy")
        return false
    }
    if (strMonth.length < 1 || month < 1 || month > 12) {
        alert("Please enter a valid month")
        return false
    }
    if (strDay.length < 1 || day < 1 || day > 31 || (month == 2 && day > daysInFebruary(year)) || day > daysInMonth[month]) {
        alert("Please enter a valid day")
        return false
    }
    if (strYear.length != 4 || year == 0 || year < minYear || year > maxYear) {
        alert("Please enter a valid 4 digit year between " + minYear + " and " + maxYear)
        return false
    }
    if (dtStr.indexOf(dtCh, pos2 + 1) != -1 || isInteger(stripCharsInBag(dtStr, dtCh)) == false) {
        alert("Please enter a valid date")
        return false
    }
    return true
}

function ValidateForm() {
    var inputFields = new Object();

    var dateAndTimeField = document.getElementById("dateInput");
    var serverNameField = document.getElementById("serverNameInput");
    var webURLField = document.getElementById("webURLInput");

    if (serverNameField.value == "" || serverNameField.value.length < 3) {
        alert("Server Name must be at least 3 characters long");
        serverNameField.focus();
        return false;
    } else {
        inputFields["serverNameInput"] = serverNameField.value;
    }

    if (webURLField.value == "" || webURLField.value.length < 3) {

        inputFields["webURLInput"] = "Not Set";
    } else {
        inputFields["webURLInput"] = webURLField.value;
    }

    if (isDate(dateAndTimeField.value) == false) {
        dateAndTimeField.focus();
        return false;
    } else {
        inputFields["dateAndTime"] = dateAndTimeField.value;
    }

    startProcessing(inputFields);
    return true
}

function startProcessing(inputArgument) {

      var input_fields= JSON.stringify(inputArgument);
    localStorage.setItem("input_fields",input_fields);

    $.ajax({
            type: "POST",
            url: "./php/process_files_and_generate_output.php",
            data: {

                files_map: uploaded_files_map,
                input: input_fields
            }
   	})
	.done(function( msg ) {


		var received_data = JSON.parse(msg);
        var url = received_data.url_to_html_output;        

        //save the global array received from php in order
        //to access it globally
        var storage_array = JSON.stringify(received_data.global_array);
        localStorage.setItem("global_array",storage_array);

		window.location.href = url;
	});
 }


// prevent Start Processing button from triggering upload file window
function preventDefaultFunction(event) {
    clickOnMainAreaFlag = true;
    event.preventDefault();

    if (uploaded_files_map.length > 0) {
        ValidateForm();
    } else {
        alert("Please add at least one XML file!");
    }
}