document.addEventListener('DOMContentLoaded', function ()
{
    const checkInInput = document.getElementById('check-in');
    const checkOutInput = document.getElementById('check-out');

    // Prevent past dates for check-in
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const todayStr = `${yyyy}-${mm}-${dd}`;

    checkInInput.min = todayStr;

    function updateCheckOutMin(checkInDate) 
    {
        if (!checkInDate) return;
        const minCheckOut = new Date(checkInDate);
        minCheckOut.setDate(minCheckOut.getDate() + 1); // Check-out must be after check-in
        const yyyy = minCheckOut.getFullYear();
        const mm = String(minCheckOut.getMonth() + 1).padStart(2, '0');
        const dd = String(minCheckOut.getDate()).padStart(2, '0');
        const minCheckOutStr = `${yyyy}-${mm}-${dd}`;

        checkOutInput.min = minCheckOutStr;

        // Auto-update check-out if it's before new minimum
        if (checkOutInput.value && checkOutInput.value < minCheckOutStr) 
        {
            checkOutInput.value = minCheckOutStr;
        }
    }

    // Update check-out minimum whenever check-in changes
    if (checkInInput.value) updateCheckOutMin(checkInInput.value);
    checkInInput.addEventListener('change', function () {
        updateCheckOutMin(this.value);
    });
});
