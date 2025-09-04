document.addEventListener("DOMContentLoaded", function() {
    // Initialize Animate on Scroll
    AOS.init({
        duration: 800,
        once: true, // whether animation should happen only once - while scrolling down
    });

    // Animated Counter
    const counters = document.querySelectorAll('.counter');
    const speed = 200; // The lower the number, the faster the count

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;
                    const inc = target / speed;

                    if (count < target) {
                        counter.innerText = Math.ceil(count + inc);
                        setTimeout(updateCount, 10);
                    } else {
                        counter.innerText = target;
                    }
                };
                updateCount();
                observer.unobserve(counter); // Stop observing once animated
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => {
        observer.observe(counter);
    });
});