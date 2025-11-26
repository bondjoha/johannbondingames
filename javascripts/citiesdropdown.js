document.addEventListener('DOMContentLoaded', function () 
{
    const countrySelect = document.querySelector('select[name="country"]');
    const citySelect = document.getElementById('city-select');

    // ⛔ Remove this — it clears Twig cities on page load!
    // citySelect.innerHTML = '<option value="">Select City</option>';

    // Only update when USER changes country
    countrySelect.addEventListener('change', function () 
    {
        const country = this.value;

        // Reset only if user actually changed the country
        citySelect.innerHTML = '<option value="">Select City</option>';

        if (!country) return;

        fetch('get_cities.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `country=${encodeURIComponent(country)}`
        })
        .then(response => response.json())
        .then(data => {
            if (!Array.isArray(data)) return;

            data.forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                citySelect.appendChild(option);
            });
        })
        .catch(err => console.error('Error fetching cities:', err));
    });
});
