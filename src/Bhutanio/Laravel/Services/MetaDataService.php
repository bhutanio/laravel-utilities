<?php

namespace Bhutanio\Laravel\Services;

class MetaDataService
{
    protected $site_name, $meta_title, $page_title, $description, $canonical, $icon, $theme, $color;

    public function __construct()
    {
        $this->meta_title = $this->getDefaultTitle();
    }

    public function setMeta($page_title = null, $meta_title = null, $description = null, $icon = null)
    {
        $this->pageTitle($page_title);
        $this->metaTitle($meta_title);
        if (empty($meta_title)) {
            if ($page = request()->get('page')) {
                if ($page > 1) {
                    $page_title .= ' (Page ' . $page . ')';
                }
            }

            $this->metaTitle($page_title . ' - ' . $this->meta_title);
        }
        $this->description($description);
        $this->icon($icon);
    }

    public function pageTitle($title = null)
    {
        if ($title) {
            $this->page_title = $title;
        }

        return $this->page_title;
    }

    public function metaTitle($title = null)
    {
        if ($title) {
            $this->meta_title = $title;
        }

        return $this->meta_title;
    }

    public function description($description = null)
    {
        if ($description) {
            $this->description = $description;
        }

        return $this->description;
    }

    public function canonical($url = null)
    {
        if ($url) {
            $this->canonical = $url;
        }

        return $this->canonical;
    }

    public function icon($icon = null)
    {
        if ($icon) {
            $this->icon = $icon;
        }

        return $this->icon;
    }

    public function theme($theme = null)
    {
        if ($theme) {
            $this->theme = $theme;
        }

        return $this->theme;
    }

    public function color($color = null)
    {
        if ($color) {
            $this->color = $color;
        }

        return $this->color;
    }

    private function getDefaultTitle()
    {
        $this->site_name = env('SITE_NAME') ?: env('APP_NAME');

        return $this->site_name ?: 'Site Name';
    }
}
