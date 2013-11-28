<?php
/**
 * Tinyark Framework
 *
 * @copyright Copyright 2012-2013, Dong <ddliuhb@gmail.com>
 * @link http://maxmars.net/projects/tinyark Tinyark project
 * @license MIT License (http://maxmars.net/license/MIT)
 */

class HttpClientTest extends PHPUnit_Framework_TestCase{
    public function testNormalizeUrl()
    {
        $url = ArkHttpClient::normalizeUrl('http://a.com', '/helloworld/a/b/..');
        $this->assertEquals($url, 'http://a.com/helloworld/a');

        $url = ArkHttpClient::normalizeUrl('http://a.com/cd/index.html', 'another.html');
        $this->assertEquals($url, 'http://a.com/cd/another.html');

        $url = ArkHttpClient::normalizeUrl('http://a.com/cd/index.html', '../../../another.html');
        $this->assertEquals($url, 'http://a.com/another.html');

        $url = ArkHttpClient::normalizeUrl('https://a.com/cd/index.html', '//another.html');
        $this->assertEquals($url, 'https://a.com/another.html');

        $url = ArkHttpClient::normalizeUrl('http://a.com/cd/index.html', 'http://b.com/ef/index.html');
        $this->assertEquals($url, 'http://b.com/ef/index.html');
    }

    public function testRequest()
    {
        $client = new ArkHttpClient();
        $response = $client->get('http://php.net');
        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertEquals($response->getInfo('url'), 'http://php.net');
        $this->assertRegExp('/\>downloads\<\/a\>/i', $response->getContent());

        $response = $client->get('http://www.yahoo.com/notexist');
        $this->assertEquals($response->getStatusCode(), 404);

        $this->assertEquals($response->hasError(), true);

        $this->assertEquals($response->hasError(array(200, 404)), false);

        $response = $client->get('http://a.com');
        $this->assertEquals($response->hasError(), true);

        $ua = 'Tinyark HTTP Client';
        $response = $client->get('http://whatsmyuseragent.com', null, array(
            'User-Agent' => $ua,
        ));
        $this->assertContains($ua, $response->getContent());
    }

}