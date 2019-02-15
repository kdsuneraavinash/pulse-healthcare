<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Pulse\Database;
use Pulse\Models\User\BrowserUserAgent;

final class BrowserUserAgentTest extends TestCase
{
    /**
     * @beforeClass
     */
    public static function setSharedVariables()
    {
        Database::init();
    }

    /**
     */
    public function testCreateBrowserAgent()
    {
        $agent = BrowserUserAgent::fromCurrentUserAgent();
        $this->assertInstanceOf(BrowserUserAgent::class, $agent);
        return $agent;
    }


    /**
     * @depends testCreateBrowserAgent
     * @param BrowserUserAgent $agent
     */
    public function testDatabaseEntries(BrowserUserAgent $agent)
    {
        $hash = sha1((string)$agent);
        $query = DB::query("SELECT * FROM user_agents WHERE user_agent=%s AND hash=%s", $agent, $hash);
        $this->assertNotNull($query);
        $this->assertCount(1, $query);
    }
}
