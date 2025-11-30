class SessionTabManager {
    constructor() {
        this.tabId = this.generateTabId();
        this.storageKey = 'active_session_tab';
        this.lastActivityKey = 'last_tab_activity';
        this.checkInterval = null;
        this.isActive = false;
        
        this.init();
    }

    generateTabId() {
        return `tab_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
    }

    init() {
        const activeTab = localStorage.getItem(this.storageKey);
        
        if (!activeTab || this.isTabExpired()) {
            this.activateTab();
        } else if (activeTab !== this.tabId) {
            this.showTabConflictModal();
        } else {
            this.activateTab();
        }

        window.addEventListener('storage', (e) => {
            if (e.key === this.storageKey) {
                if (e.newValue && e.newValue !== this.tabId) {
                    this.deactivateTab();
                }
            }
        });

        window.addEventListener('beforeunload', () => {
            if (this.isActive) {
                localStorage.removeItem(this.storageKey);
                localStorage.removeItem(this.lastActivityKey);
            }
        });

        document.addEventListener('visibilitychange', () => {
            if (!document.hidden && this.isActive) {
                this.updateActivity();
            }
        });
    }

    isTabExpired() {
        const lastActivity = localStorage.getItem(this.lastActivityKey);
        if (!lastActivity) return true;
        
        const elapsed = Date.now() - parseInt(lastActivity);
        return elapsed > 5000;
    }

    activateTab() {
        this.isActive = true;
        localStorage.setItem(this.storageKey, this.tabId);
        this.updateActivity();
        
        this.checkInterval = setInterval(() => {
            this.updateActivity();
        }, 2000);

        this.enableInterface();
        this.hideOverlay();
    }

    deactivateTab() {
        this.isActive = false;
        clearInterval(this.checkInterval);
        
        this.disableInterface();
        this.showInactiveOverlay();
    }

    updateActivity() {
        if (this.isActive) {
            localStorage.setItem(this.lastActivityKey, Date.now().toString());
        }
    }

    showTabConflictModal() {
        Swal.fire({
            title: 'Sesión Activa en Otra Pestaña',
            html: `
                <div style="text-align: center;">
                    <i class="fas fa-window-restore fa-3x mb-3" style="color: #005B96;"></i>
                    <p style="margin: 20px 0; font-size: 16px;">
                        Ya tienes una sesión activa en otra pestaña.
                    </p>
                    <p style="color: #666; font-size: 14px;">
                        ¿Deseas usar TAS en esta pestaña?
                    </p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check me-2"></i>Usar aquí',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Cancelar',
            confirmButtonColor: '#005B96',
            cancelButtonColor: '#6c757d',
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then((result) => {
            if (result.isConfirmed) {
                this.activateTab();
                
                Swal.fire({
                    title: 'Sesión Transferida',
                    text: 'Ahora estás usando TAS en esta pestaña',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    confirmButtonColor: '#005B96'
                });
            } else {
                this.deactivateTab();
            }
        });
    }

    disableInterface() {
        const inputs = document.querySelectorAll('input, button, textarea, select, a');
        inputs.forEach(el => {
            el.style.pointerEvents = 'none';
            el.style.opacity = '0.5';
        });
    }

    enableInterface() {
        const inputs = document.querySelectorAll('input, button, textarea, select, a');
        inputs.forEach(el => {
            el.style.pointerEvents = '';
            el.style.opacity = '';
        });
    }

    showInactiveOverlay() {
        let overlay = document.getElementById('inactive-tab-overlay');
        
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.id = 'inactive-tab-overlay';
            overlay.innerHTML = `
                <div class="inactive-content">
                    <i class="fas fa-window-restore fa-4x mb-3"></i>
                    <h3>Sesión Activa en Otra Pestaña</h3>
                    <p>Estás usando TAS en otra pestaña o ventana.</p>
                    <button id="btn-usar-aqui" class="btn-usar-aqui">
                        <i class="fas fa-sync-alt me-2"></i>Usar en Esta Pestaña
                    </button>
                </div>
            `;
            document.body.appendChild(overlay);

            const style = document.createElement('style');
            style.textContent = `
                #inactive-tab-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.95);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 99999;
                    backdrop-filter: blur(10px);
                }

                .inactive-content {
                    text-align: center;
                    color: white;
                    padding: 40px;
                    max-width: 500px;
                }

                .inactive-content i {
                    color: #00A8CC;
                }

                .inactive-content h3 {
                    font-size: 28px;
                    margin-bottom: 15px;
                    font-weight: 600;
                }

                .inactive-content p {
                    font-size: 16px;
                    color: #ccc;
                    margin-bottom: 30px;
                }

                .btn-usar-aqui {
                    background: linear-gradient(135deg, #005B96 0%, #00A8CC 100%);
                    color: white;
                    border: none;
                    padding: 15px 30px;
                    font-size: 16px;
                    border-radius: 10px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    font-weight: 600;
                    box-shadow: 0 4px 15px rgba(0, 91, 150, 0.3);
                }

                .btn-usar-aqui:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(0, 91, 150, 0.4);
                }

                .btn-usar-aqui i {
                    color: white;
                }
            `;
            document.head.appendChild(style);

            document.getElementById('btn-usar-aqui').addEventListener('click', () => {
                this.activateTab();
                Swal.fire({
                    title: 'Sesión Transferida',
                    text: 'Ahora estás usando TAS en esta pestaña',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    confirmButtonColor: '#005B96'
                });
            });
        }

        overlay.style.display = 'flex';
    }

    hideOverlay() {
        const overlay = document.getElementById('inactive-tab-overlay');
        if (overlay) {
            overlay.style.display = 'none';
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const isAuthenticated = document.body.dataset.authenticated === 'true';
    
    if (isAuthenticated) {
        window.sessionManager = new SessionTabManager();
    }
});

window.SessionTabManager = SessionTabManager;