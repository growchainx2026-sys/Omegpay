#!/bin/bash
cd /home/cashnex-app/htdocs/app.cashnex.com.br
php artisan queue:work --sleep=3 --tries=3 --timeout=90