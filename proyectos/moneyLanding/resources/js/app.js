import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Atajo global CMD/CTRL + K para búsqueda
document.addEventListener('keydown', (event) => {
    if ((event.metaKey || event.ctrlKey) && (event.key === 'k' || event.key === 'K')) {
        event.preventDefault();
        document.dispatchEvent(new CustomEvent('open-search'));
    }
});

// Dark mode
const setThemeClass = (theme) => {
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
};

const storedTheme = localStorage.getItem('theme');
const preferredTheme = storedTheme ?? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
setThemeClass(preferredTheme);

// IMPORTANTE: Definir stores ANTES de Alpine.start()
Alpine.store('theme', {
    current: preferredTheme,
    toggle() {
        this.current = this.current === 'dark' ? 'light' : 'dark';
        localStorage.setItem('theme', this.current);
        setThemeClass(this.current);
    },
});

// Iniciar Alpine al final
Alpine.start();
