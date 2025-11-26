document.addEventListener('DOMContentLoaded', function() {
    const card = document.querySelector('.credit-card');
    
    if (card) {
        // Animación al hacer click
        card.addEventListener('click', function() {
            this.style.animation = 'pulse 0.5s ease';
            setTimeout(() => {
                this.style.animation = 'fadeInUp 0.8s ease-out, float 6s ease-in-out infinite';
            }, 500);
        });

        // Efecto 3D con movimiento del mouse
        card.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = (y - centerY) / 10;
            const rotateY = (centerX - x) / 10;
            
            this.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.05)`;
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });

        // Animación del chip
        const chip = document.querySelector('.card-chip');
        if (chip) {
            chip.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1) rotate(5deg)';
            });
            chip.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1) rotate(0deg)';
            });
        }

        // Animación de los números
        const numberGroups = document.querySelectorAll('.number-group');
        numberGroups.forEach((group, index) => {
            group.style.animation = `fadeInUp 0.5s ease-out ${index * 0.1}s both`;
        });
    }

    // Animación de los botones de seguridad
    const securityItems = document.querySelectorAll('.security-item');
    securityItems.forEach((item, index) => {
        item.style.animation = `fadeInUp 0.6s ease-out ${0.8 + index * 0.1}s both`;
    });
});

function editCard() {
    console.log('Editando tarjeta...');
    // Agregar lógica de edición
}

function deleteCard() {
    if (confirm('¿Estás seguro de que deseas eliminar esta tarjeta?')) {
        console.log('Eliminando tarjeta...');
        // Agregar lógica de eliminación
    }
}

function setAsDefault() {
    console.log('Estableciendo como principal...');
    // Agregar lógica para establecer como predeterminada
}

// Efecto ripple en botones
document.querySelectorAll('.btn-action').forEach(button => {
    button.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;

        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple-effect');

        this.appendChild(ripple);

        setTimeout(() => ripple.remove(), 600);
    });
});

// Añadir estilos para el efecto ripple
const style = document.createElement('style');
style.textContent = `
    .ripple-effect {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }

    @keyframes ripple-animation {
        to {
            transform: scale(2);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);