function verif2()
{
    a = F2.fn.value;
    
    if(F2.lvl.selectedIndex==0)
    {
        alert('please choose your level!');
        return false;
    }
    if(a =="" || !(veriftest(a)))
    {
    alert('Please check your Full name!');
    return false;
    }
    else if(F2.ml.value =="" || ((F2.ml.value.indexOf('@')==-1) && (F2.ml.value.indexOf('.')==-1)))
    {
        alert('Please check your Email adress!');
        return false;
    } 
    alert('Your sign up is completed , we will check your CV and send you an Email soon')
}
function veriftest(ch)
 {
    for (var i=0 ; i<ch.length ; i++)
    {
        var x = ch.charAt(i).toUpperCase();
        if( (x<'A') || (x>'Z') )
        return false;
    }
    return true;
 }

   