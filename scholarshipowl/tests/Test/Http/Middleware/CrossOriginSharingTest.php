<?php namespace Test\Http\Middleware;

use Mockery as m;
use App\Testing\TestCase;

use App\Http\Middleware\CrossOriginSharing;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CrossOriginSharingTest extends TestCase
{

    public function testShouldNotFailOnRedirectResponse()
    {
        $redirectResponse = new RedirectResponse('http://test.com');

        $middleware = m::mock(CrossOriginSharing::class)->shouldAllowMockingProtectedMethods()->makePartial();
        $middleware->allowCORS($redirectResponse);
    }

    public function testCrossOriginSharingDeny()
    {
        $this->expectException(NotFoundHttpException::class);

        $request = m::mock(Request::class)->makePartial();
        $request->headers = m::mock(HeaderBag::class)
            ->shouldReceive('get')
            ->once()
            ->with('referer')
            ->andReturn('http://test.com')
            ->getMock();

        $middleware = new CrossOriginSharing();
        $middleware->handle($request, function($response){}, 'opaopaopa');
    }

    public function testCrossOriginAllowAllHeaders()
    {
        $request = m::mock(Request::class)->makePartial();
        $request->shouldReceive('testClosure')->twice();
        $request->headers = m::mock(HeaderBag::class)
            ->shouldReceive('get')
            ->twice()
            ->with('referer')
            ->andReturn('http://test.com')
            ->getMock();

        $response = m::mock(Response::class)->makePartial();
        $response->headers = m::mock(HeaderBag::class)
            ->shouldReceive('set')->once()->with('Access-Control-Allow-Origin', 'http://test.com')->getMock()
            ->shouldReceive('set')->once()->with('Access-Control-Allow-Credentials', 'true')->getMock()
            ->shouldReceive('set')->once()->with('Access-Control-Allow-Methods', 'PUT, GET, POST, DELETE, OPTIONS')->getMock()
            ->shouldReceive('set')->once()->with('Access-Control-Allow-Headers', 'X-CSRF-Token, origin, x-requested-with, content-type, X-PINGOTHER')->getMock();

        $middleware = new CrossOriginSharing();
        $middleware->handle($request, function($request) use ($response) {
            $request->testClosure();
            return $response;
        }, 'test.com');

        $response = m::mock(Response::class)->makePartial();
        $response->headers = m::mock(HeaderBag::class)
            ->shouldReceive('set')->once()->with('Access-Control-Allow-Origin', 'http://test.com')->getMock()
            ->shouldReceive('set')->once()->with('Access-Control-Allow-Credentials', 'true')->getMock()
            ->shouldReceive('set')->once()->with('Access-Control-Allow-Methods', 'PUT, GET, POST, DELETE, OPTIONS')->getMock()
            ->shouldReceive('set')->once()->with('Access-Control-Allow-Headers', 'X-CSRF-Token, origin, x-requested-with, content-type, X-PINGOTHER')->getMock();

        $middleware->handle($request, function($request) use ($response) {
            $request->testClosure();
            return $response;
        }, 'example.com|test.com');
    }
}
