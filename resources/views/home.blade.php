@extends('layouts.app')

@section('content')
<style>
    #idMap {
        height: 180px;
        border-radius: 5px;
    }

</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <div>
                        <p>Your Location</p>
                        <p id="demo"></p>
                    </div>
                    <div id="idMap"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var x = document.getElementById("demo");

    var form = document.getElementById("logoutForm");

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }

    function showPosition(position) {

        x.innerHTML = "Latitude: " + position.coords.latitude +
            "<br>Longitude: " + position.coords.longitude;


        var myMap = L.map('idMap').setView([position.coords.latitude, position.coords.longitude], 13);

         L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                    '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                    'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                maxZoom: 18,
                tileSize: 512,
                zoomOffset: -1,
            }).addTo(myMap);
            // Add the marker for the location
            var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(myMap);
            // Add a popup to the marker
            marker.bindPopup("Ini Lokasi User!").openPopup();

    }

    function showError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                logout()
                x.innerHTML = "Izin lokasi ditolak oleh pengguna.";
                break;
            case error.POSITION_UNAVAILABLE:
                x.innerHTML = "Informasi lokasi tidak tersedia.";
                break;
            case error.TIMEOUT:
                x.innerHTML = "Permintaan lokasi pengguna telah berakhir.";
                break;
            default:
                x.innerHTML = "Terjadi kesalahan saat memperoleh lokasi.";
                break;
        }
    }

    function logout() {
        fetch('/logout', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = '/login';
                } else {
                    console.error('Logout failed');
                }
            })
            .catch(error => {
                console.error('Error during logout', error);
            });
    }

</script>
@endsection
