
// Get references to the hotel and room type selects
const hotelSelect = document.getElementById('hotelSelect');
const roomTypeSelect = document.getElementById('roomTypeSelect');

// Store all room options in a JS array (avoid duplicates)
const allRoomOptions = Array.from(roomTypeSelect.querySelectorAll('option'))
  .filter(opt => opt.value !== '') // skip placeholder
  .map(opt => ({ type: opt.value, hotel: opt.dataset.hotel }));

// Function to filter room types by selected hotel
function filterRoomTypes() {
  const hotelId = hotelSelect.value;

  // Reset room type select
  roomTypeSelect.innerHTML = '<option value="">-- Select Type --</option>';

  if (!hotelId) {
    roomTypeSelect.disabled = true;
    return;
  }

  const addedTypes = new Set(); // store unique types
  allRoomOptions.forEach(opt => {
    if (opt.hotel === hotelId && !addedTypes.has(opt.type)) {
      const newOption = document.createElement('option');
      newOption.value = opt.type;
      newOption.textContent = opt.type;
      roomTypeSelect.appendChild(newOption);
      addedTypes.add(opt.type);
    }
  });

  roomTypeSelect.disabled = addedTypes.size === 0;
}

// Add event listener
hotelSelect.addEventListener('change', filterRoomTypes);

// Disable room type by default on page load
window.addEventListener('DOMContentLoaded', () => {
  roomTypeSelect.disabled = true;
});