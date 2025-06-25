document.addEventListener('DOMContentLoaded', () => {
 const loginContainer = document.getElementById('login-container');
    const registerContainer = document.getElementById('register-container');
    const toRegisterBtn = document.getElementById('to-register');
    const toLoginBtn = document.getElementById('to-login');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const passwordInput = document.getElementById('register-password');
    const confirmPasswordInput = document.getElementById('register-confirm-password');

    toRegisterBtn.addEventListener('click', toggleForms);
    toLoginBtn.addEventListener('click', toggleForms);

    confirmPasswordInput.addEventListener('input', validatePasswordMatch);

    loginForm.addEventListener('submit', handleLoginSubmit);

    registerForm.addEventListener('submit', handleRegisterSubmit);

    const cpfInput = document.getElementById('register-cpf');
    cpfInput.addEventListener('input', formatCPF);

    const phoneInput = document.getElementById('register-phone');
    phoneInput.addEventListener('input', formatPhone);

    function toggleForms() {
        loginContainer.classList.toggle('active');
        registerContainer.classList.toggle('active');
    }

    function validatePasswordMatch() {
        if (passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordInput.setCustomValidity('As senhas não coincidem');
        } else {
            confirmPasswordInput.setCustomValidity('');
        }
    }

    function formatCPF(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        if (value.length > 3) {
            value = value.replace(/^(\d{3})/, '$1.');
        }
        if (value.length > 7) {
            value = value.replace(/^(\d{3})\.(\d{3})/, '$1.$2.');
        }
        if (value.length > 11) {
            value = value.replace(/^(\d{3})\.(\d{3})\.(\d{3})/, '$1.$2.$3-');
        }
        
        e.target.value = value.substring(0, 14);
    }

    function formatPhone(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        if (value.length > 0) {
            value = value.replace(/^(\d{0,2})/, '($1)');
        }
        if (value.length > 3) {
            value = value.replace(/^(\(\d{2}\))(\d)/, '$1 $2');
        }
        if (value.length > 10) {
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
        }
        
        e.target.value = value.substring(0, 15);
    }

    function handleLoginSubmit(e) {
        e.preventDefault();
        console.log('Login attempt with:', {
            email: document.getElementById('login-email').value,
            password: document.getElementById('login-password').value
        });
        alert('Login realizado com sucesso!');
    }

    function handleRegisterSubmit(e) {
        e.preventDefault();
        
        if (passwordInput.value !== confirmPasswordInput.value) {
            alert('As senhas não coincidem!');
            return;
        }

        console.log('Registration data:', {
            name: document.getElementById('register-name').value,
            email: document.getElementById('register-email').value,
            phone: document.getElementById('register-phone').value,
            cpf: document.getElementById('register-cpf').value,
            password: passwordInput.value
        });
        
        alert('Cadastro realizado com sucesso!');
        toggleForms();
    }
});