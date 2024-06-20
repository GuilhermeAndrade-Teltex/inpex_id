## Command Cron Windows

schtasks /create /sc minute /mo 5 /tn "Laravel Scheduler" /tr "C:\xampp\htdocs\argos\laravel_scheduler.bat"