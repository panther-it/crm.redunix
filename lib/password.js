function checkPassword(field)
{
	error(field,field.value.match(/^[A-Za-z0-9_\-\.\!\@\#\$\^\&\*\(\)\?\~]{4,10}$/),"Invalid; Min 4; Max 10;");
}
