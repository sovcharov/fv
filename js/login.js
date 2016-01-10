	function checkEmail(x,y)
	{
		var regExpr = /^[A-Za-z0-9]+((([.\-_])[A-Za-z0-9]+)?)*@[A-Za-z0-9]+((([.\-_])[A-Za-z0-9]+)?)*\.[A-Za-z]{2,4}$/;
		var z=document.getElementById("divSubmitButton2");
		if(regExpr.test(x.value) && checkPass()) 
		{
			y.innerHTML="Email принят!";
			document.getElementById("errorPass").innerHTML="Пароль принят!";
			y.style.background="#B8FFDB";
			document.getElementById("errorPass").style.background="#B8FFDB";
			removeButton(z);		
			var para=document.createElement("input");
			para.setAttribute("type", "submit");
			para.setAttribute("value", "Log in");
			para.setAttribute("id", "submitButton2");
			z.appendChild(para);
		}
		else if (regExpr.test(x.value))
		{
			y.innerHTML="Email принят!";
			document.getElementById("errorPass").innerHTML="Введите правильный пароль!";
			y.style.background="#B8FFDB";
			document.getElementById("errorPass").style.background="#FFC2B2";
			removeButton(z);		
		}
		else if(checkPass()) 
		{
			y.innerHTML="Введите правильный email!";
			document.getElementById("errorPass").innerHTML="Пароль принят!";
			y.style.background="#FFC2B2";
			document.getElementById("errorPass").style.background="#B8FFDB";
			removeButton(z);		
		}
		else
		{
			y.innerHTML="Введите правильный email!";
			document.getElementById("errorPass").innerHTML="Введите правильный пароль!";
			y.style.background="#FFC2B2";
			document.getElementById("errorPass").style.background="#FFC2B2";
			removeButton(z);		
		}		
	}
	
	function checkPass()
	{
		var regExpr = /^[A-Za-z0-9]{5,}$/;
		var x = document.getElementById("inputPasswordField").value;
		if(!regExpr.test(x))
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