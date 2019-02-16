<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Pulse\Database;
use Pulse\Models\AccountSession\BrowserAgent;

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
        $agent = BrowserAgent::fromCurrentBrowserAgent();
        $this->assertInstanceOf(BrowserAgent::class, $agent);
        return $agent;
    }


    /**
     * @depends testCreateBrowserAgent
     * @param BrowserAgent $agent
     */
    public function testDatabaseEntries(BrowserAgent $agent)
    {
        $hash = sha1((string)$agent);
        $query = DB::query("SELECT * FROM browser_agents WHERE browser=%s AND hash=%s", $agent, $hash);
        $this->assertNotNull($query);
        $this->assertCount(1, $query);
    }
}
