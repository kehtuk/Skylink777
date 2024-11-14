import { defineConfig } from 'vite';

// Для React:
// import react from '@vitejs/plugin-react';
// Для Vue:
// import vue from '@vitejs/plugin-vue';

export default defineConfig({
    // plugins: [react()],  // Используйте эту строку, если вы работаете с React
    // plugins: [vue()],    // Используйте эту строку, если вы работаете с Vue
    server: {
        // Укажите порт, который будет использовать Vite (например, 3000)
        port: 3000
    },
    build: {
        // Папка, куда будут помещены файлы сборки
        outDir: 'public/dist',
        // Очистка папки перед сборкой
        emptyOutDir: true
    },
    // Корневая директория ваших статических файлов
    root: 'resources',  // например, `resources`, если файлы находятся в `resources/js`
});
