


<?php  



?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tomaê - Login de Administrador</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    
    <div class="admin-login-page">
        <div class="login-card">
            <h2>Login Administrador</h2>
            <form action="admin_painel.php" method="POST">
                 <div class="form-group">
                 <label for="admin-email">E-mail</label>
                  <input type="text" id="user" name="user" required>
                 </div>
                         <div class="form-group">
                     <label for="admin-password">Senha</label>
                   <input type="password" id="senha" name="senha" required>
    </div>
    <button type="submit" class="login-btn">Entrar</button>
</form>
        </div>
    </div>

    <?php require_once("footer.html")?>
    <script src="script.js"></script> 
</body>
</html>