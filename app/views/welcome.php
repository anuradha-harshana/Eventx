<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css" />

<section id="home" class="hero">
    <div class="hero-section">
        <div class="left-container">
            <div class="top-container">
                <div class="hero-card">
                    <h1 class="hero-title">
                        E<span class="big-letter">V</span>ENT<span class="small-letter">Z</span>
                    </h1>
                    <h4 class="hero-subtitle">
                        PAST - PRESENT - <span class="highlight">FUTURE</span>
                    </h4>

                    <p class="hero-description">
                        Discover - Connect - Create - & Sponsor <br>
                        events like never before. Connect with people who share your interests, 
                        showcase your events as an organizer or participant, or sponsor meaningful experiences. All-in-One unified platform.
                    </p>

                    <button class="register-btn">
                        Get Started
                        <span class="arrow">→</span>
                    </button>
                </div>
            </div>
            <div class="bottom-container">
                <div class="left">
                    <img src="<?php echo SITE_URL; ?>/assets/images/emer.jpg" alt="Emerging Event"/>
                </div>
                <div class="right">
                     <img src="<?php echo SITE_URL; ?>/assets/images/recs.jpg" alt="Records"/>
                </div>
            </div>
        </div>
        <div class="right-container">
            <img src="<?php echo SITE_URL; ?>assets/images/tech.jpg" alt="Tech"/>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="section features-section">
    <div class="section-wrapper">
        <h2 class="section-title">Why Choose Eventz?</h2>
        <p class="section-subtitle">A clean platform for organizers, participants, and sponsors</p>

        <div class="features-cards">
            <div class="feature-card">
                <img src="<?php echo SITE_URL; ?>/assets/images/discover.png" alt="Discover">
                <h3>Discover</h3>
                <p>Find events that match your interests and explore new experiences.</p>
            </div>

            <div class="feature-card">
                <img src="<?php echo SITE_URL; ?>/assets/images/connect.png" alt="Connect">
                <h3>Connect</h3>
                <p>Engage with like-minded people and join discussions effortlessly.</p>
            </div>

            <div class="feature-card">
                <img src="<?php echo SITE_URL; ?>/assets/images/create.svg" alt="Create">
                <h3>Create</h3>
                <p>Organize events seamlessly and showcase your ideas to your community.</p>
            </div>

            <div class="feature-card">
                <img src="<?php echo SITE_URL; ?>/assets/images/sponsor.png" alt="Sponsor">
                <h3>Sponsor</h3>
                <p>Support meaningful events and collaborate with organizers to make an impact.</p>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="section about-section">
    <div class="section-wrapper about-content">
        <div class="about-text">
            <h2>About <span class="highlight">Eventz</span></h2>
            <p>
                Eventz is a dynamic platform connecting organizers, participants, and sponsors. Our goal is to create unforgettable experiences.
            </p>
            <p>
                Attend events, organize your own, or sponsor a cause — Eventz makes it intuitive, efficient, and engaging.
            </p>
        </div>
        <div class="about-image">
            <img src="<?php echo SITE_URL; ?>/assets/images/aboutevents.jpg" alt="About Eventz">
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="section contact-section">
    <div class="section-wrapper contact-wrapper">
        <h2 class="section-title">Contact Us</h2>
        <p class="section-subtitle">We’d love to hear from you</p>

        <form class="contact-form" action="<?= SITE_URL ?>contactSubmit" method="POST">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" placeholder="Your Message" required></textarea>
            <button type="submit" class="btn-main">Send Message</button>
        </form>
    </div>
</section>
