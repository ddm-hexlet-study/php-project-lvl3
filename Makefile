start: #запуск
	php artisan serve --host 0.0.0.0
install: #установить зависимости
	composer install
	cp -n .env.example .env || true
	php artisan key:gen --ansi
	php artisan migrate: fresh
	npm ci
lint: #запуск phpcs
	composer exec --verbose phpcs -- --standard=PSR12 src
test: #запуск локального теста
	php artisan test
test-coverage: #codeclimate
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml
deploy: #deploy to heroku
	git push heroku
