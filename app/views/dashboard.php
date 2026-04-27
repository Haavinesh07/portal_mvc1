<?php require_once __DIR__ . '/../views/header.php'; ?>

<?php
$totalStudents = count($students);
$totalMarks = array_sum(array_map(fn ($student) => (int) $student['marks'], $students));
$avgMarks = $totalStudents > 0 ? round($totalMarks / $totalStudents, 2) : 0;
$passCount = 0;
$failCount = 0;

foreach ($students as $student) {
    $model->calculateGrade((int) $student['marks']) === 'F' ? $failCount++ : $passCount++;
}
?>

<header class="bg-primary text-white py-5">
    <div class="container">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <h1 class="display-5 fw-bold mb-1">Grade Management</h1>
                <p class="lead mb-0">Database-driven student marks using MVC and prepared statements</p>
            </div>
            <a class="btn btn-light btn-lg" href="index.php?action=profile">
                <i class="fa-solid fa-user-shield me-2"></i>My Profile
            </a>
        </div>
    </div>
</header>

<main class="py-5">
    <div class="container">
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total Students</p>
                        <h2 class="h1 text-primary mb-0"><?php echo $totalStudents; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Average Marks</p>
                        <h2 class="h1 text-info mb-0"><?php echo $avgMarks; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Passed</p>
                        <h2 class="h1 text-success mb-0"><?php echo $passCount; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Failed</p>
                        <h2 class="h1 text-danger mb-0"><?php echo $failCount; ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h2 class="h5 mb-0">Add Student Record</h2>
                    </div>
                    <div class="card-body">
                        <form method="post" action="index.php?action=create_student">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" maxlength="100" required>
                            </div>
                            <div class="mb-3">
                                <label for="ic" class="form-label">IC</label>
                                <input type="text" class="form-control" id="ic" name="ic" pattern="[0-9]{12}"
                                    maxlength="12" required>
                            </div>
                            <div class="mb-4">
                                <label for="marks" class="form-label">Marks</label>
                                <input type="number" class="form-control" id="marks" name="marks" min="0" max="100" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa-solid fa-plus me-2"></i>Add Record
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0">Student Records</h2>
                        <span class="badge text-bg-primary"><?php echo $totalStudents; ?> rows</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">#</th>
                                        <th>Name</th>
                                        <th>IC</th>
                                        <th class="text-center">Marks</th>
                                        <th class="text-center">Grade</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($students)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-5">No student records found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($students as $index => $student): ?>
                                            <?php
                                            $marks = (int) $student['marks'];
                                            $grade = $model->calculateGrade($marks);
                                            $badgeClass = match ($grade) {
                                                'A', 'B' => 'text-bg-success',
                                                'C' => 'text-bg-primary',
                                                'D' => 'text-bg-warning',
                                                default => 'text-bg-danger',
                                            };
                                            ?>
                                            <tr>
                                                <td class="ps-4"><?php echo $index + 1; ?></td>
                                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                                                <td><code><?php echo htmlspecialchars($student['ic']); ?></code></td>
                                                <td class="text-center fw-semibold"><?php echo $marks; ?></td>
                                                <td class="text-center">
                                                    <span class="badge <?php echo $badgeClass; ?>"><?php echo $grade; ?></span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <a href="index.php?action=edit_student&id=<?php echo (int) $student['id']; ?>"
                                                        class="btn btn-sm btn-outline-warning">Edit</a>
                                                    <a href="index.php?action=delete_student&id=<?php echo (int) $student['id']; ?>"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Delete this student record permanently?');">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../views/footer.php'; ?>
