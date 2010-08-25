<?php
import('org.rhaco.net.xml.Feed');
module('model.PlanetEntry');
module('model.PlanetSubscription');

class Planet extends Flow
{
    protected function __init__() {
        $this->vars('sitename', module_const('sitename', 'Planet'));
        $this->vars('subscriptions', C(PlanetSubscription)->find_all());
    }

    public function index() {
        $paginator = new Paginator(10, $this->in_vars('page', 1));
        if ($this->is_vars('q')) $paginator->cp(array('q' => $this->in_vars('q')));
        $this->vars('entries', C(PlanetEntry)->find_page($this->in_vars('q'), $paginator, '-updated'));
        $this->vars('paginator', $paginator);
    }

    public function add_subscription() {
        if ($this->is_post()) {
            try {
                $subscription = new PlanetSubscription();
                $subscription->cp($this->vars());
                $subscription->save();
                $this->redirect_method('index');
            } catch (Exception $e) {
                Exceptions::add($e);
            }
        }
    }
    public function edit_subscription($id) {

    }
    public function delete_subscription($id) {

    }
    public function atom() {
        Atom::convert(module_const('sitename', 'Planet'), url(), C(PlanetEntry)->find_all(new Paginator(20), Q::order('-updated')))->output();
    }

    /**
     * Planet クロールする
     **/
    static public function __setup_crawl__() {
        $http_feed = new Feed();
        foreach (C(PlanetSubscription)->find_all() as $subscription) {
            Exceptions::clear();
            Log::debug(sprintf('[crawl] feed: %d (%s)', $subscription->id(), $subscription->title()));
            $feed = $http_feed->do_read($subscription->rss_url());
            try {
                $subscription->title($feed->title());
                if ($feed->is_link()) $subscription->link(self::_get_link_href($feed->link()));
                $subscription->rss_url($http_feed->url());
                $subscription->save(true);
            } catch (Exception $e) {
                Log::error($e);
            }
            foreach ($feed->entry() as $entry) {
                Exceptions::clear();
                try {
                    $planet_entry = new PlanetEntry();
                    $planet_entry->subscription_id($subscription->id());
                    $planet_entry->title($entry->title());
                    $planet_entry->description(Text::htmldecode(Tag::cdata($entry->fm_content())));
                    $planet_entry->link($entry->first_href());
                    $planet_entry->updated($entry->published());
                    $planet_entry->save();
                } catch (Exception $e) {
                    Log::warn($e->getMessage());
                }
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
