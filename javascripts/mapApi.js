document.addEventListener("DOMContentLoaded", function () 
{
    var hotelLat = window.HOTEL_DATA.lat;
    var hotelLon = window.HOTEL_DATA.lon;
    var hotelName = window.HOTEL_DATA.name;

    var map = L.map('hotel-map').setView([hotelLat, hotelLon], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', 
    {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    L.marker([hotelLat, hotelLon])
        .addTo(map)
        .bindPopup(hotelName)
        .openPopup();
});
