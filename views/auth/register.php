<?php
session_start();
$pageTitle = "Register";
ob_start();
?>


<div class="container-fluid mt-5">
    <h2>Register</h2>
    <form method="POST" action="proses_register.php">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select class="form-control" name="role" required>
                <option value="admin">Admin</option>
                <option value="user1">User1</option>
                <option value="user2">User2</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>


<?php
$content = ob_get_clean();
include '../layout.php';