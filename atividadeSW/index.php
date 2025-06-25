<?php
session_start(); // sessão do usuario (teste)

// Class: conexão e controle do banco de dados SQLite
class Database {
    private $db;

    // Construtor: abre ou cria o banco
    public function __construct() {
        $this->db = new SQLite3('database.sqlite');
        $this->createTable();
    }

   // Cria a tabela de usuários (agora com telefone e CPF)
private function createTable() {
    $query = "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        telefone INTEGER,
        cpf INTEGER,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $this->db->exec($query);
}

// Adiciona um usuário ao banco de dados (agora com telefone e cpf)
 public function addUser($username, $email, $password, $telefone = null, $cpf = null) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Criptografa a senha
    $stmt = $this->db->prepare("INSERT INTO users (username, email, password, telefone, cpf) VALUES (:username, :email, :password, :telefone, :cpf)");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':password', $hashedPassword, SQLITE3_TEXT);
    $stmt->bindValue(':telefone', $telefone, SQLITE3_TEXT);
    $stmt->bindValue(':cpf', $cpf, SQLITE3_TEXT);
    return $stmt->execute();
}

    // Busca um usuário pelo nome de usuário
    public function getUserByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC);
    }
}

$db = new Database(); 
$error = '';   // Mensagem de erro
$success = ''; // Mensagem de sucesso

//  Processo de cadastro
if (isset($_POST['register'])) {
    // Recebe os dados do formulário
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $cpf = trim($_POST['cpf']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validação de campos
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Todos os campos são obrigatórios.';
    } elseif ($password !== $confirm_password) {
        $error = 'As senhas não coincidem.';
    } else {
        // Verifica se o usuário já existe
        $user = $db->getUserByUsername($username);
        if ($user) {
            $error = 'Nome de usuário já está em uso.';
        } else {
            // Adiciona o usuário
            if ($db->addUser($username, $email, $password)) {
                $success = 'Cadastro realizado com sucesso! Faça login.';
            } else {
                $error = 'Erro ao cadastrar. Tente novamente.';
            }
        }
    }
}

//  Processo de login
if (isset($_POST['login'])) {
    $username = trim($_POST['login_username']);
    $password = trim($_POST['login_password']);

    if (empty($username) || empty($password)) {
        $error = 'Por favor, preencha todos os campos.';
    } else {
        $user = $db->getUserByUsername($username);
        // Verifica se usuário existe e se a senha está correta
        if ($user && password_verify($password, $user['password'])) {
            // Salva dados na sessão e redireciona
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: home.php');
            exit();
        } else {
            $error = 'Credenciais inválidas.';
        }
    }
}

// Se já estiver logado, redireciona direto para a home
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Login e Cadastro</title>
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
            max-width: 400px;
            position: relative;
        }
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: absolute;
            width: 100%;
            opacity: 0;
            pointer-events: none;
        }
        .form-container.active {
            opacity: 1;
            pointer-events: all;
        }

        .form-title {
            margin-bottom: 1.5rem;
            color: #333;
            text-align: center;
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
        }
        .btn-primary, .btn-secondary {
            width: 100%;
            padding: 0.75rem;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 1rem;
        }

        .btn-primary {
            background-color: #4a90e2;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: #357abD;
        }

        .btn-secondary {
            background-color: transparent;
            color: #4a90e2;
            border: 1px solid #4a90e2;
        }

        .btn-secondary:hover {
            background-color: #f0f7ff;
        }
        input:invalid { border-color: #ff6b6b; }
        input:valid { border-color: #51cf66; }
        
        .error-message, .success-message {
            font-size: 0.9rem;
            margin-bottom: 1rem;
            padding: 0.5rem;
            border-radius: 4px;
            text-align: center;
        }

        .error-message {
            color: #ff6b6b;
            background-color: #fff5f5;
        }

        .success-message {
            color: #51cf66;
            background-color: #f5fff7;
        }

        .password-requirements {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Formulário de login -->
        <div class="form-container active" id="loginContainer">
            <form method="POST">
                <h2 class="form-title">Login</h2>

                <!-- Mensagem de erro ou sucesso após tentativa de login -->
                <?php if ($error && isset($_POST['login'])): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="success-message"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="login_username">Usuário</label>
                    <input type="text" id="login_username" name="login_username" required>
                </div>
                
                <div class="form-group">
                    <label for="login_password">Senha</label>
                    <input type="password" id="login_password" name="login_password" required>
                </div>
                
                <button type="submit" name="login" class="btn-primary">Entrar</button>
                <button type="button" class="btn-secondary" onclick="showRegister()">Criar conta</button>
            </form>
        </div>

        <!-- Formulário de cadastro -->
        <div class="form-container" id="registerContainer">
            <form method="POST">
                <h2 class="form-title">Cadastre-se</h2>

                <!-- Mensagem de erro no cadastro -->
                <?php if ($error && isset($_POST['register'])): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="username">Usuário</label>
                    <input type="text" id="username" name="username" required minlength="3">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="telefone">telefone</label>
                    <input type="text" id="telefone" name="telefone" placeholder="digite seu numero de telefone"required>
                </div>

                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf" placeholder="000.000.000.00" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" required minlength="6">
                    <div class="password-requirements">Mínimo 6 caracteres</div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirme a Senha</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" name="register" class="btn-primary">Cadastrar</button>
                <button type="button" class="btn-secondary" onclick="showLogin()">Já tem conta? Faça login</button>
            </form>
        </div>
    </div>


    <script>
        // Alterna entre os formulários
        function showRegister() {
            document.getElementById('loginContainer').classList.remove('active');
            document.getElementById('registerContainer').classList.add('active');
        }

        function showLogin() {
            document.getElementById('registerContainer').classList.remove('active');
            document.getElementById('loginContainer').classList.add('active');
        }

        // Validação em tempo real: confere se as senhas coincidem
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const confirmPassword = document.getElementById('confirm_password');
            
            if (password !== confirmPassword.value && confirmPassword.value !== '') {
                confirmPassword.setCustomValidity('As senhas não coincidem');
            } else {
                confirmPassword.setCustomValidity('');
            }
        });

        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword) {
                this.setCustomValidity('As senhas não coincidem');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>
