<!DOCTYPE html>
<html>
<head>
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="public/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h3>Đăng nhập</h3>

<?php if (!empty($error)): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="POST">
    <input class="form-control mb-2" name="username" placeholder="Username">
    <input class="form-control mb-3" name="password" type="password" placeholder="Password">
    <button class="btn btn-success">Đăng nhập</button>
</form>

</body>
</html>
