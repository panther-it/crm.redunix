function info(errMsg)
{
	errMsg = errMsg.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\n/g,"<BR/>");
	resultPane = document.getElementById("result");
	if (resultPane != null) resultPane.innerHTML += errMsg + "<BR/>\n";
}

function error(field,valid,errMsg)
{
	errMsg = errMsg.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\n/g,"<BR/>");
	resultPane = document.getElementById("result");
	if (!valid)
	{ //set error
		//field.style.border = "solid 1px red";
		field.className += " error";
		resultPane.innerHTML += field.name + ": " + errMsg + "<br>\n";
	}
	else
	{ //clear error
		//field.style.border = "";
		field.className = field.className.replace(/ error/g,"");
		resultPane.innerHTML = resultPane.innerHTML.replace(field.name + ": " + errMsg + "<br>\n","");
	}
}



