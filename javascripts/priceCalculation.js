document.addEventListener('DOMContentLoaded', () => 
{
    const checkInInput = document.getElementById('checkIn');
    const checkOutInput = document.getElementById('checkOut');
    const nightsSpan = document.getElementById('nights');
    const totalPriceSpan = document.getElementById('totalPrice');
    const pricePerNight = parseFloat(document.getElementById('roomPrice').textContent);

    function updatePrice() 
    {
        const checkIn = new Date(checkInInput.value);
        const checkOut = new Date(checkOutInput.value);
        if (checkIn && checkOut && checkOut > checkIn) 
        {
            const diffTime = checkOut - checkIn;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            nightsSpan.textContent = diffDays;
            totalPriceSpan.textContent = (diffDays * pricePerNight).toFixed(2);
        } 
        else 
        {
            nightsSpan.textContent = 0;
            totalPriceSpan.textContent = '0.00';
        }
    }

    // Checking inputs if they change
    checkInInput.addEventListener('change', updatePrice);
    checkOutInput.addEventListener('change', updatePrice);

    // Start calculation when the page loads
    updatePrice();
});