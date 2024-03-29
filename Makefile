start: #запуск
	php artisan serve
install: #установить зависимости
	composer install
	cp -n .env.example .env || true
	php artisan key:gen --ansi
	mkdir -p database
	touch database/database.sqlite
	php artisan migrate --force
	npm install
lint: #запуск phpcs
	composer exec --verbose phpcs -- --standard=PSR12 app tests
test: #запуск локального теста
	php artisan test
test-coverage: #codeclimate
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml
deploy: #deploy to heroku
	git push heroku
