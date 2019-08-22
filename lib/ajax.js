var clients = new Array();

function CXMLReq() 
{ 
	//this.freed    = freed; 
	this.xmlhttp    = new XMLHttpRequest(); 
	this.className  = "unknown";
	this.action     = "unknown";
	this.handler    = httpResultParser;
	this.responseId = -1;
	this.data       = "";
} 

function httpRequest(url, className, action, params, retfun)
{
	method = "POST";
	url    = "http://" + location.host + url;
	client = new CXMLReq();
	client.className = className;
	client.action    = action   ;
        if (retfun != null) client.handler = retfun;
        params = "class="  + className  + "&"
               + "action=" + action     + "&"
               + params;
	info("HTTP Request[" + clients.length + "]: " + url    + "\n"
                             + params);


	clients.push(client);
        if (method == "POST")
        {
                client.xmlhttp.open(method, url);
        	client.xmlhttp.onreadystatechange = httpResult; //must be set after "open" and before "send" command
                client.xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                client.xmlhttp.setRequestHeader("Content-Length", params.length);
                //client.xmlhttp.setRequestHeader("Connection", "close");
                client.xmlhttp.setRequestHeader("Accept-Encoding", "*");
                client.xmlhttp.send(params);
        }
/*        else
        {
                client.xmlhttp.open(method, url + "?" + params);
        	client.xmlhttp.onreadystatechange = retfun; //must be set after "open" and before "send" command
                client.xmlhttp.send();
        }
*/
}

function httpResult()
{
    for (i=0; i < clients.length; i++)
    {
	client = clients[i];
	if(client.xmlhttp == undefined) continue;
        if(client.xmlhttp.readyState == 4)
	{
            info("\n" + client.className + "[" + i + "]." + client.action + ": " + client.xmlhttp.status + " HTTP " + client.xmlhttp.statusText);
            if (client.xmlhttp.status == 200) 
            {
                if(client.xmlhttp.responseText != null) // && client.responseXML.getElementById('test').firstChild.data)
			client.handler(client);
                else
                        info("empty backend response.");
		//clients = clients.slice(0,i).concat(clients.slice(i+1)); //remove from array
            }
            //else 
            //{
            //    resultPane.innerHTML += client.className + "." + client.action + ': HTTP Error ' + client.xmlhttp.statusText;
            //}
	    //clients.splice(i,1); i--; 
	    client.xmlhttp.abort();
	    client.xmlhttp = undefined; //uitrangeren
	}
    }
}


function httpResultParser(result)
{
	result.data       = result.xmlhttp.responseText.split("\n");
	result.responseId = result.data[result.data.length-1]; //last line = new id

	//default parsers based upon requested action
	switch (result.action)
	{
		case "query" : fillField(result)              ; break;
		case "insert": updateFields(result)           ;
		default      : httpGenericResultParser(result); 
	}
}

function httpGenericResultParser(result)
{
    info('Backend response:\n' + result.xmlhttp.responseText);
}


/*
 * Adds the newly inserted record into existing dropdownfields with the same fieldname as the formname.
 */
//function updateFields(className, action, id)
function updateFields(request)
{
	form = document.forms[request.className];
	//find first non-hidden field
	for (i=0; i < form.elements.length; i++)
	{
		element = form.elements[i];
		if (element.type == "text") break;
	}
	label = element.value;

	//fields = document.getElementsByClassName(request.className + "sField");
	fields = document.getElementsByTagName("select");

	for (j=0; j < fields.length; j++)
	{
		field = fields[j];
		if (!field.options)           continue; //not a dropdownlist
		if (field.className.indexOf(request.className + "sField") == -1) continue; //wrong dropdownlist
		if (request.action == "insert") 
		{
			//if (field.options.length < 2) getFieldValues(field); 
			field.options[field.options.length] = new Option(label, request.responseId);
		}
		//if (request.action == "delete")
		//if (request.action == "update")
	}
}
