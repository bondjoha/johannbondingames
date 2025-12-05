document.addEventListener("DOMContentLoaded", function() 
{
    // Modal elements
    const modalEl = document.getElementById("roomsModal");
    const roomsModal = new bootstrap.Modal(modalEl);
    const modalTitle = modalEl.querySelector(".modal-title");
    const modalBody = document.getElementById("roomsModalBody");

    // Handle View Rooms click
    document.body.addEventListener("click", function(e) 
    {
        const btn = e.target.closest(".ViewDetailsButton");
        if (!btn) return;

        const hotelId = btn.dataset.hotelId;
        const hotelName = btn.dataset.hotelName;

        // Get selected dates from the search input
        const checkIn = document.querySelector('input[name="check_in"]').value;
        const checkOut = document.querySelector('input[name="check_out"]').value;

        modalTitle.innerText = hotelName + " - Rooms";
        modalBody.innerHTML = "Loading...";

        fetch("load_rooms.php", 
        {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "hotel_id=" + encodeURIComponent(hotelId) +
                  "&check_in=" + encodeURIComponent(checkIn) +
                  "&check_out=" + encodeURIComponent(checkOut)
        })
        .then(res => res.text())
        .then(html => 
        {
            modalBody.innerHTML = html;
            roomsModal.show();
        })
        .catch(() => 
        {
            modalBody.innerHTML = "<p class='text-danger'>Unable to load rooms.</p>";
        });
    });

    // Dynamic City dropdown based on selected country
    const countrySelect = document.getElementById("countrySelect");
    const citySelect = document.getElementById("citySelect");

    countrySelect.addEventListener("change", function() 
    {
        const country = encodeURIComponent(this.value);
        citySelect.innerHTML = '<option value="">Loading...</option>';

        if (!country) 
        {
            citySelect.innerHTML = '<option value="">All Cities</option>';
            return;
        }

        fetch("get_cities.php?country=" + country)
            .then(res => res.json())
            .then(data => 
            {
                let options = '<option value="">All Cities</option>';
                data.forEach(city => options += `<option value="${city}">${city}</option>`);
                citySelect.innerHTML = options;
            })
            .catch(() => 
            {
                citySelect.innerHTML = '<option value="">Unable to load cities</option>';
            });
    });

    // AJAX search to not let page to reload and keep dates
    const searchForm = document.getElementById("searchForm");
    searchForm.addEventListener("submit", function(e) 
    {
        e.preventDefault(); // prevent full page reload
        const formData = new FormData(this);

        fetch("index.php", { method: "POST", body: formData })
            .then(res => res.text())
            .then(html => 
            {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.querySelector(".main-content").innerHTML;
                document.querySelector(".main-content").innerHTML = newContent;
            })
            .catch(err => console.error(err));
    });
});
