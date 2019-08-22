/*
 * Description: sends request to php backend to get the values belonging in this dropdownlist 
 */
function getFieldValues(field, className, value)
{
	if (!field.options)           return false; //not a dropdownlist
	//if (field.options.length > 1) return false; //already filled
		
	if (className == undefined) className="DropDownListField";
	key = field.getAttribute("ID");

        httpRequest("/mutate.php"
                   ,className
                   ,"query"
                   ,"value=" + value + "&values=" + key
                   ,null);
//                   ,fillField);

}

/*
 * Description: gets values from php backend in key===values\n form.
                fills select/dropdownlist with those values.
 */
function fillField(response)
{
			rows       = response.data; 
			if (document.getElementsByClassName)
				fields     = document.getElementsByClassName(rows[0]);
			else
				fields	   = document.getElementsByTagName("select");
			//if (response.xmlhttp.responseText.length < 400) resultPane.innerHTML += response.xmlhttp.responseText; //debugging
			info(rows[1]); //debug first value 

			for (j=0; j < fields.length; j++)
			{
			field = fields[j];
			if (!field.options)             continue; //not a dropdownlist
			if (field.className.indexOf(rows[0]) == -1) continue; //not correct dropdownlist
			selectedIndex = field.selectedIndex;
			field.options.length = 0; //clean out old values

			for (y=1;y < rows.length; y++)
			{
			    values = rows[y].split("===");
			    if (values[1] != undefined)
			        field.options[field.options.length] = new Option(values[1],values[0]);
			}
			if (selectedIndex < field.options.length);
				field.selectedIndex = selectedIndex;
			}
}
