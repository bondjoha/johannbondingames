document.addEventListener("DOMContentLoaded", () => {
    const countrySelect = document.getElementById("country-select");
    const citySelect = document.getElementById("city-select");
    const starSelect = document.getElementById("star-select");

    async function updateStarRatings() {
        const country = countrySelect.value;
        const city = citySelect.value;

        if (!country && !city) return; // nothing to update

        // Save previous selected star value
        const previousStar = starSelect.value;

        try {
            const response = await fetch(`searchStarRatingsAjax.php?country=${encodeURIComponent(country)}&city=${encodeURIComponent(city)}`);
            const ratings = await response.json();

            // Clear current options
            starSelect.innerHTML = '<option value="">Any</option>';

            if (ratings.length > 0) {
                ratings.forEach(r => {
                    const opt = document.createElement("option");
                    opt.value = r.Star_Rating;
                    opt.textContent = `${r.Star_Rating} Star${r.Star_Rating > 1 ? "s" : ""}`;
                    starSelect.appendChild(opt);
                });
            } else {
                // fallback: 1-5 stars
                for (let i = 1; i <= 5; i++) {
                    const opt = document.createElement("option");
                    opt.value = i;
                    opt.textContent = `${i} Star${i > 1 ? "s" : ""}`;
                    starSelect.appendChild(opt);
                }
            }

            // Restore previous selection if still available
            if ([...starSelect.options].some(o => o.value === previousStar)) {
                starSelect.value = previousStar;
            }

        } catch (err) {
            console.error("Failed to load star ratings:", err);
        }
    }

    countrySelect.addEventListener("change", updateStarRatings);
    citySelect.addEventListener("change", updateStarRatings);
});
