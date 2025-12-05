const slides = ['slide1','slide2','slide3']; // Slider array
const texts = ['text1','text2','text3']; // Slider text
let currentSlide = 0; // reset to 0 

// Function to show and hide slide 
function showSlide(index)
{
    slides.forEach((id, i) => 
    {      
        document.getElementById(id).style.opacity = (i === index) ? '1' : '0'; // Showing slide     
        document.getElementById(texts[i]).style.opacity = (i === index) ? '1' : '0'; // Showing text
    });
}

showSlide(currentSlide);

// Every 8 seconds the slide changes
setInterval(() => 
{
    currentSlide = (currentSlide + 1) % slides.length;  // Move next slide
    showSlide(currentSlide); // display

}, 
7000); // 7000ms
