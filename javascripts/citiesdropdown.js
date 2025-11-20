document.addEventListener('DOMContentLoaded', function () 
{
    // country  and city names
    const countrySelect = document.querySelector('select[name="country"]');
    const cityWrapper = document.getElementById('city-wrapper');
    const citySelect = document.getElementById('city-select');

    // Listen for changes in the country dropdown
    countrySelect.addEventListener('change', function () 
    {
        // Get the selected country
        const country = this.value;

        // Remove the city dropdown and options
        if (!country) 
        {
            cityWrapper.style.display = 'none'; // hide city wrapper
            citySelect.innerHTML = ''; // remove all city options
            return; 
        }

        // Display the corresponding cities to the choosen countrty
        fetch('get_cities.php', 
        {
            method: 'POST', 
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, // proper header for form data
            body: `country=${encodeURIComponent(country)}` // sending security
        })
        .then(response => response.json()) // parse response as JSON
        .then(data => 
        {
            // remove city options and write "All Cities" 
            citySelect.innerHTML = '<option value="">All Cities</option>';

            // Add cities the city 
            data.forEach(city => 
            {
                const option = document.createElement('option'); // new option element
                option.value = city; // set the value attribute
                option.textContent = city; // set the visible text
                citySelect.appendChild(option); // add to the dropdown
            });

            // Show dropdown 
            cityWrapper.style.display = 'block';
        })
        .catch(err => console.error('Error fetching cities:', err)); // log any errors to the console
    });
});
