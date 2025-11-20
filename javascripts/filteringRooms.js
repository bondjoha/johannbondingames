// Filter room types according to the selected hotel
const hotelSelect = document.getElementById('hotelSelect');
const roomTypeSelect = document.getElementById('roomTypeSelect');

function filterRoomTypes() 
{
    const hotelId = hotelSelect.value;
    roomTypeSelect.value = '';
    let hasTypes = false;

    roomTypeSelect.querySelectorAll('option').forEach(opt => {
        if (opt.value === '') 
        {
            opt.hidden = false; // placeholder
        } 
        else if (opt.dataset.hotel === hotelId) 
        {
            opt.hidden = false; // show types
            hasTypes = true;
        } 
        else 
        {
            opt.hidden = true; // hide others
        }
    });
    roomTypeSelect.disabled = !hasTypes;
}

// Add event listeners
hotelSelect.addEventListener('change', filterRoomTypes);

window.addEventListener('DOMContentLoaded', () => 
{
    roomTypeSelect.disabled = true;
});
