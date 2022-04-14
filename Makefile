info:
	echo "nothing"

install: composer_install

composer_install:
	docker exec -it cyldiscordbot_php_1 php composer.phar install
