<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSP Student Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
        integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="index.php">PSP Student Portal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php if (isset($_SESSION['logged_in'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=dashboard">Grades</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=profile">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=change_password">Password</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <?php if (isset($_SESSION['logged_in'])): ?>
                        <li class="nav-item">
                            <span class="navbar-text me-lg-3">
                                <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Student'); ?>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-danger btn-sm" href="index.php?action=logout">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item me-lg-2">
                            <a class="nav-link" href="index.php?action=login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm" href="index.php?action=register">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
