================
Planet on rhaco2
================

How to Install
--------------
::

  $ git clone git://github.com/riaf/Planet.git
  $ cd Planet
  $ php setup.php
  ...


Update Feeds
------------
::

  $ php setup.php -crawl


Configuration Sample
--------------------
__settings__.php ::

  def('org.rhaco.storage.db.Dbc@Planet', 'type=org.rhaco.storage.db.module.DbcMysql,dbname=planet,user=username,password=password,encode=utf8');
  def('Planet@sitename', 'My Planet');

