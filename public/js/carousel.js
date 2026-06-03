/**
 * Carousel — AnimaMarket
 */

function initCarouselHome() {
    const carousel = document.querySelector('#carouselHome');
    if (!carousel || carousel.dataset.initialized === 'true') return;

    carousel.dataset.initialized = 'true';

    const track = carousel.querySelector('.carousel-track');
    const items = carousel.querySelectorAll('.carousel-item');
    const indicators = carousel.querySelectorAll('.carousel-indicators button');
    const btnPrev = carousel.querySelector('.carousel-control-prev');
    const btnNext = carousel.querySelector('.carousel-control-next');

    if (!track || items.length === 0) return;

    let current = 0;
    let interval = null;
    const AUTO_TIME = 5000;

    function showSlide(index) {
        const nextIndex = (index + items.length) % items.length;

        track.style.transform = `translateX(-${nextIndex * 100}%)`;

        items.forEach((item, i) => {
            item.classList.toggle('active', i === nextIndex);
        });

        indicators.forEach((btn, i) => {
            btn.classList.toggle('active', i === nextIndex);
        });

        current = nextIndex;
    }

    function nextSlide() {
        showSlide(current + 1);
    }

    function prevSlide() {
        showSlide(current - 1);
    }

    function stopAuto() {
        if (interval) {
            clearInterval(interval);
            interval = null;
        }
    }

    function startAuto() {
        if (items.length <= 1 || interval) return;
        interval = setInterval(nextSlide, AUTO_TIME);
    }

    function restartAuto() {
        stopAuto();
        startAuto();
    }

    if (btnNext) {
        btnNext.addEventListener('click', () => {
            nextSlide();
            restartAuto();
        });
    }

    if (btnPrev) {
        btnPrev.addEventListener('click', () => {
            prevSlide();
            restartAuto();
        });
    }

    indicators.forEach((btn, i) => {
        btn.addEventListener('click', () => {
            showSlide(i);
            restartAuto();
        });
    });

    carousel.addEventListener('mouseenter', stopAuto);
    carousel.addEventListener('mouseleave', startAuto);

    let startX = 0;

    carousel.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
    });

    carousel.addEventListener('touchend', (e) => {
        const endX = e.changedTouches[0].clientX;
        const diff = startX - endX;

        if (Math.abs(diff) > 50) {
            if (diff > 0) {
                nextSlide();
            } else {
                prevSlide();
            }
            restartAuto();
        }
    });

    showSlide(0);
    startAuto();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCarouselHome);
} else {
    initCarouselHome();
}
