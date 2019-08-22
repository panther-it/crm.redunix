function showTab(name)
{
	activeBlock = document.getElementById(name);
	blocks = [];
	if (document.getElementsByClassName)
		blocks      = document.getElementsByClassName(activeBlock.className);
	else if (document.getElementsByTagName)
		blocks	    = document.getElementsByTagName("div");
	for(i=0; i < blocks.length; i++)
		if (blocks[i].className.indexOf("tab") != -1)
			blocks[i].style.display = "none";
	activeBlock.style.display   = "block";
}


