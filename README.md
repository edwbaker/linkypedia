linkypedia
==========

linkypedia is a webapp for seeing how 3rd party web content is being used on 
wikipedia.

This version has additional scripts by Ed Baker (http://ebaker.me.uk)
to provide additional ways of interpreting links to a domain from Wikipedia.

Setup
-----

* git clone https://github.com/edwbaker/linkypedia.git
* cd linkypedia
* pip install -r requirements.pip
  -- The above doesn;t always work - some pythin packages may need to be
  -- installed manually using easy_install
* create database and user/password permissions
* cd linkypedia
* cp settings.py.tmpl settings.py
* add database credentials to linkypedia/settings.py
* python manage.py syncdb
* python manage.py runserver
* python manage.py add_website http://www.loc.gov/
* python manage.py crawl # look for wikipedia articles referencing websites
* python manage.py load_users # scrape user info
* python manage.py stats # fetch page view stats from wikipedia
* optionally you can deploy with apache+mod_wsgi using conf/linkypedia.conf

License
-------
* CC0 - inherited from the original linkypedia project

