@extends('layouts.app')

@section('title', 'مستقبل المدفوعات الآمنة')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-background"></div>
    <div class="hero-grid"></div>
    
    <!-- Header -->
    <header class="header">
        <nav class="nav-container">
            <div class="logo">
                <span class="logo-text">آمن</span>
            </div>
            <div class="nav-links">
                <a href="#features">المميزات</a>
                <a href="#security">الأمان</a>
                <a href="#contact">تواصل معنا</a>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="hero-content">
        <!-- Certification Badges -->
        <div class="certification-badges">
            <div class="badge badge-emerald">
                <svg class="badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>مؤسسة النقد السعودي</span>
            </div>
            <div class="badge badge-amber">
                <svg class="badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>معايير الأمان العالمية</span>
            </div>
            <div class="badge badge-stone">
                <svg class="badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>ISO 27001</span>
            </div>
        </div>

        <!-- Main Title -->
        <div class="hero-title">
            <h1>
                <span class="title-arabic">آمن</span>
                <span class="title-english">Amen</span>
            </h1>
            <div class="hero-subtitle">
                <p class="tagline-arabic">كفك هويتك</p>
                <p class="tagline-description">مستقبل المدفوعات الآمنة في المملكة العربية السعودية</p>
            </div>
            <p class="hero-description">
                اكتشف مستقبل المدفوعات الآمنة في المملكة العربية السعودية. ضع كفك على جهاز المسح للحصول على معاملات فورية
                وآمنة. لا حاجة لبطاقة أو هاتف - فقط يدك.
            </p>
        </div>

        <!-- Palm Scanner Visualization -->
        <div class="palm-scanner-container">
            <div class="palm-scanner">
                <div class="palm-area">
                    <svg class="hand-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M18 11V6a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v0"/>
                        <path d="M14 10V4a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v2"/>
                        <path d="M10 10.5V6a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v8"/>
                        <path d="M18 8a2 2 0 1 1 4 0v6a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.86-5.99-2.34l-3.6-3.6a2 2 0 0 1 2.83-2.82L7 15"/>
                    </svg>
                </div>
                <div class="scanning-rings">
                    <div class="ring ring-1"></div>
                    <div class="ring ring-2"></div>
                </div>
                <div class="status-indicator">
                    <div class="status-dot"></div>
                    <span>جاهز للمسح</span>
                </div>
            </div>
        </div>

        <!-- Features -->
        <div class="hero-features">
            <div class="feature">
                <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <polygon points="13,2 3,14 12,14 11,22 21,10 12,10 13,2"/>
                </svg>
                <span>دفع في 0.3 ثانية</span>
            </div>
            <div class="feature">
                <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>آمان 99.9%</span>
            </div>
            <div class="feature">
                <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>معتمد من ساما</span>
            </div>
        </div>

        <!-- CTA Buttons -->
        <div class="cta-buttons">
            <a href="{{ route('register') }}" class="btn btn-primary">ابدأ الآن</a>
            <a href="{{ route('login') }}" class="btn btn-secondary">تسجيل الدخول</a>
        </div>
    </div>
</section>

<!-- Social Proof Section -->
<section class="social-proof">
    <div class="social-proof-title">
        موثوق من قبل البنوك والمؤسسات المالية الرائدة في المملكة
    </div>
    <div class="partners-grid">
        <div class="partner-logo">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/16/Logo_Saudi_Arabian_Monetary_Authority.svg/1200px-Logo_Saudi_Arabian_Monetary_Authority.svg.png" alt="مؤسسة النقد العربي السعودي - ساما">
        </div>
        <div class="partner-logo">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRWpQDNYNW9t9X9qDNqyJSfgw2EP_UOftXN8Q&s" alt="شريك معتمد">
        </div>
        <div class="partner-logo">
            <div class="placeholder-logo">شريك 1</div>
        </div>
        <div class="partner-logo">
            <div class="placeholder-logo">شريك 2</div>
        </div>
        <div class="partner-logo">
            <div class="placeholder-logo">شريك 3</div>
        </div>
        <div class="partner-logo">
            <div class="placeholder-logo">شريك 4</div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="features-section">
    <div class="container">
        <h2 class="section-title">مميزات متقدمة</h2>
        <p class="section-subtitle">اكتشف ما يجعل Amen Pay الخيار الأمثل للمدفوعات</p>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <svg class="feature-icon-large" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3>أمان متقدم</h3>
                <p>تقنيات تشفير متقدمة وحماية ذكية من الاحتيال باستخدام الذكاء الاصطناعي</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <svg class="feature-icon-large" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3>سرعة فائقة</h3>
                <p>معاملات فورية في أقل من ثانية واحدة مع تقنية المسح المتقدمة</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <svg class="feature-icon-large" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3>تقارير مفصلة</h3>
                <p>تحليلات شاملة وتقارير مفصلة لجميع معاملاتك وأنماط إنفاقك</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <h2>جاهز لبدء رحلة المدفوعات الآمنة؟</h2>
        <p>انضم إلى آلاف المستخدمين الذين يثقون بـ Amen Pay</p>
        <div class="cta-buttons-large">
            <a href="{{ route('register') }}" class="btn btn-primary btn-large">ابدأ الآن مجاناً</a>
            <a href="{{ route('login') }}" class="btn btn-secondary btn-large">تسجيل الدخول</a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-brand">
                <span class="logo-text">Amen Pay</span>
                <p>مستقبل المدفوعات الآمنة في المملكة العربية السعودية. تقنيات متقدمة وأمان عالي المستوى.</p>
            </div>
            
            <div class="footer-links">
                <h4>المميزات</h4>
                <ul>
                    <li>المسح بالكف</li>
                    <li>الأمان المتقدم</li>
                    <li>السرعة الفائقة</li>
                    <li>التقارير المفصلة</li>
                </ul>
            </div>
            
            <div class="footer-links">
                <h4>الدعم</h4>
                <ul>
                    <li>support@amenpay.com</li>
                    <li>+966 50 123 4567</li>
                    <li>الرياض، المملكة العربية السعودية</li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Amen Pay. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</footer>

<style>
/* Additional styles for new sections */
.features-section {
    padding: 4rem 1rem;
    background: var(--stone-50);
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--stone-700);
    margin-bottom: 1rem;
}

.section-subtitle {
    text-align: center;
    font-size: 1.25rem;
    color: var(--stone-600);
    margin-bottom: 3rem;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.feature-card {
    background: white;
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--stone-100);
    text-align: center;
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
}

.feature-icon-wrapper {
    width: 4rem;
    height: 4rem;
    background: linear-gradient(135deg, var(--emerald-600), var(--desert-gold));
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.feature-icon-large {
    width: 2rem;
    height: 2rem;
    color: white;
    stroke-width: 2;
}

.feature-card h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--stone-700);
    margin-bottom: 1rem;
}

.feature-card p {
    color: var(--stone-600);
    line-height: 1.6;
}

.cta-section {
    padding: 4rem 1rem;
    background: linear-gradient(135deg, var(--emerald-600), var(--desert-gold));
    color: white;
    text-align: center;
}

.cta-section h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.cta-section p {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.cta-buttons-large {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-large {
    padding: 1.25rem 2.5rem;
    font-size: 1.25rem;
}

.footer {
    background: var(--stone-700);
    color: white;
    padding: 3rem 1rem 1rem;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.footer-brand p {
    margin-top: 1rem;
    opacity: 0.8;
    line-height: 1.6;
}

.footer-links h4 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.footer-links ul {
    list-style: none;
}

.footer-links li {
    margin-bottom: 0.5rem;
    opacity: 0.8;
}

.footer-bottom {
    border-top: 1px solid var(--stone-600);
    padding-top: 1rem;
    text-align: center;
    opacity: 0.8;
}

.placeholder-logo {
    width: 120px;
    height: 60px;
    background: var(--stone-100);
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--stone-600);
    font-size: 0.875rem;
    font-weight: 500;
}

@media (max-width: 768px) {
    .section-title {
        font-size: 2rem;
    }
    
    .cta-section h2 {
        font-size: 2rem;
    }
    
    .cta-buttons-large {
        flex-direction: column;
        align-items: center;
    }
    
    .footer-content {
        grid-template-columns: 1fr;
        text-align: center;
    }
}
</style>
@endsection
