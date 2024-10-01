var Save = 
{
    Name: "Alex",
}

function ajax(dataToSend) 
{
    return new Promise((resolve, reject) => 
    {
        $.ajax(
        {
            url: '../PHP/RW.php',
            method: 'POST',
            data: {JSONText: JSON.stringify(dataToSend)},
            dataType: 'json',
            success: function(response) 
            {
                resolve(response); 
            },
            error: function(xhr, status, error) 
            {
                console.error(xhr.responseText);
                reject(error);
            }
        });
    });
}




$(document).ready(async function()
{
    $("#EnterName").click(async function () 
    {
        var Name = $("#NameEntry").val();
        console.log(Name);
        Save.Name = Name
        var write = await ajax(Save);
        console.log(write);
    })
});
