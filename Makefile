SHELL = bash

docs = $(shell find doc -name '*.md' \
				|sed 's|.md|.1|g' \
				|sed 's|doc/|man1/|g' )

install: 
	bin/phark-install	

man1: 
	[ -d man1 ] || mkdir -p man1

doc: man1 $(docs)

# use `gem install ronn` for this to work.
man1/%.1: doc/%.md
	which ronn || gem install ronn
	ronn -r --pipe $< > $@

test:
	php tests/all.php
