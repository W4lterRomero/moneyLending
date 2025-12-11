import './bootstrap';

// Atajo global CMD/CTRL + K para búsqueda
document.addEventListener('keydown', (event) => {
    if ((event.metaKey || event.ctrlKey) && (event.key === 'k' || event.key === 'K')) {
        event.preventDefault();
        window.dispatchEvent(new CustomEvent('open-search'));
    }
});

// Dark mode - DEFAULT TO LIGHT if no preference stored
document.addEventListener('livewire:init', () => {
    const setThemeClass = (theme) => {
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    };

    // Only use stored theme or default to LIGHT - don't auto-detect system preference
    const storedTheme = localStorage.getItem('theme');
    const preferredTheme = storedTheme || 'light'; // Default to light, not system preference
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
