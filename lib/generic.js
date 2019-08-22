var IE = true;
var FF = false;
var browser = navigator.userAgent.indexOf("IE") != -1;


function getParentByTagName(child, tagName)
{
        if (child.parentNode == undefined) return undefined;
        if (child.parentNode.tagName != tagName)
                return getParentByTagName(child.parentNode, tagName);
        else
                return child.parentNode;
}


function getChildByClassName(parentNode, className)
{
        for (i=0; i < parentNode.childNodes.length; i++)
        {
		if (parentNode.childNodes[i].className != undefined)
                if (parentNode.childNodes[i].className == className)
			return parentNode.childNodes[i];
        }
        return undefined;
}

function getChildByTagName(parentNode, tagName)
{
        for (i=0; i < parentNode.childNodes.length; i++)
        {
                if (parentNode.childNodes[i].tagName == tagName) return parentNode.childNodes[i];
        }
        return undefined;
}

function onWindowLoad()
{	//force frame if not yet
	if (top == window)
	{
		top.location.href = "/index.php?body=" + escape(location.pathname + location.search);
	}
}

//onWindowLoad();
