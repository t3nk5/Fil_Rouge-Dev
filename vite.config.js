import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import os from 'os';

const localIp = ((preferredInterfaceName = null) => {
    const interfaces = os.networkInterfaces();

    if (preferredInterfaceName && interfaces[preferredInterfaceName]) {
        for (const net of interfaces[preferredInterfaceName]) {
            if (net.family === 'IPv4' && !net.internal) {
                return net.address;
            }
        }
    }

    for (const name of Object.keys(interfaces)) {
        for (const net of interfaces[name]) {
            if (net.family === 'IPv4' && !net.internal) {
                return net.address;
            }
        }
    }

    return 'localhost';
})('Wi-Fi');

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        origin: `http://${localIp}:5173`,
        headers: {
            'Access-Control-Allow-Origin': '*',
            // 'Access-Control-Allow-Origin': `http://${localIp}:8000`,  // need to add 127.0.0.1 (localhost)
        },
    },
});
