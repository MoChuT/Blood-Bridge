<?php require __DIR__ . '/includes/storage.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blood Bridge</title>
  <link rel="stylesheet" href="styles.css?v=20260629-2">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <script src="app.js" defer></script>
</head>
<body class="snap-page">
  <main>
    <section class="welcome-page">
      <header class="welcome-header">
        <div class="brand">
          <span class="brand-mark"></span>
          <span>
            <strong class="brand-title">Blood Bridge</strong>
            <span class="brand-subtitle">Blood donation registration platform</span>
          </span>
        </div>
        <nav class="nav-actions" aria-label="Public navigation">
          <a class="nav-link" href="#about">About</a>
          <a class="nav-link" href="#partners">Partners</a>
          <a class="nav-link" href="#contact">Contact</a>
        </nav>
      </header>
      <div class="welcome-content">
        <p class="welcome-kicker">Donate blood, save lives</p>
        <h1>Connecting donors with blood donation drives.</h1>
        <p>Blood Bridge helps donors register, receive important event announcements, and access donation services through a clear and simple online experience.</p>
        <a class="btn-primary btn-large" href="#functions">Explore Functions</a>
      </div>
    </section>

    <section class="map-page" id="functions">
      <div id="threeScene"></div>
      <div class="map-overlay">
      <header class="map-header">
        <div class="brand">
          <span class="brand-mark"></span>
          <span>
            <strong class="brand-title">Blood Bridge</strong>
            <span class="brand-subtitle">Donor function map</span>
          </span>
        </div>
        <nav class="nav-actions" aria-label="Main navigation"></nav>
      </header>

      <section class="map-intro">
        <h1>Choose a function.</h1>
      </section>

      <nav class="mind-map" aria-label="Donor function map">
        <a class="node node-register" href="donor-register.php">
          <strong>Register Donor</strong>
          <span>Donor details and registration confirmation</span>
        </a>
        <a class="node node-login" href="donor-login.php">
          <strong>Login</strong>
          <span>Donor ID, password and account verification</span>
        </a>
        <a class="node node-announcement" href="donor-announcements.php">
          <strong>Announcement</strong>
          <span>View event details and donor notices</span>
        </a>
      </nav>

      <footer class="map-footer">
        <span>Select a function to continue.</span>
        <span>Blood Bridge</span>
      </footer>
      </div>
    </section>

    <section class="story-page about-page" id="about">
      <div class="story-layout">
        <div class="story-copy">
          <p class="welcome-kicker">About Blood Bridge</p>
          <h1>Built to make donation drives easier to join.</h1>
          <p>Blood Bridge is a blood donation registration platform designed to connect donors, organizers and donation events through one clear online system.</p>
          <p>The platform supports donor registration, announcements, appointment requests, document upload, health screening and administrative review so donation drives can be managed with less manual work.</p>
        </div>
        <div class="story-grid">
          <article class="story-card">
            <span>01</span>
            <h2>Our History</h2>
            <p>Blood Bridge began as a final year project focused on improving the registration process for local blood donation drives.</p>
          </article>
          <article class="story-card">
            <span>02</span>
            <h2>Our Mission</h2>
            <p>We help donors receive event information, submit required details and follow their donation journey from one portal.</p>
          </article>
          <article class="story-card">
            <span>03</span>
            <h2>Our Values</h2>
            <p>Clarity, trust and readiness guide the system design, from donor forms to administrative approval.</p>
          </article>
        </div>
      </div>
    </section>

    <section class="story-page partner-page" id="partners">
      <div class="story-layout reverse">
        <div class="story-copy">
          <p class="welcome-kicker">Founder and Sponsorship</p>
          <h1>Created for community donation support.</h1>
          <p>Blood Bridge was founded by the Blood Bridge project team to support a more organized and accessible donation drive experience.</p>
          <p>The platform welcomes collaboration with hospitals, campus units, non-profit organizations and community sponsors who want to improve donor outreach.</p>
        </div>
        <div class="story-grid compact">
          <article class="story-card accent-red">
            <span>Founder</span>
            <h2>Blood Bridge Project Team</h2>
            <p>Responsible for designing the donor flow, admin workflow and database structure used by the system.</p>
          </article>
          <article class="story-card accent-teal">
            <span>Sponsorship</span>
            <h2>Partner With Us</h2>
            <p>Sponsors may support event promotion, donor awareness, venue coordination and blood drive preparation.</p>
          </article>
        </div>
      </div>
    </section>

    <section class="contact-page" id="contact">
      <div class="contact-content">
        <div>
          <p class="welcome-kicker">Contact</p>
          <h1>Blood Bridge Support</h1>
          <p>For blood donation drive enquiries, sponsorship discussion and administrative assistance, please contact the Blood Bridge coordination team.</p>
        </div>
        <footer class="site-footer">
          <div>
            <strong>Email</strong>
            <span>support@bloodbridge.org</span>
          </div>
          <div>
            <strong>Contact number</strong>
            <span>+60 12-345 6789</span>
          </div>
          <div>
            <strong>Fax</strong>
            <span>+60 3-1234 5678</span>
          </div>
          <div>
            <strong>Organization address</strong>
            <span>Blood Bridge Coordination Office, Multimedia University, Cyberjaya, Selangor, Malaysia</span>
          </div>
        </footer>
      </div>
    </section>
  </main>
</body>
</html>
