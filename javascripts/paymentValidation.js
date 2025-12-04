
document.querySelector('form').addEventListener('submit', function(e) {
    const cardNumber = document.querySelector('[name="card_number"]').value.replace(/\s+/g, '');
    const expiry = document.querySelector('[name="expiry"]').value;
    const cvv = document.querySelector('[name="cvv"]').value;

    // Validate card number (16 digits)
    if (!/^\d{16}$/.test(cardNumber)) {
        alert("Please enter a valid 16-digit card number.");
        e.preventDefault();
        return;
    }

    // Validate expiry MM/YY and future date
    if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry)) {
        alert("Please enter a valid expiry date in MM/YY format.");
        e.preventDefault();
        return;
    }
    const [month, year] = expiry.split('/');
    const expiryDate = new Date(`20${year}`, month);
    if (expiryDate < new Date()) {
        alert("Card has expired.");
        e.preventDefault();
        return;
    }

    // Validate CVV (3 or 4 digits)
    if (!/^\d{3,4}$/.test(cvv)) {
        alert("Please enter a valid CVV.");
        e.preventDefault();
        return;
    }
});

