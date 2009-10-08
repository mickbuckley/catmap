VERSION=0.1
RELEASE_FILE=catmap-$(VERSION).tar

SOURCES =
SOURCES += catmap.xml
SOURCES += catmap.php
SOURCES += catmap/add_to_article.xml
SOURCES += catmap/catmap.js
SOURCES += catmap/catmap.css

install: $(RELEASE_FILE)
	@echo "install complete $(RELEASE_FILE)"

$(RELEASE_FILE) : $(SOURCES)
	tar cvf $@ $(SOURCES)

