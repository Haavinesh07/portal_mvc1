<?php require_once __DIR__ . '/../views/header.php'; ?>

<header class="bg-primary text-white py-5">
    <div class="container">
        <h1 class="display-5 fw-bold mb-1">Student Profile</h1>
        <p class="lead mb-0">Profile data is loaded only from the active login session</p>
    </div>
</header>

<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <?php if ($success || $error): ?>
                    <div class="alert alert-<?php echo $success ? 'success' : 'danger'; ?>" role="alert">
                        <?php echo htmlspecialchars($success ?: $error); ?>
                    </div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h2 class="h5 mb-0">Personal Information</h2>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-sm-row align-items-center gap-4 mb-4">
                            <?php if ($profilePictureUrl): ?>
                                <img src="<?php echo htmlspecialchars($profilePictureUrl); ?>"
                                    alt="Profile picture"
                                    class="rounded-circle border object-fit-cover"
                                    style="width: 120px; height: 120px;">
                            <?php else: ?>
                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center border"
                                    style="width: 120px; height: 120px;">
                                    <i class="fa-solid fa-user fs-1"></i>
                                </div>
                            <?php endif; ?>

                            <form action="index.php?action=upload_profile_picture" method="post"
                                enctype="multipart/form-data" class="w-100">
                                <label for="profile_picture" class="form-label fw-semibold">Profile Picture</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="profile_picture" name="profile_picture"
                                        accept=".jpg,.jpeg,.png,image/jpeg,image/png" required>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-upload me-2"></i>Upload
                                    </button>
                                </div>
                                <div class="form-text">JPG, JPEG, or PNG. Maximum size: 2MB.</div>
                            </form>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted">Name</div>
                            <div class="col-sm-8 fw-semibold"><?php echo htmlspecialchars($profile['name']); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted">NRIC</div>
                            <div class="col-sm-8"><code><?php echo htmlspecialchars($profile['nric']); ?></code></div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-4 text-muted">Program</div>
                            <div class="col-sm-8"><?php echo htmlspecialchars($profile['program']); ?></div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <a href="index.php?action=change_password" class="btn btn-primary">
                                <i class="fa-solid fa-key me-2"></i>Change Password
                            </a>
                            <a href="index.php?action=dashboard" class="btn btn-outline-secondary">Back to Grades</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../views/footer.php'; ?>
