import os, sys

from os.path import dirname, abspath

home = dirname(dirname(abspath(__file__)))

sys.path.append(home)
os.environ['DJANGO_SETTINGS_MODULE'] = 'linkypedia.settings'

import django.core.handlers.wsgi

application = django.core.handlers.wsgi.WSGIHandler()
