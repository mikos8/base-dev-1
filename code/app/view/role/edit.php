<?php
$title = 'Edit role';
require_once ROOT_DIR . '/app/models/role/Role.php';
ob_start();
?>

<div class="bg-dark h-100">
    <div class="container">
        <h1>Edit Role</h1>
    </div>
    <form action="index.php?page=roles&action=update" method="post">
        <input type="hidden" name="id" value="<?=$role['id'] ?>">
        <div class="form-group">
            <label for="role_name">Role name</label>
            <input type="text" class="form-control" id="role_name" name="role_name" value="<?=$role['role_name'] ?>" required />
        </div>
        <div class="form-group">
            <label for="role_description">Role description </label>
            <input type="text" class="form-control" id="role_description" name="role_description" value="<?=$role['role_description'] ?>" required/>
        </div>
        <button type="submit" class="btn btn-primary">Update role</button>
    </form>
</div>



<?php
$content = ob_get_clean();
include ROOT_DIR . '/app/view/layout.php';
?>
