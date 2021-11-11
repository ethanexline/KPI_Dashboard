function saveWeatherLocation()
{
    var e = document.getElementById("inlineFormCustomSelect");
    var location = e.options[e.selectedIndex].value;

    if(location != 'NONE')
    {
        local_storage.SetLocalStorage('weather-location', location)
        var saveBtn = document.getElementById('location-msg');
        saveBtn.innerText = 'Success!';
        saveBtn.disabled = true;

        setTimeout(function(){
            document.getElementById('location-msg').innerText = 'Save';
            saveBtn.disabled = false;
        }, 3000);
    }

    else
    {
        local_storage.SetLocalStorage('weather-location', '')
        var saveBtn = document.getElementById('location-msg');
        saveBtn.innerText = 'Success!';
        saveBtn.disabled = true;

        setTimeout(function(){
            document.getElementById('location-msg').innerHTML = 'Save';
            saveBtn.disabled = false;
        }, 3000);
    }
}