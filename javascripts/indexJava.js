document.addEventListener("DOMContentLoaded", function() 
{
    document.body.addEventListener("click", function(e) 
    {
        // Check if the user clicks the View Rooms button 
        const btn = e.target.closest(".ViewDetailsButton");
        if (!btn) return; // If the user does not click do not do nothing

        // Obtain the hotel ID and name from the button attributes
        const hotelId = encodeURIComponent(btn.dataset.hotelId);
        const hotelName = btn.dataset.hotelName;

        // Get modal elements
        const modalEl = document.getElementById("roomsModal");
        const modalTitle = modalEl.querySelector(".modal-title");
        const modalBody = document.getElementById("roomsModalBody");

        // Set modal title and loading message
        modalTitle.innerText = hotelName + " - Rooms";
        modalBody.innerHTML = "Loading...";

        // Get the room details from database according to the hotel ID
        fetch("load_rooms.php?hotel_id=" + hotelId)
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

    // Update city dropdown according to country 
    const countrySelect = document.getElementById("countrySelect");
    const citySelect = document.getElementById("citySelect");

    countrySelect.addEventListener("change", function() 
    {
        const country = this.value;
        citySelect.innerHTML = '<option value="">Loading...</option>';

        // show all cities incase country is not selected
        if (!country) 
        {
            citySelect.innerHTML = '<option value="">All Cities</option>';
            return;
        }

        // Send POST request to get_cities.php file
        fetch("get_cities.php", 
        {
            method: "POST",
            headers: 
            {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "country=" + encodeURIComponent(country)
        })
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
            // Show error message
            citySelect.innerHTML = '<option value="">Unable to load cities</option>';
        });
    });

});
