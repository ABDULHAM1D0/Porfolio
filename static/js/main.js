// ===== COOKIE HELPERS =====
function setCookie(name, value, days) {
  const d = new Date();
  d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
  document.cookie = name + "=" + value + ";expires=" + d.toUTCString() + ";path=/";
}

function getCookie(name) {
  const cname = name + "=";
  const ca = decodeURIComponent(document.cookie).split(';');
  for (let c of ca) {
    c = c.trim();
    if (c.indexOf(cname) === 0) return c.substring(cname.length);
  }
  return "";
}

// ===== DARK MODE (light mode toggle) =====
const darkToggle = document.getElementById('darkModeToggle');
const label = document.querySelector('label[for="darkModeToggle"]');

function applyMode(isLight) {
  if (isLight) {
    document.body.classList.add('dark-mode');
    if (label) label.textContent = '🌙';
  } else {
    document.body.classList.remove('dark-mode');
    if (label) label.textContent = '☀️';
  }
}

const saved = getCookie('lightMode') === 'true';
if (darkToggle) darkToggle.checked = saved;
applyMode(saved);

if (darkToggle) {
  darkToggle.addEventListener('change', function () {
    applyMode(this.checked);
    setCookie('lightMode', this.checked, 30);
  });
}

// ===== SCROLL ANIMATIONS =====
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      // Animate skill bars
      entry.target.querySelectorAll('.skill-fill').forEach(bar => {
        bar.style.width = bar.dataset.width + '%';
      });
    }
  });
}, { threshold: 0.1 });

document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

// ===== FORM VALIDATION =====
const contactForm = document.getElementById('contactForm');
if (contactForm) {
  contactForm.addEventListener('submit', function (e) {
    let valid = true;
    ['nameError','emailError','subjectError','messageError'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.textContent = '';
    });

    const name    = document.querySelector('[name="Name"]')?.value.trim();
    const email   = document.querySelector('[name="Email"]')?.value.trim();
    const subject = document.querySelector('[name="Subject"]')?.value.trim();
    const message = document.querySelector('[name="Message"]')?.value.trim();

    if (!name) { document.getElementById('nameError').textContent = 'Name is required.'; valid = false; }
    if (!email) { document.getElementById('emailError').textContent = 'Email is required.'; valid = false; }
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { document.getElementById('emailError').textContent = 'Enter a valid email.'; valid = false; }
    if (!subject) { document.getElementById('subjectError').textContent = 'Subject is required.'; valid = false; }
    if (!message) { document.getElementById('messageError').textContent = 'Message is required.'; valid = false; }
    else if (message.length < 10) { document.getElementById('messageError').textContent = 'At least 10 characters.'; valid = false; }

    if (!valid) e.preventDefault();
  });
}

// ===== AJAX LOAD PROJECTS =====
function loadProjects() {
  const container = document.getElementById('projects-container');
  if (!container) return;

  fetch('/portfolio/api/projects.php')
    .then(r => r.json())
    .then(projects => {
      if (projects.length === 0) {
        container.innerHTML = `<div class="col-12 text-center" style="color:var(--text2); padding:60px 0;">
          <p style="font-family:'Space Mono',monospace; font-size:14px;">// No projects yet. Check back soon!</p>
        </div>`;
        return;
      }

      let html = '';
      projects.forEach(p => {
        const techs = (p.tech_stack || '').split(',').map(t =>
          `<span class="tech-badge">${t.trim()}</span>`
        ).join('');

        html += `
        <div class="col-md-6 col-lg-4 fade-up">
          <div class="project-card">
            <div class="project-title">${p.title}</div>
            <p class="project-desc">${p.description}</p>
            <div>${techs}</div>
            <div class="project-links">
              ${p.github_link ? `<a href="${p.github_link}" target="_blank" class="project-link"><i class="fa fa-github me-1"></i>GitHub</a>` : ''}
              ${p.live_link ? `<a href="${p.live_link}" target="_blank" class="project-link"><i class="fa fa-external-link me-1"></i>Live</a>` : ''}
            </div>
          </div>
        </div>`;
      });

      container.innerHTML = html;

      // Re-observe new elements
      container.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
    })
    .catch(() => {
      container.innerHTML = `<div class="col-12 text-center" style="color:var(--text2);">Failed to load projects.</div>`;
    });
}

document.addEventListener('DOMContentLoaded', loadProjects);