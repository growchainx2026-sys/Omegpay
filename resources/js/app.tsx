import '../css/app.css';

import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { ChakraProvider } from '@chakra-ui/react';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => title ? `${title} - ${appName}` : appName,
    resolve: (name) => resolvePageComponent(
        `./pages/${name}.tsx`, 
        import.meta.glob('./pages/**/*.tsx', { eager: true })  // ← ADICIONADO: { eager: true }
    ),
    setup({ el, App, props }) {
        const root = createRoot(el);
        root.render(
            <ChakraProvider>
                <App {...props} />
            </ChakraProvider>
        );
    },
    progress: {
        color: '#4B5563',
    },
});