<?php
import('org.rhaco.storage.db.Dao');
import('org.rhaco.net.xml.Atom');

class PlanetEntry extends Dao implements AtomInterface
{
    protected $id;
    protected $subscription_id;
    protected $title;
    protected $description;
    protected $link;
    protected $created;
    protected $updated;
    protected $deleted;
    static protected $__id__ = 'type=serial';
    static protected $__subscription_id__ = 'type=integer,require=true';
    static protected $__title__ = 'require=true';
    static protected $__description__ = 'type=text,require=true';
    static protected $__link__ = 'require=true,unique=true';
    static protected $__created__ = 'type=timestamp';
    static protected $__updated__ = 'type=timestamp';
    static protected $__deleted__ = 'type=timestamp';

    protected function __init__() {
        $this->created = $this->updated = time();
    }
    protected function __before_save__() {
        $this->updated = time();
    }

    public function atom_id() {
        return $this->id;
    }
    public function atom_title() {
        return $this->title;
    }
    public function atom_published() {
        return $this->created;
    }
    public function atom_updated() {
        return $this->updated;
    }
    public function atom_issued() {
        return $this->deleted;
    }
    public function atom_content() {
        return $this->description;
    }
    public function atom_summary() {
        return $this->description;
    }
    public function atom_href() {
        return $this->link;
    }
    public function atom_author() {
        return null;
    }
}
