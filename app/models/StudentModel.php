<?php
namespace App\Models;

class StudentModel
{
    public function __construct(private \mysqli $conn)
    {
    }

    public function calculateGrade(int $marks): string
    {
        return match (true) {
            $marks >= 80 => 'A',
            $marks >= 70 => 'B',
            $marks >= 60 => 'C',
            $marks >= 50 => 'D',
            default => 'F',
        };
    }

    public function registerUser(string $name, string $nric, string $program, string $password): bool
    {
        return $this->execute(
            "INSERT INTO users (name, nric, program, password) VALUES (?, ?, ?, ?)",
            'ssss',
            [$name, $nric, $program, password_hash($password, PASSWORD_DEFAULT)]
        );
    }

    public function verifyLogin(string $nric, string $password): array|false
    {
        $user = $this->one("SELECT * FROM users WHERE nric = ? LIMIT 1", 's', [$nric]);
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        return false;
    }

    public function getCurrentUser(int $id): array|false
    {
        return $this->one("SELECT id, name, nric, program, profile_picture FROM users WHERE id = ?", 'i', [$id]);
    }

    public function updateUserProfilePicture(int $id, string $filename): bool
    {
        return $this->execute(
            "UPDATE users SET profile_picture = ? WHERE id = ?",
            'si',
            [$filename, $id]
        );
    }

    public function verifyUserPassword(int $id, string $password): bool
    {
        $user = $this->one("SELECT password FROM users WHERE id = ?", 'i', [$id]);
        return $user && password_verify($password, $user['password']);
    }

    public function updateUserPassword(int $id, string $password): bool
    {
        return $this->execute(
            "UPDATE users SET password = ? WHERE id = ?",
            'si',
            [password_hash($password, PASSWORD_DEFAULT), $id]
        );
    }

    public function getAllStudents(): array
    {
        return $this->all("SELECT id, name, ic, marks FROM student_grades ORDER BY id ASC");
    }

    public function getStudent(int $id): array|false
    {
        return $this->one("SELECT id, name, ic, marks FROM student_grades WHERE id = ?", 'i', [$id]);
    }

    public function createStudent(string $name, string $ic, int $marks): bool
    {
        return $this->execute(
            "INSERT INTO student_grades (name, ic, marks) VALUES (?, ?, ?)",
            'ssi',
            [$name, $ic, $marks]
        );
    }

    public function updateStudent(int $id, string $name, string $ic, int $marks): bool
    {
        return $this->execute(
            "UPDATE student_grades SET name = ?, ic = ?, marks = ? WHERE id = ?",
            'ssii',
            [$name, $ic, $marks, $id]
        );
    }

    public function deleteStudent(int $id): bool
    {
        return $this->execute("DELETE FROM student_grades WHERE id = ?", 'i', [$id]);
    }

    private function all(string $sql, string $types = '', array $params = []): array
    {
        $stmt = $this->statement($sql, $types, $params);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    private function one(string $sql, string $types, array $params): array|false
    {
        $rows = $this->all($sql, $types, $params);
        return $rows[0] ?? false;
    }

    private function execute(string $sql, string $types, array $params): bool
    {
        try {
            $stmt = $this->statement($sql, $types, $params);
            $ok = $stmt->execute();
            $stmt->close();
            return $ok;
        } catch (\mysqli_sql_exception) {
            return false;
        }
    }

    private function statement(string $sql, string $types = '', array $params = []): \mysqli_stmt
    {
        $stmt = $this->conn->prepare($sql);
        if ($types !== '') {
            $stmt->bind_param($types, ...$params);
        }
        return $stmt;
    }
}
