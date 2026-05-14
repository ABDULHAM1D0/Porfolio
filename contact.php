<?php
require 'includes/db.php';

$msg_sent = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['Name'] ?? '');
    $email   = trim($_POST['Email'] ?? '');
    $subject = trim($_POST['Subject'] ?? '');
    $message = trim($_POST['Message'] ?? '');

    if (empty($name))    $errors[] = "Name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (empty($subject)) $errors[] = "Subject is required.";
    if (empty($message)) $errors[] = "Message is required.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $subject, $message]);
        $msg_sent = true;
    }
}
?>

<?php include 'includes/header.php'; ?>

<section class="section" style="min-height: 90vh;">
  <div class="container">

    <div class="fade-up" style="margin-bottom: 48px;">
      <p class="section-label">Get In Touch</p>
      <h2 class="section-title">Contact <span>Me</span></h2>
      <div class="section-line"></div>
    </div>

    <div class="row g-5">
      <div class="col-lg-7 fade-up">

        <?php if ($msg_sent): ?>
          <div style="background: rgba(118,228,196,0.1); border: 1px solid rgba(118,228,196,0.3);
                      border-radius: 10px; padding: 20px; margin-bottom: 24px;
                      font-family: 'Space Mono', monospace; font-size: 14px; color: var(--accent2);">
            ✓ Message sent successfully! I'll get back to you soon.
          </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
          <div style="background: rgba(226,75,74,0.1); border: 1px solid rgba(226,75,74,0.3);
                      border-radius: 10px; padding: 20px; margin-bottom: 24px; color: #fc8181;">
            <?php foreach ($errors as $e): ?>
              <div style="font-size:14px; margin-bottom:4px;">✗ <?php echo htmlspecialchars($e); ?></div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <form action="contact.php" method="POST" id="contactForm" class="contact-form-card">
          <div class="row g-3">
            <div class="col-md-6">
              <input type="text" class="form-control" placeholder="Your Name" name="Name"
                     value="<?php echo htmlspecialchars($_POST['Name'] ?? ''); ?>">
              <span class="text-danger" id="nameError" style="font-size:12px;"></span>
            </div>
            <div class="col-md-6">
              <input type="text" class="form-control" placeholder="Your Email" name="Email"
                     value="<?php echo htmlspecialchars($_POST['Email'] ?? ''); ?>">
              <span class="text-danger" id="emailError" style="font-size:12px;"></span>
            </div>
            <div class="col-12">
              <input type="text" class="form-control" placeholder="Subject" name="Subject"
                     value="<?php echo htmlspecialchars($_POST['Subject'] ?? ''); ?>">
              <span class="text-danger" id="subjectError" style="font-size:12px;"></span>
            </div>
            <div class="col-12">
              <textarea name="Message" rows="7" class="form-control"
                        placeholder="Your message..."><?php echo htmlspecialchars($_POST['Message'] ?? ''); ?></textarea>
              <span class="text-danger" id="messageError" style="font-size:12px;"></span>
            </div>
            <div class="col-12">
              <button type="submit" class="btn-primary-custom" style="cursor:pointer; width:100%;">
                Send Message
              </button>
            </div>
          </div>
        </form>

      </div>

      <div class="col-lg-5 fade-up" style="transition-delay:0.2s;">
        <div style="margin-bottom: 32px;">
          <p style="font-size:15px; color:var(--text2); line-height:1.8;">
            Have a project in mind, a research collaboration, or just want to say hello? 
            My inbox is always open. I'll try my best to get back to you!
          </p>
        </div>

        <div class="exp-card" style="margin-bottom:16px;">
          <div style="display:flex; align-items:center; gap:16px;">
            <div style="width:44px; height:44px; background:rgba(99,179,237,0.1);
                        border:1px solid rgba(99,179,237,0.3); border-radius:8px;
                        display:flex; align-items:center; justify-content:center; font-size:18px;">📍</div>
            <div>
              <div style="font-family:'Space Mono',monospace; font-size:11px; color:var(--accent); text-transform:uppercase; letter-spacing:1px;">Location</div>
              <div style="color:var(--text); font-size:15px;">Istanbul, Fatih, Turkey</div>
            </div>
          </div>
        </div>

        <div class="exp-card" style="margin-bottom:16px;">
          <div style="display:flex; align-items:center; gap:16px;">
            <div style="width:44px; height:44px; background:rgba(99,179,237,0.1);
                        border:1px solid rgba(99,179,237,0.3); border-radius:8px;
                        display:flex; align-items:center; justify-content:center; font-size:18px;">📧</div>
            <div>
              <div style="font-family:'Space Mono',monospace; font-size:11px; color:var(--accent); text-transform:uppercase; letter-spacing:1px;">Email</div>
              <a href="mailto:mirzaahmedovabdulhamid@gmail.com" style="color:var(--text); font-size:14px;">mirzaahmedovabdulhamid@gmail.com</a>
            </div>
          </div>
        </div>

        <div class="exp-card" style="margin-bottom:16px;">
          <div style="display:flex; align-items:center; gap:16px;">
            <div style="width:44px; height:44px; background:rgba(99,179,237,0.1);
                        border:1px solid rgba(99,179,237,0.3); border-radius:8px;
                        display:flex; align-items:center; justify-content:center; font-size:18px;">📱</div>
            <div>
              <div style="font-family:'Space Mono',monospace; font-size:11px; color:var(--accent); text-transform:uppercase; letter-spacing:1px;">Phone</div>
              <a href="tel:+905316372805" style="color:var(--text); font-size:15px;">+90 531 637 28 05</a>
            </div>
          </div>
        </div>

        <div class="exp-card">
          <div style="display:flex; align-items:center; gap:16px;">
            <div style="width:44px; height:44px; background:rgba(99,179,237,0.1);
                        border:1px solid rgba(99,179,237,0.3); border-radius:8px;
                        display:flex; align-items:center; justify-content:center; font-size:18px;">💼</div>
            <div>
              <div style="font-family:'Space Mono',monospace; font-size:11px; color:var(--accent); text-transform:uppercase; letter-spacing:1px;">GitHub</div>
              <a href="https://github.com/ABDULHAM1D0" target="_blank" style="color:var(--text); font-size:15px;">ABDULHAM1D0</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>