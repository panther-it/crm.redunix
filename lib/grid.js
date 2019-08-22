function toggleDisplayFilterRow(link)
{
        table = getParentByTagName(link, "TABLE");
	if (table != undefined)
        {
		row   = getChildByClassName(table.tBodies[0],"filter");
		if (row != undefined)
        		if (row.style.display=="none" || row.style.display=="") row.style.display="table-row"; else row.style.display="none";
	}
}


function toggleDisplayInsertRow(link)
{
        table = getParentByTagName(link, "TABLE");
        row   = getChildByClassName(table.tBodies[0], "insert");
        if (row.style.display=="none" || row.style.display=="") 
	{
		if (browser != IE)
			row.style.display="table-row"; 
		else
			row.style.display="block";
	}
	else 
		row.style.display="none";
}

function toggleSelectRow(row,className)
{
        //row.className = (row.className == "normal") ? "selected" : "normal";
        if (row != undefined && row != null)
	{
		if (row.className != className)
                	row.className = className;
		else
			return; //row already in wanted state
	}

	//fields = row.getElementsByTagName("INPUT");
	//for (field=0; field < fields.length; field++)
	//	eval(fields[0].onfocus + ";onfocus(null);");
	fields = row.getElementsByTagName("SELECT");
	for (f=0; f < fields.length; f++)
	{
		focusEvent = fields[f].onfocus + "";
		focusEvent = focusEvent.replace("function onfocus(event) {","").replace(/^}$/,"");
		focusEvent = focusEvent.replace("this","fields[f]");
		eval(focusEvent);
	}
}

function getValues(form)
{
        var values = "";

        for(i=0; i<form.elements.length; i++)
        {
           field   = form.elements[i];

           if (field.type != "button")
                values += "values[" + field.name + "]=";

           if (field.type == "text" || field.type == "textarea" || field.type == "hidden")
                values += field.value;
           else if(field.type == "checkbox")
                values += field.checked;
           else if(field.type == "select-one")
                values += field.options[field.selectedIndex].value;
           
           if (i+1 < form.elements.length)
                values += "&";
        }

        return values;
}


function doRow(button)
{
        if (button.tagName == "INPUT")
        {
                form = button.form;
                form.elements["action"].value = button.name;
        }
        else
                form = button;

        httpRequest("/mutate.php"
                   ,form.elements["class"].value
                   ,form.elements["action"].value
                   ,getValues(form)
                   );
        row = getParentByTagName(button, "TR");
        //toggleSelectRow(row,"normal"); //leave edit mode/layout
}
