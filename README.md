linkypedia
==========

linkypedia is a webapp for seeing how 3rd party web content is being used on 
wikipedia.

This version has additional scripts by Ed Baker (http://ebaker.me.uk)
to provide additional ways of interpreting links to a domain from Wikipedia.
This work is being done to provide some metrics for analysing how an
organisation can monitor it's Wikipedia impact over time·

Setup (linkypedia)
------------------
The following are what I need to run on Ubuntu 12.10 to get linkypedia 
installed
* sudo apt-get install libmysqlclient-dev
* sudo apt-get install python-pip python-dev
* sudo easy_install -U distribute
* sudo pip install -U pip

If individual packages fail they can be installed by, e.g.:
* sudo easy_install django celery
* sudo easy_install django-celery

Linkypedia install:
* git clone https://github.com/edwbaker/linkypedia.git
* cd linkypedia
* pip install -r requirements.pip (Doesn't always work - some python packages may need to be  installed manually using easy_install)
* create database and user/password permissions
* cd linkypedia
* cp settings.py.tmpl settings.py
* add database credentials to linkypedia/settings.py
* python manage.py syncdb

* pythin manage.py migrate

* python manage.py runserver
* python manage.py add_website http://www.loc.gov/
* python manage.py crawl # look for wikipedia articles referencing websites
* python manage.py load_users # scrape user info
* python manage.py stats # fetch page view stats from wikipedia
* optionally you can deploy with apache+mod_wsgi using conf/linkypedia.conf

Setup - Addons

License
-------
* CC0 - inherited from the original linkypedia project

