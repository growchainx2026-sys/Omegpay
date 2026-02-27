-- Execute este SQL no MySQL se não puder rodar: php artisan migrate
-- Adiciona a coluna login_background na tabela settings (background da tela de login)

ALTER TABLE `settings` ADD COLUMN `login_background` VARCHAR(255) NULL;
