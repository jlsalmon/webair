function departureDateChecker(){
    var mydate=new Date()
    var year=mydate.getYear()

    if (year < 1000)
        year+=1900

    var day=mydate.getDay()
    var month=mydate.getMonth()+1

    if (month<10)
        month="0"+month

    var daym=mydate.getDate()
    if (daym<10)
        daym="0"+daym

    var today = String(year)+"/"+String(month)+"/"+String(daym);
    var frm = document.forms["booking"];

    var selectedDate_array = frm.departure_date.value.split("/");
    var selectedDateReverse = selectedDate_array[2]+"/"+selectedDate_array[1]+"/"+selectedDate_array[0];

    if(selectedDateReverse < today)
    {
        return false;
    }
    else {
        return true;
    }
}

       
function returnDateChecker()
{
    var mydate=new Date()
    var year=mydate.getYear()

    if (year < 1000)
        year+=1900

    var day=mydate.getDay()
    var month=mydate.getMonth()+1

    if (month<10)
        month="0"+month

    var daym=mydate.getDate()
    if (daym<10)
        daym="0"+daym

    var today = String(year)+"/"+String(month)+"/"+String(daym);
    var frm = document.forms["booking"];

    var selectedDate_array = frm.return_date.value.split("/");
    var selectedDateReverse = selectedDate_array[2]+"/"+selectedDate_array[1]+"/"+selectedDate_array[0];
    if(selectedDateReverse < today)

    {
        return false;
    }
    else {
        return true;
    }
}

function flightDateContinuity()
{
    var frm = document.forms["booking"];
    var departureDate_array = frm.departure_date.value.split("/");
    var departureDateReverse = departureDate_array[2]+"/"+departureDate_array[1]+"/"+departureDate_array[0];
    var returnDate_array = frm.return_date.value.split("/");
    var returnDateReverse = returnDate_array[2]+"/"+returnDate_array[1]+"/"+returnDate_array[0];

    if (returnDateReverse < departureDateReverse)
    {
        return false;
    }
    else {return true;}

}

function DoCustomValidation()
{
    var frm = document.forms["booking"];
    if(false == departureDateChecker())
    {
        sfm_show_error_msg('The departure date you have entered is in the past');
        return false;
    }
    else
    if(false == returnDateChecker())
    {
        sfm_show_error_msg('The return date you have entered is in the past');
        return false;
    }
    else
    if (false == flightDateContinuity())
    {
        sfm_show_error_msg('The return date cannot be before the departure date');
        return false;
    }
    else
    {
        return true;
    }
}