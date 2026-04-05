<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css" />

<!-- Hero Section -->
<section id="home" class="hero-social">
    <!-- Abstract gradient background blobs -->
    <div class="bg-blob blob-1"></div>
    <div class="bg-blob blob-2"></div>
    <div class="bg-blob blob-3"></div>

    <!-- Floating UI Elements for energetic social vibe -->
    <div class="floating-badge badge-1">🔥 Trending Now</div>
    <div class="floating-badge badge-2">🎉 10k+ Events</div>
    <div class="floating-badge badge-3">👋 Meet People</div>
    
    <div class="img-bubble bubble-1">
        <img src="<?php echo SITE_URL; ?>/assets/images/emer.jpg" alt="Party" />
    </div>
    <div class="img-bubble bubble-2">
        <img src="<?php echo SITE_URL; ?>/assets/images/recs.jpg" alt="Concert" />
    </div>
    <div class="img-bubble bubble-3">
        <img src="<?php echo SITE_URL; ?>/assets/images/tech.jpg" alt="Tech" />
    </div>

    <div class="hero-content">
        <div class="pill-badge">✨ The Ultimate Social Event Hub</div>
        <h1 class="hero-main-title">
            Where Moments <br/>
            Become <span class="text-gradient">Memories</span>
        </h1>
        <p class="hero-subtext">
            Discover, connect, and experience events with a community that shares your vibe. Whether you're organizing a massive music festival or a casual weekend meetup, Eventz brings people together.
        </p>
        
        <div class="hero-cta-group">
            <button class="btn-primary-energetic">
                Join the Party 
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
            </button>
            <button class="btn-secondary-energetic">Explore Events</button>
        </div>

        <div class="social-proof">
            <div class="avatars">
                <img src="<?php echo SITE_URL; ?>/assets/images/aboutevents.jpg" class="avatar" alt="User">
                <img src="<?php echo SITE_URL; ?>/assets/images/emer.jpg" class="avatar" alt="User">
                <img src="<?php echo SITE_URL; ?>/assets/images/recs.jpg" class="avatar" alt="User">
                <div class="avatar-more">+50k</div>
            </div>
            <p>Join <strong>50,000+</strong> users creating memories</p>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="section energetic-features">
    <div class="section-wrapper">
        <div class="features-header">
            <h2 class="section-title">Why Choose <span class="text-gradient">Eventz?</span></h2>
            <p class="section-subtitle">Everything you need to host, discover, and enjoy events in one unified, wildly energetic platform.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card-new">
                <div class="feature-icon icon-orange">
                    <img src="<?php echo SITE_URL; ?>/assets/images/discover.png" alt="Discover">
                </div>
                <h3>Discover</h3>
                <p>Find events that match your energy. From underground gigs to widespread conventions, it's all here.</p>
            </div>

            <div class="feature-card-new">
                <div class="feature-icon icon-pink">
                    <img src="<?php echo SITE_URL; ?>/assets/images/connect.png" alt="Connect">
                </div>
                <h3>Connect</h3>
                <p>Engage with like-minded people. Chat, share, and make friends before the event even starts.</p>
            </div>

            <div class="feature-card-new">
                <div class="feature-icon icon-purple">
                    <img src="<?php echo SITE_URL; ?>/assets/images/create.svg" alt="Create">
                </div>
                <h3>Create</h3>
                <p>Intuitive tools to build, market, and manage your event. Turn your wild ideas into reality.</p>
            </div>

            <div class="feature-card-new">
                <div class="feature-icon icon-blue">
                    <img src="<?php echo SITE_URL; ?>/assets/images/sponsor.png" alt="Sponsor">
                </div>
                <h3>Sponsor</h3>
                <p>Back the events you believe in. Gain unmatched visibility and connect with passionate demographics.</p>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="section about-social">
    <div class="section-wrapper about-social-wrapper">
        <div class="about-images-grid">
            <div class="img-main">
                <img src="<?php echo SITE_URL; ?>/assets/images/aboutevents.jpg" alt="About Eventz">
            </div>
            <div class="about-card-float">
                <h3 class="text-gradient">100%</h3>
                <p>Pure Energy</p>
            </div>
        </div>
        <div class="about-text-content">
            <div class="pill-badge">About Us</div>
            <h2 class="section-title">Redefining How We <span class="text-gradient">Celebrate</span></h2>
            <p>
                Eventz isn't just a ticketing platform; it's a social ecosystem. We believe that the best moments in life happen when people come together. 
            </p>
            <p>
                Whether you're organizing a worldwide summit or an intimate local gathering, our platform is engineered to spark connections, hype up your audience, and deliver completely unforgettable experiences.
            </p>
            <button class="btn-outline-energetic mt-4">Learn More About Us</button>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="section contact-energetic">
    <div class="section-wrapper contact-wrapper-new">
        <div class="contact-info">
            <h2 class="section-title">Let's Keep the <span class="text-gradient">Energy Alive</span></h2>
            <p class="section-subtitle-left">Have an idea? Need support with an event? Or just want to say hi? Drop us a message!</p>
            
            <div class="contact-methods">
                <div class="contact-method">
                    <strong>Email Us</strong>
                    <span>hello@eventz.social</span>
                </div>
                <div class="contact-method">
                    <strong>Call Us</strong>
                    <span>+1 (555) 123-4567</span>
                </div>
            </div>
        </div>
        
        <div class="contact-form-container">
            <form class="contact-form-new" action="<?= SITE_URL ?>contactSubmit" method="POST">
                <div class="input-group">
                    <input type="text" name="name" placeholder="Your Name" required>
                </div>
                <div class="input-group">
                    <input type="email" name="email" placeholder="Your Email" required>
                </div>
                <div class="input-group">
                    <textarea name="message" placeholder="What's on your mind?" required></textarea>
                </div>
                <button type="submit" class="btn-primary-energetic full-width">Send Message</button>
            </form>
        </div>
    </div>
</section>
