import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/custom.css',
                'resources/css/theme.css',
                'resources/js/app.js',
                'resources/js/custom.js',
                'resources/js/theme.js',
                'resources/js/components/utils/btn_open_modal.module.js',
                'resources/js/components/perfil.module.js',
                'resources/js/pages/role/role-list.module.js',
                'resources/js/pages/user/user-list.module.js',
                'resources/js/pages/client/client-list.module.js',
                'resources/js/pages/school/school-list.module.js',
                'resources/js/pages/student/student-edit.module.js',
                'resources/js/pages/student/student-list.module.js',
                'resources/js/pages/student/student-create.module.js',
                'resources/js/components/app_components/spinner_loading.module.js',
                'resources/js/components/utils/modal.module.js',
                'resources/css/components/utils/btn.css',
                'resources/css/dropzone/dropzone-amd-module.js',
            ],
            refresh: true,
        }),
    ],
});
