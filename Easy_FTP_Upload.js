function EFU_validateForm() {
    var valMsg = "";
    var valEmail = "";
    var valAlert = "";
    var frmOK = true;
    var x=document.getElementById("EFU_main_form");
    for (var i=0;i<x.length;i++) {
        y = x.elements[i];
		y.style.borderColor = "inherit";
        clsX = y.className;
        if (clsX == "EFU_text_req" || clsX == "EFU_file_req" || clsX == "EFU_textarea_req"){
            if (y.value == "" || y.value == null) {
                y.style.borderColor = "red";
                frmOK = false;
                valMsg = "One or more required fields has not been completed.";
            } else {
				y.style.borderColor = "inherit";
			}
		} else if (clsX == "EFU_text_req_email") {
			var atpos=y.value.indexOf("@");
			var dotpos=y.value.lastIndexOf(".");
			if (atpos<1 || dotpos<atpos+2 || dotpos+2>=y.value.length) {
                frmOK = false;
                y.style.borderColor = "red";
                valEmail = "You must enter a valid email address.";
			} else {
				y.style.borderColor = "inherit";
				valEmail = "";
			}
		}
    }
    if (frmOK == false) {
        if (!valMsg == "") {
            valAlert = valMsg;
        }
        if (!valEmail == "") {
            if (valAlert == "") {
                valAlert = valEmail;
            } else {
                valAlert = valAlert + "\n" + valEmail;
            }
        }
        valAlert = valAlert + "\n" + "Please correct the fields outlined in red.";
        alert(valAlert);
        return false;
    }
    return true;
}