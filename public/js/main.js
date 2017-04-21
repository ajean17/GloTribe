function toggleElement(x)
{
	var x = document.getElementById(x);
	if(x.style.display == 'block')
  {
		x.style.display = 'none';
	}
  else
  {
		x.style.display = 'block';
	}
}
