<?php

namespace Bhutanio\Laravel\Services;

use Illuminate\Http\Request;

class MetaDataService
{
    protected $meta_title, $page_title, $description, $canonical, $icon, $theme, $color;

    public function __construct(Request $request)
    {
        $this->meta_title = $this->getDefaultTitle();
        $this->setDefaultMeta($request);
    }

    public function setMeta($page_title = null, $meta_title = null, $description = null, $icon = null)
    {
        $this->pageTitle($page_title);
        $this->metaTitle($meta_title);
        if (empty($meta_title)) {
            $this->metaTitle($page_title . ' - ' . $this->meta_title);
        }
        $this->description($description);
        $this->icon($icon);
    }

    public function setTheme($theme = null, $color = null)
    {
    }

    public function metaTitle($title = null)
    {
        if ($title) {
            $this->meta_title = $title;
        }

        return $this->meta_title;
    }

    public function pageTitle($title = null)
    {
        if ($title) {
            $this->page_title = $title;
        }

        return $this->page_title;
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

    /**
     * @param Request $request
     */
    private function setDefaultMeta(Request $request)
    {
        switch ($request->getRequestUri()) {
            case '/auth/login':
                $this->setMeta('Login');
                break;
            case '/auth/register':
                $this->setMeta('Register');
                break;
        }
    }

    /**
     * @return mixed
     */
    private function getDefaultTitle()
    {
        return env('SITE_NAME') ?: 'Site Name';
    }
}
