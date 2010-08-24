<?php
import('org.rhaco.net.xml.Feed');
module('model.PlanetEntry');
module('model.PlanetSubscription');

class Planet extends Flow
{
    public function index() {
        $paginator = new Paginator(20, $this->in_vars('page', 1));
        $this->vars('entries', C(PlanetEntry)->find_page($this->in_vars('q'), $paginator, '-updated'));
        $this->vars('paginator', $paginator);
    }

    public function add_subscription() {
        if ($this->is_post()) {
            try {
                $subscription = new PlanetSubscription();
                $subscription->cp($this->vars());
                $subscription->save();
                $this->redirect_by_map('index');
            } catch (Exception $e) {
                Exceptions::add($e);
            }
        }
    }
    public function edit_subscription($id) {

    }
    public function delete_subscription($id) {

    }

    /**
     * Planet クロールする
     **/
    static public function __setup_crawl__() {
        foreach (C(PlanetSubscription)->find_all() as $subscription) {
            $feed = Feed::read($subscription->link());
            $subscription->title($feed->title());
            $subscription->link(self::_get_link_href($feed->link()));
            foreach ($feed->entry() as $entry) {
                $planet_entry = new PlanetEntry();
                $planet_entry->subscription_id($subscription->id());
                $planet_entry->title($entry->title());
                $planet_entry->description($entry->content()->value());
                $planet_entry->link(self::_get_link_href($entry->link()));
                $planet_entry->save();
            }
        }
    }
    static private function _get_link_href($array) {
        $link = array_shift($array);
        if ($link instanceof AtomLink) {
            return $link->href();
        } else {
            return $link;
        }
    }
}
