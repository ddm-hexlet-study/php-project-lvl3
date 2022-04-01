install: #установить зависимости
	composer install
lint: #запуск phpcs
	composer exec --verbose phpcs -- --standard=PSR12 public
