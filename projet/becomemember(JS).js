function verif()
{
    n = F.T1.value;
    l = F.T2.value;
    u = F.T5.value;
    if(n =="" || (veriftext(n) == false))
    { 
     alert('Please check your first name!');
    return false;
    }
    else if(l =="" || (veriftext(l) == false))
    {
    alert('Please check your last name!');
    return false;
    }
    else if(F.T3.value =="" || ((F.T3.value.indexOf('@')==-1) && (F.T3.value.indexOf('.')==-1)))
    {
    alert('Please check your email adress!');
    return false;
    }
    else if(F.T4.value =="" || !(F.T4.value==F.T3.value))
    {
    alert('Please verify your email adress!');
    return false;
    }
    if(u =="" || (veriftext(u) == false))
    {
    alert('Please check your user name!');
    return false;
    }
    if(F.T6.value =="" || ((F.T6.value).length<6))
    {
    alert('Please check your password!');
    return false;
    }
    else if(F.T7.value =="" || (((F.T7.value).length!=8) || (isNaN(F.T7.value))))
    { 
    alert('Please check your phone number!');
    return false;
    }
    else if(F.c.selectedIndex==0)
    {
        alert('Please choose your country!');
        return false;
    }
    alert('Welcome to our agency , you are a member now')
    return true;
} 
 function veriftext(ch)
 {
    for (var i=0 ; i<ch.length ; i++)
    {
        var x = ch.charAt(i).toUpperCase();
        if( (x<'A') || (x>'Z') )
        return false;
    }
    return true;
}

