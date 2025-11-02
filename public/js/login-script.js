document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('container');
    const signUpBtn = document.getElementById('signUp');
    const signInBtn = document.getElementById('signIn');

    function toggleForm(isRegister) {
        if (!container) return;

        if (isRegister) {
            container.classList.add('active');
        } else {
            container.classList.remove('active');
        }
    }

    function checkCurrentRoute() {
        const path = window.location.pathname.toLowerCase();
        const isRegister = path.includes('registro') || path.includes('register');
        toggleForm(isRegister);
        document.title = isRegister ? 'Registro - TAS' : 'Login - TAS';
    }

    if (signUpBtn && signInBtn) {
        signUpBtn.addEventListener('click', (e) => {
            e.preventDefault();

            toggleForm(true);

            const url = signUpBtn.dataset.url || '/registro';
            window.history.pushState({ view: 'register' }, '', url);
            document.title = 'Registro - TAS';
        });

        signInBtn.addEventListener('click', (e) => {
            e.preventDefault();

            toggleForm(false);

            const url = signInBtn.dataset.url || '/login';
            window.history.pushState({ view: 'login' }, '', url);
            document.title = 'Login - TAS';
        });
    }

    window.addEventListener('popstate', checkCurrentRoute);

    checkCurrentRoute();
});
