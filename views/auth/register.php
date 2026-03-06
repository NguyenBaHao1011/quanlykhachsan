<!DOCTYPE html>
<html>
<head>
    <title>Đăng ký</title>
    <link rel="stylesheet" href="public/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h3>Đăng ký</h3>

<form method="POST">
    <input class="form-control mb-2" name="username" placeholder="Username">
    <input class="form-control mb-2" name="email" placeholder="Email">
    <input class="form-control mb-2" name="password" type="password" placeholder="Password">

    <select class="form-control mb-3" name="role_id">
        <option value="1">Admin</option>
        <option value="2">Nhân viên</option>
    </select>

    <button class="btn btn-primary">Đăng ký</button>
</form>

</body>
</html>
