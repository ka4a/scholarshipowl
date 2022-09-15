<?php namespace Test\Services;

use App\Services\CmsService;
use App\Testing\TestCase;
use Illuminate\Http\Request;
use Mockery as m;

class CmsServiceTest extends TestCase
{

    /**
     * @var CmsService
     */
    protected $cms;

    public function setUp(): void
    {
        parent::setUp();

        $this->cms = app(CmsService::class);
    }

    public function tearDown(): void
    {
        $this->cms->clear();
        parent::tearDown();
    }

    public function testCmsFacade()
    {
        $this->generateCms('/test');


        $this->get('/?test#test');
        $this->assertEquals(CmsService::DEFAULT_TITLE, \CMS::title());
        $this->assertEquals(CmsService::DEFAULT_DESCRIPTION, \CMS::description());
        $this->assertEquals(CmsService::DEFAULT_KEYWORDS, \CMS::keywords());
        $this->assertEquals(CmsService::DEFAULT_AUTHOR, \CMS::author());

        $this->get('/test?fadfasd#fdasfasdf');
        $this->assertEquals('test cms title', \CMS::title());
        $this->assertEquals('test cms description', \CMS::description());
        $this->assertEquals('test cms keywords', \CMS::keywords());
        $this->assertEquals('test cms author', \CMS::author());
    }

    public function testCmsEntitiesFind()
    {
        $this->generateCms('/');
        $this->generateCms('/test');

        $request = m::mock(Request::class)->shouldReceive('path')->once()->andReturn('/')->getMock();
        $this->assertNotNull($this->cms->entity($request));
        $this->cms->clear();

        $request = m::mock(Request::class)->shouldReceive('path')->once()->andReturn('/test')->getMock();
        $this->assertNotNull($this->cms->entity($request));
        $this->cms->clear();

        $request = m::mock(Request::class)->shouldReceive('path')->once()->andReturn('/testing')->getMock();
        $this->assertNull($this->cms->entity($request));
        $this->cms->clear();
    }
}
