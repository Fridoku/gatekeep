test
{{ ( parse_url($_SERVER['HTTP_REFERER'], PHP_HOST) == parse_url(env('APP_URL'), PHP_HOST)) ? '<a href="'.$_SERVER['HTTP_REFERER'].'" role="button" class="btn btn-secondary">Return</a>'}}
