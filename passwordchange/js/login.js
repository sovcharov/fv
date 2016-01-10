	function checkEmail()
	{
		var z=document.getElementById("divSubmitButton2");
		if(checkPass2() && checkPass3()) 
		{
			document.getElementById("errorPass2").innerHTML="Пароль принят!";
			document.getElementById("errorPass3").innerHTML="Пароль принят!";
			document.getElementById("errorPass2").style.background="#B8FFDB";
			document.getElementById("errorPass3").style.background="#B8FFDB";
			removeButton(z);		
			var para=document.createElement("input");
			para.setAttribute("type", "submit");
			para.setAttribute("value", "Сменить");
			para.setAttribute("id", "submitButton2");
			z.appendChild(para);
		}
		else if (checkPass2())
		{
			document.getElementById("errorPass2").innerHTML="Пароль принят!";
			document.getElementById("errorPass3").innerHTML="Подтвердите пароль!";
			document.getElementById("errorPass2").style.background="#B8FFDB";
			document.getElementById("errorPass3").style.background="#FFC2B2";
			removeButton(z);		
		}
		else
		{
			document.getElementById("errorPass2").innerHTML="Введите правильный пароль не менее 5 символов!";
			document.getElementById("errorPass3").innerHTML="Подтвердите пароль!";
			document.getElementById("errorPass2").style.background="#FFC2B2";
			document.getElementById("errorPass3").style.background="#FFC2B2";
			removeButton(z);		
		}		
	}	
	function checkPass2()
	{
		var regExpr = /^[A-Za-z0-9]{5,}$/;
		var x = document.getElementById("password2").value;
		if(!regExpr.test(x))
		{
			return false;
		}
		else return true;
	}
	function checkPass3()
	{
		var x = document.getElementById("password2").value;
		var y = document.getElementById("password3").value;
		if(x!=y)
		{
			return false;
		}
		else return true;
	}
	
	function removeButton(z)
	{
			while(z.firstChild)
			{
				z.removeChild(z.firstChild)
			}			
	}