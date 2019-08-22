function textSelectPressed(field)
{
        if(window.event) // IE
        {
                keynum = e.keyCode
        }
        else if(e.which) // Netscape/Firefox/Opera
        {
                keynum = e.which
        }

        alert(keynum);

        keychar    = String.fromCharCode(keynum)
        inputvalue = field.value + keychar;
        list       = listValues[field.name];
        listwin    = document.getElementById(field.name + "_list");

        listWin.innerHTML        = "";
        listwin.style.visibility = "visible";

        for (i=0; i < list.count; i++)
        {
                listrow   = list[i];
                listid    = listorw[0];
                listlabel = listrow[1];

                if (listlabel.indexOf(value))
                {
                        listwin.innerHTML += "<li onClick=\"textSelectListRowClicked(this);\"" 
                                           + "    value  =\"" + listid + "\">" + listlabel . "</li>\n";
                }
        }


}

function textSelectListRowClicked(li)
{
        listid           = li.value;
        listlabel        = li.innerText;
        list             = li.parent;
        //fieldname      = li.parent.id.replace(/_list$/,"");
        labelfield       = list.previousSibling();
        idfield          = labelfield.previousSibling();
        labelfield.value = listlabel;
        idfield.value    = listid;
        list.style.visibility = "hidden";
}
