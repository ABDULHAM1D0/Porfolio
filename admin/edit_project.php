<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

require '../includes/db.php';

$id = (int)$_GET['id'];

// Handle update.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $tech_stack  = trim($_POST['tech_stack']);
    $github_link = trim($_POST['github_link']);
    $live_link   = trim($_POST['live_link']);

    $stmt = $pdo->prepare("UPDATE projects SET title=?, description=?, tech_stack=?, github_link=?, live_link=? WHERE id=?");
    $stmt->execute([$title, $description, $tech_stack, $github_link, $live_link, $id]);

    header("Location: dashboard.php");
    exit();
}

// Fetch project
$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$project) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">Edit Project</span>
    <a href="dashboard.php" class="btn btn-secondary btn-sm">Back to Dashboard</a>
</nav>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning">
            <h5 class="mb-0">Edit Project</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Project Title</label>
                        <input type="text" name="title" class="form-control" 
                               value="<?php echo htmlspecialchars($project['title']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tech Stack</label>
                        <input type="text" name="tech_stack" class="form-control" 
                               value="<?php echo htmlspecialchars($project['tech_stack']); ?>">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($project['description']); ?></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">GitHub Link</label>
                        <input type="text" name="github_link" class="form-control" 
                               value="<?php echo htmlspecialchars($project['github_link']); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Live Demo Link</label>
                        <input type="text" name="live_link" class="form-control" 
                               value="<?php echo htmlspecialchars($project['live_link']); ?>">
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-warning">Update Project</button>
                        <a href="dashboard.php" class="btn btn-secondary ms-2">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
