document.addEventListener('DOMContentLoaded', () => 
{
    document.querySelectorAll('[id^="carousel-"]').forEach(carousel => 
    {
        const slides = Array.from(carousel.children);
        let index = 0;
        const total = slides.length;

        // Clone first slide for smooth looping
        if (total > 1) 
        {
            const firstClone = slides[0].cloneNode(true);
            carousel.appendChild(firstClone);
        }

        // Previous or next buttons
        const carouselId = carousel.id.split('-')[1];
        const prevBtn = document.querySelector(`[data-carousel="${carouselId}"][data-action="prev"]`);
        const nextBtn = document.querySelector(`[data-carousel="${carouselId}"][data-action="next"]`);

        function updateSlide() 
        {
            carousel.style.transition = 'transform 0.5s ease';
            carousel.style.transform = `translateX(-${index * 100}%)`;
        }

        function nextSlide() 
        {
            index++;
            updateSlide();

            if (index > total - 1)
            {
                // After transition, reset to start instantly
                setTimeout(() => 
                {
                    carousel.style.transition = 'none';
                    carousel.style.transform = 'translateX(0)';
                    index = 0;
                }
                , 500); // transition time
            }
        }

        function prevSlide() 
        {
            if (index === 0) 
            {
                carousel.style.transition = 'none';
                carousel.style.transform = `translateX(-${total * 100}%)`;
                index = total - 1;
                setTimeout(() => 
                {
                    carousel.style.transition = 'transform 0.5s ease';
                    index--;
                    updateSlide();
                }, 50);
            } 
            else 
            {
                index--;
                updateSlide();
            }
        }

        // Next button
        if (nextBtn) nextBtn.addEventListener('click', nextSlide);

        // Previous button
        if (prevBtn) prevBtn.addEventListener('click', prevSlide);

        // Auto-play every 3 seconds
        if (total > 1) {setInterval(nextSlide, 3000);}
    });
});
