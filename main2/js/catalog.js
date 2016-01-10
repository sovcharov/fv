	
	function createElement()
	{
		//var para=document.createElement("input");
		//para.setAttribute("id", "inputSearchField");
		//para.setAttribute("name", "part");
		//var element=document.getElementById("divSearch");
		//element.insertBefore(para,document.getElementById("submitButton1"));
		setCreatedElement();
		document.getElementById('formTextField').value="";
	}
	
	function setCreatedElement()
	{
		var element=document.getElementById("divSearch");
		element.childNodes[1].style.fontFamily="Verdana";
		element.childNodes[1].style.color="#CFCFCF";
		element.childNodes[1].style.fontWeight="bold";
		element.childNodes[1].style.width="180px";
		textInBox="Поиск в каталоге";
		if (element.childNodes[1].value=="")
		{
			element.childNodes[1].value=textInBox;
		}
		else changeWhenFocus(element, textInBox);		
		//document.getElementById("inputSearchField").value="bla bla bla"; // just tried to get access by id that i assign in second line of func
		element.childNodes[1].style.padding="2px";
		//var marHead=document.getElementById("divHeader").offsetWidth;
		//var marWS=document.getElementById("divWorkspace").offsetWidth;
		//var marHead=marHead-marWS;
		//marHead=marHead+"px";
		//element.style.marginLeft=marHead; 
		element.style.left="155px"//marHead;
		element.style.height="40px";
		element.style.width="450px";
		element.style.position="relative";

		element.childNodes[1].onfocus=function(){changeWhenFocus(element, textInBox)};
		element.childNodes[1].onblur=function(){changeWhenBlur(element,textInBox)};
		subButtonStyle(document.getElementById("submitButton1"));
	}
	function changeWhenFocus(element,txt)
	{
		
		element.childNodes[1].style.background="#F0F0F0";
		if(element.childNodes[1].value==txt)
		{	
		
			element.childNodes[1].value="";				
		}
		element.childNodes[1].style.fontFamily="Arial";
		element.childNodes[1].style.color="black";
		element.childNodes[1].style.fontWeight="normal";
		document.getElementById("submitButton1").style.visibility="visible";	
	}
	function changeWhenBlur(element,txt)
	{
		
		if(!element.childNodes[1].value)
		{
			element.childNodes[1].value=txt;
			element.childNodes[1].style.fontFamily="Verdana";
			element.childNodes[1].style.color="#CFCFCF";
			element.childNodes[1].style.fontWeight="bold";
			document.getElementById("submitButton1").style.visibility="hidden";
		}
		element.childNodes[1].style.background="white";	
		
	}
	function subButtonStyle(x)
	{
		//x.style.border="1px solid black";
		x.style.borderStyle="none";
		//x.style.borderBottom="1px solid black";
		//x.style.borderRight="1px solid black";
		x.style.background="#006282";//"#CF0000";//"#E60000";
		x.value="start";
		x.style.color="White";
		x.style.fontWeight="Bold";
		x.style.fontFamily="Tahoma";
		x.style.marginLeft="3px";
		x.style.height="26px";	
		x.style.width="55px";
		x.style.visibility="hidden";
		//x.style.paddingLeft="3px";
		//x.style.paddingRight="3px";
		
	}
	
	//Here is the section for Cart Massive
	var cartArray;
	//var huj;y
	function addToCart(x,r)
	{	
		window.huj="fdsa";
		//alert(window.cartArray);
		var elId="tdQ"+x;
		var myRow = document.getElementById('myTable').rows[r].cells;
		
		if(!window.cartArray)
		{
			window.cartArray=new Array();
			window.cartArray[0]=new Array(2);
			window.cartArray[0][0]=x;
			window.cartArray[0][1]=1;
			myRow[5].innerHTML=window.cartArray[0][1];
			
		}
		else
		{
			var p=window.cartArray;
			arLen=p.length;
			for(i in p)
			{
				if (x==p[i][0])
				{
					p[i][1]++;
					var addedQty=true;
					myRow[5].innerHTML=p[i][1];
				}				
			}
			if(!addedQty)
			{
				p[arLen]=new Array(2);
				p[arLen][0]=x;
				p[arLen][1]=1;
				myRow[5].innerHTML=p[arLen][1];
			}
		}

		//z=window.cartArray.length;
		var m="Хер тебе";//window.huj;
		//alert(window.cartArray);		
		document.getElementById('paraTestArray').innerHTML=window.cartArray;	
		
	}
	
	function showCart()
	{
		var strToForm;
		for (i in window.cartArray)
		{
			if(!strToForm)
			{
				strToForm=window.cartArray[i][0]+" " + window.cartArray[i][1];
			}
			else
			{
				strToForm=strToForm + " " + window.cartArray[i][0]+" " + window.cartArray[i][1];
			}			
		}
		//y=window.cartArray;
		y=strToForm;
		if (y)
		{
			document.getElementById('formTextField').value=y;
		}
		//document.getElementById('paraTestArray').innerHTML=document.getElementById('formTextField').value;
	}
	
	