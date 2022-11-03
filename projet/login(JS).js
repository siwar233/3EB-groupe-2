function verif1()
{
    U = F1.Us.value;
    P = F1.Ps.value;
   if(U=="")
    {
        alert('Please enter your username!');
        return false;
    }
    if(!(U=="Siwar")) 
    {
        alert('Invalid account! To creat an account please click on Sign Up button.');
        return false;
    }
    if(P =="" || !(P ==123456789))
    {
        alert('Password incorrect!');
        return false;
    }

}

