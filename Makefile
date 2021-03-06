# TAO-SERVICES Makefile

PHP_COMPILE = /usr/bin/env php -w

.PHONY : install

install:
ifdef PREFIX
	@ echo -n "Creating lib tree..."
	@ find lib -type d | sed -e 's|^lib/|$(PREFIX)/lib/Service/|' | xargs mkdir -p
	@ echo "ok"
	@ echo -n "Installing modules..."
	@ find lib -type f -name '*.php' | sed -e 's|lib/\(.*\)|$(PHP_COMPILE) lib/\1 > $(PREFIX)/lib/Service/\1|' | sh
	@ echo "ok"
else
	@ echo 'Error: PREFIX is not set, installation cancelled'
endif
