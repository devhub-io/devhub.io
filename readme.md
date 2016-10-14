# DevelopHub

## Queue

- nohup php artisan queue:work --tries=0 --queue=github-update &
- nohup php artisan queue:work --tries=0 --queue=github-analytics &
- nohup php artisan queue:work --tries=0 --queue=developer-update &
