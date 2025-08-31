// Palm Scanner Animation Controller
class PalmScannerController {
    constructor() {
        this.scanner = document.querySelector(".palm-scanner")
        this.statusDot = document.querySelector(".status-dot")
        this.statusText = document.querySelector(".status-indicator span")
        this.isScanning = false

        this.init()
    }

    init() {
        // Add click event to palm scanner
        if (this.scanner) {
            this.scanner.addEventListener("click", () => this.startScan())
        }

        // Add smooth scroll for navigation links
        this.initSmoothScroll()

        // Add button click handlers
        this.initButtonHandlers()
    }

    startScan() {
        if (this.isScanning) return

        this.isScanning = true
        this.statusText.textContent = "جاري المسح..."

        // Simulate scanning process
        setTimeout(() => {
            this.statusText.textContent = "تم التحقق بنجاح"
            this.statusDot.style.background = "#10b981"

            setTimeout(() => {
                this.resetScanner()
            }, 2000)
        }, 1500)
    }

    resetScanner() {
        this.isScanning = false
        this.statusText.textContent = "جاهز للمسح"
        this.statusDot.style.background = "#10b981"
    }

    initSmoothScroll() {
        const links = document.querySelectorAll('a[href^="#"]')

        links.forEach((link) => {
            link.addEventListener("click", (e) => {
                e.preventDefault()
                const targetId = link.getAttribute("href")
                const targetElement = document.querySelector(targetId)

                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: "smooth",
                        block: "start",
                    })
                }
            })
        })
    }

    initButtonHandlers() {
        const primaryBtn = document.querySelector(".btn-primary")
        const secondaryBtn = document.querySelector(".btn-secondary")

        if (primaryBtn && !primaryBtn.hasAttribute('href')) {
            primaryBtn.addEventListener("click", () => {
                // Navigate to register page if no href is set
                window.location.href = '/register'
            })
        }

        if (secondaryBtn && !secondaryBtn.hasAttribute('href')) {
            secondaryBtn.addEventListener("click", () => {
                // Navigate to login page if no href is set
                window.location.href = '/login'
            })
        }
    }
}

// Intersection Observer for animations
class AnimationController {
    constructor() {
        this.observerOptions = {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px",
        }

        this.init()
    }

    init() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("animate-in")
                }
            })
        }, this.observerOptions)

        // Observe elements for animation
        const animatedElements = document.querySelectorAll(
            ".certification-badges, .hero-title, .palm-scanner-container, .hero-features, .cta-buttons, .social-proof, .features-section, .cta-section, .footer",
        )

        animatedElements.forEach((el) => {
            observer.observe(el)
        })
    }
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    new PalmScannerController()
    new AnimationController()

    // Add fade-in animation styles
    const style = document.createElement("style")
    style.textContent = `
        .certification-badges,
        .hero-title,
        .palm-scanner-container,
        .hero-features,
        .cta-buttons,
        .social-proof,
        .features-section,
        .cta-section,
        .footer {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .animate-in {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
        
        .certification-badges.animate-in {
            transition-delay: 0.1s;
        }
        
        .hero-title.animate-in {
            transition-delay: 0.2s;
        }
        
        .palm-scanner-container.animate-in {
            transition-delay: 0.3s;
        }
        
        .hero-features.animate-in {
            transition-delay: 0.4s;
        }
        
        .cta-buttons.animate-in {
            transition-delay: 0.5s;
        }
        
        .social-proof.animate-in {
            transition-delay: 0.6s;
        }
        
        .features-section.animate-in {
            transition-delay: 0.7s;
        }
        
        .cta-section.animate-in {
            transition-delay: 0.8s;
        }
        
        .footer.animate-in {
            transition-delay: 0.9s;
        }
    `
    document.head.appendChild(style)
})
  