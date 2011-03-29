/* 
This file is part of the Easy FTP Upload WordPress plugin
Copyright 2011 Jenny Chalek
Current version 2.7
http://www.bucketofwombats.com/easy-ftp-upload-for-wordpress
*/
//--Global variables
	var EFU_Options_Array;

//--Main functions

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

function EFU_validateAdmin() {
    var valMsg = "";
    var valEmail = "";
    var valAlert = "";
    var frmOK = true;
    var x=document.getElementById("EFU_admin_form");
    for (var i=0;i<x.length;i++) {
        y = x.elements[i];
	y.style.borderColor = "inherit";
        clsX = y.className;
        if (clsX == "EFU_required"){
            if (y.value == "" || y.value == null) {
                y.style.borderColor = "red";
                frmOK = false;
                valMsg = "One or more required fields has not been completed.";
            } else {
		y.style.borderColor = "inherit";
	    }
	} else if (clsX == "EFU_email_required") {
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

function EFU_Admin_Menu_Create(passedArray) {
    EFU_Options_Array = passedArray;
    //put the array passed into a global variable for future use
    
    var selectbox = document.getElementById("efu_accounts");
    for (var aIndex in EFU_Options_Array) {
	var aOptNew = document.createElement('option');
	aOptNew.text = aIndex;
	aOptNew.value = aIndex;
	//aOptNew.text = EFU_Options_Array[aIndex];
	//aOptNew.value = EFU_Options_Array[aIndex];
	selectbox.add(aOptNew,null);
	//document.write( aIndex + " : " + EFU_Options_Array[aIndex] + "<br />");
    }
	
    efuSelectAccount();
}

//--Helper functions

function efuAddNew() {
	var aNew = "Account1";
	var selectbox = document.getElementById("efu_accounts");
	var i;
	var iFound = "false";
	for(i=selectbox.options.length-1;i>=0;i--)
	{
		if(selectbox.options[i].text == aNew){
			selectbox.options[i].selected = "true";
			iFound = "true";
		}
	}
	if (iFound == "false") {
		var newItem = selectbox.add(aNew);
		newItem.selected = "true";
	}
}

function efuDelete() {
	var selectbox = document.getElementById("efu_accounts");
	var i;
	for(i=selectbox.options.length-1;i>=0;i--)
	{
		if(selectbox.options[i].selected)
		selectbox.remove(i);
	}
}

function efuSelectAccount() {
	var sIndex = document.getElementById("efu_accounts").selectedIndex;
	var AcctChoice = document.getElementById("efu_accounts")[0];
	//var AcctChoice = document.getElementById("efu_accounts")[sIndex];
	//document.getElementById("efu_account_name").value = equivalent;
	//document.getElementById("efu_shortcode").value = '[easy_ftp_upload account="'+equivalent+'"]';
	document.getElementById("efu_account_name").value = AcctChoice.text;
	document.getElementById("efu_shortcode").value = '[easy_ftp_upload account="'+AcctChoice.text+'"]';
	//populate all other boxes with info from this account
	var AccountArray = EFU_Options_Array[AcctChoice.text];
	document.getElementById("efu_server_name").value = AccountArray["efu_server"];
	document.getElementById("efu_user_name").value = AccountArray["efu_username"];
	document.getElementById("efu_password").value = AccountArray["efu_user_pass"];
	document.getElementById("efu_email").value = AccountArray["efu_notify"];
}