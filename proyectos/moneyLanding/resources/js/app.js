import './bootstrap';

// Atajo global CMD/CTRL + K para búsqueda
document.addEventListener('keydown', (event) => {
    if ((event.metaKey || event.ctrlKey) && (event.key === 'k' || event.key === 'K')) {
        event.preventDefault();
        window.dispatchEvent(new CustomEvent('open-search'));
    }
});

// Dark mode - Usamos document.addEventListener para esperar a que Livewire/Alpine carguen
document.addEventListener('livewire:init', () => {
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

    // Registrar store de Alpine (Livewire 3 incluye Alpine automáticamente)
    if (window.Alpine) {
        Alpine.store('theme', {
            current: preferredTheme,
            toggle() {
                this.current = this.current === 'dark' ? 'light' : 'dark';
                localStorage.setItem('theme', this.current);
                setThemeClass(this.current);
            },
        });
    }
});
