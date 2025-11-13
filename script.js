document.addEventListener('DOMContentLoaded', function() {
    initNavigation();
    initScrollProgress();
    animateSkillBars();
    animateCounters();
    setupScrollAnimations();
    handleFormSubmission();
    initDarkMode();
    setupExperienceToggle();
    setupSkillFilters();
});

function initNavigation() {
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    const navLinks = document.querySelectorAll('.nav-link');

    navToggle.addEventListener('click', () => {
        navMenu.classList.toggle('active');

        const spans = navToggle.querySelectorAll('span');
        if (navMenu.classList.contains('active')) {
            spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
            spans[1].style.opacity = '0';
            spans[2].style.transform = 'rotate(-45deg) translate(7px, -6px)';
        } else {
            spans[0].style.transform = 'none';
            spans[1].style.opacity = '1';
            spans[2].style.transform = 'none';
        }
    });

    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = link.getAttribute('href');
            const targetSection = document.querySelector(targetId);

            if (targetSection) {
                targetSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });

                navMenu.classList.remove('active');
                const spans = navToggle.querySelectorAll('span');
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[2].style.transform = 'none';
            }
        });
    });

    const sections = document.querySelectorAll('.section');
    const observerOptions = {
        root: null,
        rootMargin: '-50% 0px -50% 0px',
        threshold: 0
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const sectionId = entry.target.getAttribute('id');
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${sectionId}`) {
                        link.classList.add('active');
                    }
                });
            }
        });
    }, observerOptions);

    sections.forEach(section => {
        observer.observe(section);
    });
}

function initScrollProgress() {
    const scrollProgress = document.querySelector('.scroll-progress');

    window.addEventListener('scroll', () => {
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollPercent = (scrollTop / (documentHeight - windowHeight)) * 100;

        scrollProgress.style.width = scrollPercent + '%';
    });
}

function animateSkillBars() {
    const skillLevels = document.querySelectorAll('.skill-level');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const level = entry.target.getAttribute('data-level');
                setTimeout(() => {
                    entry.target.style.width = level + '%';
                }, 200);
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.5
    });

    skillLevels.forEach(skill => {
        observer.observe(skill);
    });
}

function animateCounters() {
    const statNumbers = document.querySelectorAll('.stat-number');
    let hasAnimated = false;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !hasAnimated) {
                hasAnimated = true;
                statNumbers.forEach(stat => {
                    const target = parseInt(stat.getAttribute('data-target'));
                    let current = 0;
                    const increment = target / 50;
                    const duration = 2000;
                    const stepTime = duration / 50;

                    const counter = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            stat.textContent = target;
                            clearInterval(counter);
                        } else {
                            stat.textContent = Math.floor(current);
                        }
                    }, stepTime);
                });
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.5
    });

    const aboutSection = document.querySelector('.about-section');
    if (aboutSection) {
        observer.observe(aboutSection);
    }
}

function setupScrollAnimations() {
    const timelineItems = document.querySelectorAll('.timeline-item');
    const educationItems = document.querySelectorAll('.education-item');
    const certItems = document.querySelectorAll('.cert-item');
    const skillCards = document.querySelectorAll('.skill-card');

    const animateElements = (elements) => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.2
        });

        elements.forEach(element => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            element.style.transition = 'all 0.6s ease-out';
            observer.observe(element);
        });
    };

    animateElements(timelineItems);
    animateElements(educationItems);
    animateElements(certItems);
    animateElements(skillCards);
}

function handleFormSubmission() {
    const form = document.querySelector('.contact-form');

    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const message = document.getElementById('message').value;

            const submitBtn = form.querySelector('.submit-btn');
            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
            submitBtn.disabled = true;

            setTimeout(() => {
                submitBtn.innerHTML = '<i class="fas fa-check"></i> Message envoyé !';
                submitBtn.style.background = '#28a745';

                form.reset();

                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    submitBtn.style.background = '';
                }, 3000);
            }, 1500);
        });
    }
}

window.addEventListener('beforeprint', function() {
    const skillLevels = document.querySelectorAll('.skill-level');
    skillLevels.forEach(skill => {
        const level = skill.getAttribute('data-level');
        skill.style.width = level + '%';
    });

    const statNumbers = document.querySelectorAll('.stat-number');
    statNumbers.forEach(stat => {
        const target = stat.getAttribute('data-target');
        stat.textContent = target;
    });
});

const heroScrollIndicator = document.querySelector('.hero-scroll-indicator');
if (heroScrollIndicator) {
    heroScrollIndicator.addEventListener('click', () => {
        const aboutSection = document.querySelector('#about');
        if (aboutSection) {
            aboutSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
}

function initDarkMode() {
    const darkModeToggle = document.querySelector('.dark-mode-toggle');
    const htmlElement = document.documentElement;

    const savedMode = localStorage.getItem('darkMode') || 'light';
    htmlElement.setAttribute('data-theme', savedMode);

    if (darkModeToggle) {
        if (savedMode === 'dark') {
            darkModeToggle.classList.add('active');
        }

        darkModeToggle.addEventListener('click', () => {
            const currentMode = htmlElement.getAttribute('data-theme');
            const newMode = currentMode === 'light' ? 'dark' : 'light';

            htmlElement.setAttribute('data-theme', newMode);
            localStorage.setItem('darkMode', newMode);
            darkModeToggle.classList.toggle('active');
        });
    }
}

function setupExperienceToggle() {
    const timelineItems = document.querySelectorAll('.timeline-item');

    timelineItems.forEach(item => {
        const content = item.querySelector('.timeline-content');
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'expand-btn';
        toggleBtn.innerHTML = '<i class="fas fa-chevron-down"></i>';
        toggleBtn.setAttribute('aria-label', 'Voir plus de détails');

        const details = content.querySelector('ul');
        if (details) {
            toggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                content.classList.toggle('expanded');
                toggleBtn.classList.toggle('active');
            });
            content.appendChild(toggleBtn);
        }
    });
}

function setupSkillFilters() {
    const skillsSection = document.querySelector('.skills-section');
    if (!skillsSection) return;

    const filterContainer = document.createElement('div');
    filterContainer.className = 'skill-filters';
    filterContainer.innerHTML = `
        <button class="filter-btn active" data-filter="all">Tous</button>
        <button class="filter-btn" data-filter="languages">Langages</button>
        <button class="filter-btn" data-filter="frameworks">Frameworks</button>
        <button class="filter-btn" data-filter="databases">Bases de données</button>
        <button class="filter-btn" data-filter="tools">Outils</button>
    `;

    const sectionContainer = skillsSection.querySelector('.section-container');
    sectionContainer.insertBefore(filterContainer, sectionContainer.querySelector('.skills-grid'));

    const skillCards = document.querySelectorAll('.skill-card');
    const filterBtns = document.querySelectorAll('.filter-btn');

    const cardMap = {
        'languages': 0,
        'frameworks': 1,
        'databases': 2,
        'tools': 3,
        'languages-list': 4,
        'soft-skills': 5
    };

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.getAttribute('data-filter');

            skillCards.forEach((card, index) => {
                if (filter === 'all') {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 10);
                } else {
                    const categoryIndex = Object.values(cardMap).indexOf(index);
                    const category = Object.keys(cardMap)[categoryIndex];

                    if (category === filter || (filter === 'languages-list' && index === 4) || (filter === 'soft-skills' && index === 5)) {
                        card.style.display = 'block';
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, 10);
                    } else {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            card.style.display = 'none';
                        }, 300);
                    }
                }
            });
        });
    });
}

function downloadPDF() {
    const element = document.body;

    const opt = {
        margin: 10,
        filename: 'Maissa_ELLOUZE_CV.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { orientation: 'portrait', unit: 'mm', format: 'a4' }
    };

    if (window.html2pdf) {
        html2pdf().set(opt).from(element).save();
    } else {
        alert("La bibliothèque html2pdf.js n'est pas chargée !");
    }
}

