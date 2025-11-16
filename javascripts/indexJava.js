// View Rooms Modal in index page html twig
document.addEventListener("DOMContentLoaded", function() 
{
    document.body.addEventListener("click", function(e) 
    {
        // Check if the user clicks the View Rooms button 
        const btn = e.target.closest(".ViewDetailsButton");
        if (!btn) return; // If the user does not click do not do nothing

        // Obtain the hotel ID and name from the button attributes
        const hotelId = btn.dataset.hotelId;
        const hotelName = btn.dataset.hotelName;

        // Get modal elements
        const modalEl = document.getElementById("roomsModal");
        const modalTitle = modalEl.querySelector(".modal-title");
        const modalBody = document.getElementById("roomsModalBody");

        // Set modal title and loading message
        modalTitle.innerText = hotelName + " - Rooms";
        modalBody.innerHTML = "Loading...";

        // Use POST to fetch the rooms
        fetch("load_rooms.php", 
        {
            method: "POST",
            headers: 
            {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "hotel_id=" + encodeURIComponent(hotelId)
        })
        .then(res => res.text())
        .then(html => 
        {
            // Insert the room details 
            modalBody.innerHTML = html;
            new bootstrap.Modal(modalEl).show();
        })
        .catch(() => 
        {
            // Show error message
            modalBody.innerHTML = "<p class='text-danger'>Unable to load rooms.</p>";
        });
    });

    // City dropdown according to country
    const countrySelect = document.getElementById("countrySelect");
    const citySelect = document.getElementById("citySelect");

    countrySelect.addEventListener("change", function() 
    {
        const country = encodeURIComponent(this.value);
        citySelect.innerHTML = '<option value="">Loading...</option>';

        // show all cities if user do not choose a country
        if (!country) 
        {
            citySelect.innerHTML = '<option value="">All Cities</option>';
            return;
        }

        // obtain cities for the choosen country from database
        fetch("get_cities.php?country=" + country)
            .then(res => res.json())
            .then(data => 
            {
                let options = '<option value="">All Cities</option>';
                data.forEach(city => 
                {
                    options += `<option value="${city}">${city}</option>`;
                });
                citySelect.innerHTML = options;
            })
            .catch(() => 
            {
                // Show error if fetch fails
                citySelect.innerHTML = '<option value="">Unable to load cities</option>';
            });
    });

});
