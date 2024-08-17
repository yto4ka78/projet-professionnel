//Form registration

document.addEventListener('DOMContentLoaded', function() {
    const formRegister = document.querySelector('#form_register_js');
    
    formRegister.addEventListener('submit', function(e){
        e.preventDefault();
        
        fetch(formRegister.dataset.url, {
            method: formRegister.method,
            body: new FormData(formRegister)
        })
        .then(response => response.json())
        .then(data => {
            handleFormResponse(data);
        });
    });

    function handleFormResponse(data) {
        const formFields = formRegister.querySelectorAll('.form_input');
        formFields.forEach(field => {
            const errorElement = field.parentElement.querySelector('.invalid-feedback');
            errorElement.innerText = '';
            field.classList.remove('is-invalid');
        });

        if (data.errors) {
            Object.keys(data.errors).forEach(key => {
                const field = formRegister.querySelector(`[name*="${key}"]`);
                if (field) {
                    const errorElement = field.parentElement.querySelector('.invalid-feedback');
                    errorElement.innerText = data.errors[key];
                    field.classList.add('is-invalid');
                }
            });
        } else if (data.success) {
            window.location.href = data.redirect_url;
        }
        
    }
});

// form login

document.addEventListener('DOMContentLoaded', function() {
    const formLogin = document.querySelector('#form_login_js');
    
    formLogin.addEventListener('submit', function(e){
        e.preventDefault();
    
        fetch(formLogin.dataset.url, {
            method: formLogin.method,
            body: new FormData(formLogin),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            handleFormResponse(data);
        })
    });

    function handleFormResponse(data) {
        const feedback = document.querySelector('.invalid-feedback-login');
        if (data.success) {
            if (data.redirect_url) {
                window.location.href = data.redirect_url;
            }
        } else {
            // Показать ошибку
            feedback.textContent = "L'adresse e-mail ou le mot de passe est incorrect.";
            feedback.style.display = 'block';
        }
    }
});