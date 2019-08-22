function checkPhone(field)
{
	field.value = field.value.replace(/[+]/g,"00");
	field.value = field.value.replace(/[ .-]/g,"");
	error(field,field.value.match(/[0-9]{6,20}/),"numeric; min=6; max=20;");
}
