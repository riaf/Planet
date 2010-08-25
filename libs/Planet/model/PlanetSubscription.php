<?php
import('org.rhaco.storage.db.Dao');

class PlanetSubscription extends Dao
{
    protected $id;
    protected $title;
    protected $link;
    protected $rss_url;
    protected $author;
    protected $created;
    protected $updated;
    protected $deleted;
    static protected $__id__ = 'type=serial';
    static protected $__title__ = 'require=true';
    static protected $__link__ = 'require=true';
    static protected $__rss_url__ = 'unique=true,unique=true';
    static protected $__author__ = 'require=true';
    static protected $__created__ = 'type=timestamp';
    static protected $__updated__ = 'type=timestamp';
    static protected $__deleted__ = 'type=timestamp';

    protected function __init__() {
        $this->created = $this->updated = time();
    }

    protected function __before_create__() {
        $this->rss_url = $this->link;
    }
    protected function __before_save__() {
        $this->updated = time();
    }
}
