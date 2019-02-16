<?php declare(strict_types=1);

namespace Pulse\Models\AccountSession;

use DB;
use Pulse\Models\BaseModel;
use Pulse\Utils;

class BrowserAgent implements BaseModel
{
    private $browserAgent;
    private $hash;
    private $id;

    /**
     * BrowserAgent constructor.
     * @param int $id ID of the browser agent
     * @param string $browserAgent BaseBrowser agent text
     * @param string $hash Hashed browser agent
     */
    private function __construct(int $id, string $browserAgent, string $hash)
    {
        $this->id = $id;
        $this->browserAgent = $browserAgent;
        $this->hash = $hash;
    }

    /**
     * @return BrowserAgent
     */
    public static function fromCurrentBrowserAgent(): BrowserAgent
    {
        $browserAgent = Utils::getBrowserAgent();
        $hash = sha1($browserAgent);

        $query = DB::queryFirstRow("SELECT id FROM browser_agents WHERE hash=%s", $hash);
        if ($query == null) {
            // New browser agent
            DB::insert('browser_agents', array(
                    'browser' => $browserAgent,
                    'hash' => $hash)
            );
            $id = (int)DB::insertId();
        } else {
            // Existing browser agent
            $id = (int)$query['id'];
        }

        return new BrowserAgent($id, $browserAgent, $hash);
    }

    public function __toString()
    {
        return $this->browserAgent;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}