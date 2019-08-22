resultPane = document.getElementById("result");

function setError(field,description, isError)
{
	if ((isError === undefined) || (isError == true))
	{
		field.style.border = "solid 1px red";
		resultPane.innerHTML = field.name + ": " + description + "\n";
	}
	else
	{
		field.style.border = "";
		resultPane.innerHTML = resultPane.innerHTML.replace(field.name + ": " + description,"");
	}
}


function checkForm(form)
{
	resultPane = document.getElementById("result");
	errMsg     = "";
	passed     = true;

	//check if fieldName exists in resultPane errormessages
	for (i=0; i < form.elements.length; i++)
	{
		field = form.elements[i];
		if (field.onchange != undefined)
		{
			 field.onchange();
			if (resultPane.innerHTML.match(field.name + ":"))
			{
				if (errMsg == "") //first message/field 
					errMsg = "Field '" + field.name + "' is still invalid.\nPlease correct this before submitting.\n";
				passed = false;
			}
		}
	}

	if (!passed)
	{
		alert(errMsg);
		return false;
	}
	else
		return true;
}


function required(field)
{
	if (field.value != undefined)
		error(field,field.value.match(/^.+$/),"required");
}
