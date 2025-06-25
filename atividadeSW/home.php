<?php
// Inicia o php
session_start();

// Verifica se o usuário está logado; caso contrário, redireciona para a página de login (index.php)
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Recupera o nome de usuário da sessão
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Configurações de codificação -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área Restrita</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 800px;
        }
        .dashboard {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        .welcome {
            color: #333;
        }
        .username {
            color: #4a90e2;
            font-weight: bold;
        }
        .btn-logout {
            padding: 0.5rem 1rem;
            background-color: #ff6b6b;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-logout:hover {
            background-color: #ff5252;
        }
        .dashboard-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        .card {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            color: #4a90e2;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        .card-content {
            color: #555;
            line-height: 1.6;
        }
        .footer {
            margin-top: 2rem;
            text-align: center;
            color: #888;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="dashboard">
            <!-- Cabecalho de bem vindo(a) -->
            <div class="header">
                <h1 class="welcome">Bem-vindo, <span class="username"><?php echo htmlspecialchars($username); ?></span>!</h1>
                <form method="post" action="logout.php">
                    <button type="submit" class="btn-logout">Sair</button>
                </form>
            </div>
            
            <!-- Conteúdo principal com os cardss -->
            <div class="dashboard-content">
                <!--Perfil do Usuário -->
                <div class="card">
                    <h2 class="card-title">Perfil do Usuário</h2>
                    <div class="card-content">
                        <p>edite suas informações pessoais e visualise seu cadastro, se necessario altere ssua senha e configure sua conta (não funciona de verdade)</p>
                    </div>
                </div>
                
                <!--Configurações -->
                <div class="card">
                    <h2 class="card-title">Configurações</h2>
                    <div class="card-content">
                        <p>Personalize o sistema do nosso site ajustando as configurações de privacidade, notificações e preferências de exibição.(tbm nao funciona)</p>
                    </div>
                </div>
                
                <!--Estatísticas -->
                <div class="card">
                    <h2 class="card-title">Estatísticas</h2>
                    <div class="card-content">
                        <p>Acompanhe suas atividades, visualizações, tempo de uso e outras métricas importantes para seu desempenho.(adivinha kakakka)</p>
                    </div>
                </div>
                
                <!--Suporte -->
                <div class="card">
                    <h2 class="card-title">Suporte</h2>
                    <div class="card-content">
                        <p>Precisa de ajuda? Entre em contato com nosso suporte técnico ou consulte nossa base de conhecimento.(nem perca seu tempo)</p>
                    </div>
                </div>
            </div>
            
            <!-- teste de rodape (primeira vez que uso isso llkkkkk) -->
            <div class="footer">
                <p>https://static.wikia.nocookie.net/fiction-battlefield/images/8/88/Dantedmc3.png/revision/latest?cb=20181107230903&path-prefix=pt-br &copy; <?php echo date('Y'); ?></p> 
            </div>
        </div>
    </div>
</body>
</html>