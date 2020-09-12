$(document).ready(function () {
	history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
 $('#sidebarCollapse').on('click', function () {
     $('#sidebar').toggleClass('active');
     $(this).toggleClass('active');
 });
});
function fetch_select(val)
{
    $.ajax({
    type: 'post',
    url: 'fetch_device.php',
    data: {
     get_option:val
    },
    success: function (response) {
     document.getElementById("drop-description").innerHTML=response;
     document.getElementById("drop-description").innerHTML+="<option selected>Other</option>"
    }
    });
}
function fetch_select2(val)
{
    $.ajax({
    type: 'post',
    url: 'fetch_device.php',
    data: {
     get_option:val
    },
    success: function (response) {
     document.getElementById("spec").innerHTML=response;
    }
    });
}
function Device()
{
	val = document.getElementById('drop-name').value;
	if(val=="Other")
	{
		document.getElementById('hide-drop-name').disabled=false;
		document.getElementById('alert-server-new-device').value="1";
	}
	else
	{
		document.getElementById('hide-drop-name').disabled=true;
		document.getElementById('alert-server-new-device').value="0";
	}
}
function Company()
{
	val = document.getElementById('drop-other-company').value;
//	alert(val.innerHTML	);
	if(val == "Other")
	{
		document.getElementById('hide-drop-other-company').disabled=false;
		document.getElementById('alert-server-new-company').value="1";
	}
	else
	{
//		alert(0);
		document.getElementById('hide-drop-other-company').disabled=true;
		document.getElementById('alert-server-new-company').value="0";
	}
}
function Supplier()
{

	val = document.getElementById('drop-supplier').value;
	if(val=="Other")
	{
		document.getElementById('other-supplier').disabled=false;
		document.getElementById('alert-server-new-supplier').value="1";
	}
	else
	{
		document.getElementById('other-supplier').disabled=true;
		document.getElementById('alert-server-new-supplier').value="0";	
		
	}
}
function Description()
{
	val = document.getElementById('drop-description').value;
	if(val=="Other")
	{
		document.getElementById('other-description').disabled=false;
		document.getElementById('alert-server-new-description').value="1";
	}
	else
	{
		document.getElementById('other-description').disabled=true;
		document.getElementById('alert-server-new-description').value="0";	
		
	}
}
function Name()
{
	val = document.getElementById('drop-name').value;
	if(val=="Other")
	{
		document.getElementById('other-device').disabled=false;
		document.getElementById('alert-server-new-device').value="1";
	}
	else
	{
		document.getElementById('other-device').disabled=true;
		document.getElementById('alert-server-new-device').value="0";	
	}
}
function Number(val)
{
	n=/^[0-9]+$/;
	if(n.test(document.getElementById(val).value)||document.getElementById(val).value=="")	
	{
		document.getElementById('error').innerHTML=null;
		return true;
	}
	else
	{
		document.getElementById('error').innerHTML="Invalid Input";
		document.getElementById(val).value="";
		return false;
	}
}
function Names(val)
{
	n=/^[a-zA-Z ]+$/;
	if(n.test(document.getElementById(val).value)||document.getElementById(val).value=="")	
	{
		document.getElementById('error').innerHTML=null;
		return true;
	}
	else
	{
		document.getElementById('error').innerHTML="Invalid Input";
		document.getElementById(val).value="";
		return false;
	}
}
function Size(val)
{
	var n=/\d\s\wb$/i;
	if(n.test(document.getElementById(val).value)||document.getElementById(val).value=="")
	{
		document.getElementById('error').innerHTML=null;
		return true;
	}
	else
	{
		document.getElementById('error').innerHTML="Invalid Input";
		document.getElementById(val).value="";
		return false;
	}
}
function Other(val)
{
	var o=/\bother\b/i;
	if(o.test(document.getElementById(val).value))
	{
		document.getElementById('error').innerHTML="Invalid Input";
		document.getElementById(val).value="";
		return false;
	}
	else
	{
		document.getElementById('error').innerHTML=null;
		return true;
	}
}
function contact(val)
{
	n=/^[0-9]+$/;
	m=document.getElementById(val).value;
	if(n.test(document.getElementById(val).value)||document.getElementById(val).value=="")	
	{
		if(m.length==10||document.getElementById(val).value=="")
		{
			document.getElementById('error').innerHTML=null;
			return true;
		}
		else
		{
			document.getElementById('error').innerHTML="10 Digits only";
			document.getElementById(val).value="";
			return false;
		}
	}
	else
	{
		document.getElementById('error').innerHTML="Invalid Input";
		document.getElementById(val).value="";
		return false;
	}
}
function newp(val)
{
	m=document.getElementById(val).value;
	if(m.length==8||m.length>8||document.getElementById(val).value=="")	
	{
		document.getElementById('error').innerHTML=null;
		return true;
	}
	else
	{
		document.getElementById('error').innerHTML="Min. 8 characters";
		document.getElementById(val).value="";
		return false;
	}
}
function conp(val)
{
	if(document.getElementById(val).value==document.getElementById('npswrd').value||document.getElementById(val).value=="")	
	{
		document.getElementById('error').innerHTML=null;
		return true;
	}
	else
	{
		document.getElementById('error').innerHTML="Password did not match";
		document.getElementById(val).value="";
		return false;
	}
}
function labs(val)
{
	n=/^[a-zA-Z0-9 ]+$/;
	if(n.test(document.getElementById(val).value)||document.getElementById(val).value=="")	
	{
		document.getElementById('error').innerHTML=null;
		return true;
	}
	else
	{
		document.getElementById('error').innerHTML="Invalid Input";
		document.getElementById(val).value="";
		return false;
	}
}
function Purpose(val)
{
	var o=/^[0-9]+$/;
	if(o.test(document.getElementById(val).value))
	{
		document.getElementById('error').innerHTML="Invalid Input";
		document.getElementById(val).value="";
		return false;
	}
	else
	{
		document.getElementById('error').innerHTML=null;
		return true;
	}
}
function check(val)
{
	var m=/^[0-9]+$/;
	if(m.test(document.getElementById(val).value))
	{
		if(document.getElementById(val).value<document.getElementById('mcs').value)
		{
			document.getElementById('error').innerHTML="Computer No.(to) should not be less than Computer No.(from)";
			document.getElementById(val).value="";
			return false;
		}
		else
		{
			document.getElementById('error').innerHTML=null;
			return true;
		}
	}
	else
	{
		document.getElementById('error').innerHTML="Invalid Input";
		document.getElementById(val).value="";
		return false;
	}
}
