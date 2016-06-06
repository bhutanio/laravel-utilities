<?php

use Bhutanio\Laravel\Services\MetaDataService;
use Illuminate\Http\Request;

class MetaDataServiceTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_sets_page_titles()
    {
        $request = new Request;
        $meta = new MetaDataService($request);

        $meta->setMeta('Foo');
        $this->assertEquals('Foo - Site Name', $meta->metaTitle());
        $this->assertEquals('Foo', $meta->pageTitle());
    }

    /** @test */
    public function it_sets_page_numbers_in_the_title()
    {
        $request = new Request();
        $request->replace(['page' => '2']);

        $meta = new MetaDataService($request);

        $meta->setMeta('Bar');
        $this->assertEquals('Bar (Page 2) - Site Name', $meta->metaTitle());
    }
}