<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

require '../includes/db.php';

if (isset($_POST['add_project'])) {
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $tech_stack  = trim($_POST['tech_stack']);
    $github_link = trim($_POST['github_link']);
    $live_link   = trim($_POST['live_link']);
    $stmt = $pdo->prepare("INSERT INTO projects (title, description, tech_stack, github_link, live_link) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $description, $tech_stack, $github_link, $live_link]);
    $success = "Project added successfully!";
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM projects WHERE id = ?")->execute([$id]);
    header("Location: dashboard.php");
    exit();
}

$projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$messages = $pdo->query("SELECT * FROM messages ORDER BY sent_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$proj_count = count($projects);
$msg_count  = count($messages);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    :root {
      --bg: #0a0e1a;
      --bg2: #0f1525;
      --surface: #1a2340;
      --border: rgba(99,179,237,0.15);
      --accent: #63b3ed;
      --accent2: #76e4c4;
      --accent3: #f6ad55;
      --text: #e2e8f0;
      --text2: #94a3b8;
      --text3: #64748b;
      --danger: #fc8181;
      --success: #68d391;
    }
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'DM Sans',sans-serif; background:var(--bg); color:var(--text); min-height:100vh; }

    /* SIDEBAR */
    .sidebar {
      position: fixed; top:0; left:0; bottom:0; width:240px;
      background: var(--bg2);
      border-right: 1px solid var(--border);
      padding: 24px 0;
      z-index: 100;
    }
    .sidebar-brand {
      font-family:'Space Mono',monospace;
      font-size:16px; color:var(--accent);
      padding: 0 24px 24px;
      border-bottom: 1px solid var(--border);
      margin-bottom: 16px;
    }
    .sidebar-brand span { color:var(--accent2); }
    .sidebar-link {
      display:flex; align-items:center; gap:12px;
      padding: 12px 24px;
      color: var(--text2);
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      transition: all 0.2s;
      border-left: 3px solid transparent;
    }
    .sidebar-link:hover, .sidebar-link.active {
      color: var(--accent);
      background: rgba(99,179,237,0.05);
      border-left-color: var(--accent);
    }
    .sidebar-link i { width:18px; text-align:center; }

    /* MAIN */
    .main-content {
      margin-left: 240px;
      padding: 32px;
      min-height: 100vh;
    }

    /* TOP BAR */
    .topbar {
      display:flex; justify-content:space-between; align-items:center;
      margin-bottom: 32px;
    }
    .topbar-title {
      font-family:'Space Mono',monospace;
      font-size:22px; color:var(--text);
    }
    .topbar-title span { color:var(--accent); }
    .btn-logout {
      font-family:'Space Mono',monospace;
      font-size:12px; color:var(--danger);
      background: rgba(252,129,129,0.1);
      border: 1px solid rgba(252,129,129,0.3);
      padding: 8px 18px; border-radius:6px;
      text-decoration:none; transition:all 0.2s;
    }
    .btn-logout:hover { background:rgba(252,129,129,0.2); color:var(--danger); }

    /* STAT CARDS */
    .stat-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 24px;
    }
    .stat-number {
      font-family:'Space Mono',monospace;
      font-size:36px; font-weight:700;
      color:var(--accent); line-height:1;
      margin-bottom:8px;
    }
    .stat-label { font-size:13px; color:var(--text2); text-transform:uppercase; letter-spacing:1px; }

    /* CARDS */
    .dash-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 12px;
      overflow: hidden;
      margin-bottom: 24px;
    }
    .dash-card-header {
      padding: 16px 24px;
      border-bottom: 1px solid var(--border);
      display:flex; align-items:center; justify-content:space-between;
    }
    .dash-card-title {
      font-family:'Space Mono',monospace;
      font-size:14px; color:var(--accent);
      text-transform:uppercase; letter-spacing:1px;
    }
    .dash-card-body { padding: 24px; }

    /* FORM */
    .form-control, .form-select {
      background: var(--bg) !important;
      border: 1px solid var(--border) !important;
      color: var(--text) !important;
      border-radius: 8px !important;
      padding: 10px 14px !important;
      font-size: 14px !important;
    }
    .form-control:focus {
      border-color: var(--accent) !important;
      box-shadow: 0 0 0 3px rgba(99,179,237,0.1) !important;
    }
    .form-control::placeholder { color:var(--text3) !important; }
    .form-label { font-size:12px; color:var(--text2); text-transform:uppercase; letter-spacing:1px; margin-bottom:6px; }

    .btn-add {
      font-family:'Space Mono',monospace;
      font-size:12px; font-weight:700;
      background: var(--accent); color:#0a0e1a;
      border:none; padding:10px 24px;
      border-radius:6px; cursor:pointer;
      transition:all 0.2s;
    }
    .btn-add:hover { background:#90cdf4; }

    /* TABLE */
    .dash-table { width:100%; border-collapse:collapse; }
    .dash-table th {
      font-family:'Space Mono',monospace;
      font-size:11px; color:var(--text3);
      text-transform:uppercase; letter-spacing:1px;
      padding: 10px 16px;
      border-bottom: 1px solid var(--border);
      text-align:left;
    }
    .dash-table td {
      padding: 14px 16px;
      font-size:14px; color:var(--text2);
      border-bottom: 1px solid rgba(99,179,237,0.05);
    }
    .dash-table tr:hover td { background:rgba(99,179,237,0.03); }

    .btn-edit {
      font-family:'Space Mono',monospace; font-size:11px;
      color:var(--accent3); background:rgba(246,173,85,0.1);
      border:1px solid rgba(246,173,85,0.3);
      padding:5px 12px; border-radius:4px;
      text-decoration:none; transition:all 0.2s; margin-right:6px;
    }
    .btn-edit:hover { background:rgba(246,173,85,0.2); color:var(--accent3); }

    .btn-del {
      font-family:'Space Mono',monospace; font-size:11px;
      color:var(--danger); background:rgba(252,129,129,0.1);
      border:1px solid rgba(252,129,129,0.3);
      padding:5px 12px; border-radius:4px;
      text-decoration:none; transition:all 0.2s;
    }
    .btn-del:hover { background:rgba(252,129,129,0.2); color:var(--danger); }

    .badge-tech {
      font-family:'Space Mono',monospace; font-size:10px;
      color:var(--accent2); background:rgba(118,228,196,0.1);
      border:1px solid rgba(118,228,196,0.25);
      padding:3px 8px; border-radius:4px;
    }

    .alert-success-custom {
      background:rgba(104,211,145,0.1); border:1px solid rgba(104,211,145,0.3);
      border-radius:8px; padding:14px 20px;
      font-family:'Space Mono',monospace; font-size:13px;
      color:var(--success); margin-bottom:20px;
    }
  </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
  <div class="sidebar-brand">admin<span>.</span>panel</div>
  <a href="#projects" class="sidebar-link active">
    <i class="fa fa-code"></i> Projects
  </a>
  <a href="#messages" class="sidebar-link">
    <i class="fa fa-envelope"></i> Messages
    <?php if ($msg_count > 0): ?>
      <span style="margin-left:auto; background:var(--accent); color:#0a0e1a;
                   font-size:11px; padding:2px 8px; border-radius:10px;
                   font-family:'Space Mono',monospace;"><?php echo $msg_count; ?></span>
    <?php endif; ?>
  </a>
  <a href="/portfolio/index.php" class="sidebar-link" target="_blank">
    <i class="fa fa-external-link"></i> View Site
  </a>
  <div style="position:absolute; bottom:24px; left:0; right:0; padding:0 16px;">
    <a href="logout.php" class="btn-logout" style="display:block; text-align:center;">
      <i class="fa fa-sign-out me-2"></i>Logout
    </a>
  </div>
</div>

<!-- MAIN -->
<div class="main-content">

  <!-- TOP BAR -->
  <div class="topbar">
    <div class="topbar-title">Dashboard <span>//</span> Overview</div>
    <div style="font-family:'Space Mono',monospace; font-size:12px; color:var(--text3);">
      Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
    </div>
  </div>

  <!-- STATS -->
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="stat-card">
        <div class="stat-number"><?php echo $proj_count; ?></div>
        <div class="stat-label">Projects</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-card">
        <div class="stat-number"><?php echo $msg_count; ?></div>
        <div class="stat-label">Messages</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-card">
        <div class="stat-number">4</div>
        <div class="stat-label">Publication</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-card">
        <div class="stat-number">4+</div>
        <div class="stat-label">Certifications</div>
      </div>
    </div>
  </div>

  <?php if (isset($success)): ?>
    <div class="alert-success-custom">✓ <?php echo $success; ?></div>
  <?php endif; ?>

  <!-- ADD PROJECT -->
  <div class="dash-card" id="projects">
    <div class="dash-card-header">
      <div class="dash-card-title"><i class="fa fa-plus me-2"></i>Add New Project</div>
    </div>
    <div class="dash-card-body">
      <form method="POST">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Project Title</label>
            <input type="text" name="title" class="form-control" placeholder="e.g. Pest Detection System" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Tech Stack</label>
            <input type="text" name="tech_stack" class="form-control" placeholder="e.g. PyTorch, OpenCV, Python">
          </div>
          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Describe the project..." required></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">GitHub Link</label>
            <input type="text" name="github_link" class="form-control" placeholder="https://github.com/...">
          </div>
          <div class="col-md-6">
            <label class="form-label">Live Demo Link</label>
            <input type="text" name="live_link" class="form-control" placeholder="https://...">
          </div>
          <div class="col-12">
            <button type="submit" name="add_project" class="btn-add">
              <i class="fa fa-plus me-2"></i>Add Project
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- PROJECTS TABLE -->
  <div class="dash-card">
    <div class="dash-card-header">
      <div class="dash-card-title"><i class="fa fa-code me-2"></i>All Projects</div>
      <span style="font-family:'Space Mono',monospace; font-size:12px; color:var(--text3);"><?php echo $proj_count; ?> total</span>
    </div>
    <div class="dash-card-body" style="padding:0;">
      <?php if (empty($projects)): ?>
        <div style="padding:40px; text-align:center; color:var(--text3); font-family:'Space Mono',monospace; font-size:13px;">
          // No projects yet. Add one above.
        </div>
      <?php else: ?>
        <table class="dash-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>Tech Stack</th>
              <th>Links</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($projects as $p): ?>
            <tr>
              <td style="color:var(--text3); font-family:'Space Mono',monospace;"><?php echo $p['id']; ?></td>
              <td style="color:var(--text); font-weight:500;"><?php echo htmlspecialchars($p['title']); ?></td>
              <td>
                <?php foreach (explode(',', $p['tech_stack']) as $t): ?>
                  <span class="badge-tech"><?php echo trim(htmlspecialchars($t)); ?></span>
                <?php endforeach; ?>
              </td>
              <td>
                <?php if ($p['github_link']): ?>
                  <a href="<?php echo $p['github_link']; ?>" target="_blank" style="color:var(--accent); font-size:13px; margin-right:8px;"><i class="fa fa-github"></i></a>
                <?php endif; ?>
                <?php if ($p['live_link']): ?>
                  <a href="<?php echo $p['live_link']; ?>" target="_blank" style="color:var(--accent2); font-size:13px;"><i class="fa fa-external-link"></i></a>
                <?php endif; ?>
              </td>
              <td style="font-family:'Space Mono',monospace; font-size:12px; color:var(--text3);">
                <?php echo date('d M Y', strtotime($p['created_at'])); ?>
              </td>
              <td>
                <a href="edit_project.php?id=<?php echo $p['id']; ?>" class="btn-edit"><i class="fa fa-pencil me-1"></i>Edit</a>
                <a href="?delete=<?php echo $p['id']; ?>" class="btn-del"
                   onclick="return confirm('Delete this project?')"><i class="fa fa-trash me-1"></i>Del</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>

  <!-- MESSAGES TABLE -->
  <div class="dash-card" id="messages">
    <div class="dash-card-header">
      <div class="dash-card-title"><i class="fa fa-envelope me-2"></i>Contact Messages</div>
      <span style="font-family:'Space Mono',monospace; font-size:12px; color:var(--text3);"><?php echo $msg_count; ?> total</span>
    </div>
    <div class="dash-card-body" style="padding:0;">
      <?php if (empty($messages)): ?>
        <div style="padding:40px; text-align:center; color:var(--text3); font-family:'Space Mono',monospace; font-size:13px;">
          // No messages yet.
        </div>
      <?php else: ?>
        <table class="dash-table">
          <thead>
            <tr><th>#</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date</th></tr>
          </thead>
          <tbody>
            <?php foreach ($messages as $m): ?>
            <tr>
              <td style="color:var(--text3); font-family:'Space Mono',monospace;"><?php echo $m['id']; ?></td>
              <td style="color:var(--text); font-weight:500;"><?php echo htmlspecialchars($m['name']); ?></td>
              <td style="color:var(--accent); font-size:13px;"><?php echo htmlspecialchars($m['email']); ?></td>
              <td><?php echo htmlspecialchars($m['subject']); ?></td>
              <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                <?php echo htmlspecialchars($m['message']); ?>
              </td>
              <td style="font-family:'Space Mono',monospace; font-size:12px; color:var(--text3);">
                <?php echo date('d M Y', strtotime($m['sent_at'])); ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>