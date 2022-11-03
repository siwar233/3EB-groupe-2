function verif3()
{
    d = F3.yn.value;
    k = F3.sb.value;
    h = F3.ms.value;
    if(d =="" || (verif3text(d) == false))
    {
    alert('Please check your name!');
    return false;
    }
    
    if(F3.ye.value =="" || (F3.ye.value.indexOf('@')==-1 && F3.ye.value.indexOf('.')==-1))
    {
    alert('Please check your Email!');
    return false;
    }
    
    if(k =="" || (verif3text(k) == false))
    {
    alert('Please check the subject!');
    return false;
    }
    
    if(h =="" || (verif3text(h) == false))
    {
    alert('Please check the message!');
    return false;
    }
    alert('your message sent seccessfully');
}


function verif3text(ch)
 {
    for (var i=0 ; i<ch.length ; i++)
    {
        var x = ch.charAt(i).toUpperCase();
        if( (x<'A') || (x>'Z') )
        return false;
    }
    return true;
}