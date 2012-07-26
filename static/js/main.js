function $class(classname)
{
	return document.getElementsByClassName(classname);
}

function toggle(el)
{
	if(el.style.display == "block")
		el.style.display = "none";
	else
		el.style.display = "block";
}
