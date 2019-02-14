<?php declare(strict_types=1);

namespace Pulse\Models\User;

use DB;
use Pulse\Models\BaseModel;
use Pulse\Utils;

class BrowserUserAgent implements BaseModel
{
    private $userAgent;
    private $hash;
    private $id;

    /**
     * BrowserUserAgent constructor.
     * @param int $id ID of the user agent
     * @param string $userAgent BaseUser agent text
     * @param string $hash Hashed user agent
     */
    private function __construct(int $id, string $userAgent, string $hash)
    {
        $this->id = $id;
        $this->userAgent = $userAgent;
        $this->hash = $hash;
    }

    /**
     * @return BrowserUserAgent
     */
    public static function fromCurrentUserAgent(): BrowserUserAgent
    {
        $userAgent = Utils::getUserAgent();
        $hash = sha1($userAgent);

        $query = DB::queryFirstRow("SELECT id FROM user_agents WHERE hash=%s", $hash);
        if ($query == null) {
            // New user agent
            DB::insert('user_agents', array(
                    'user_agent' => $userAgent,
                    'hash' => $hash)
            );
            $id = (int)DB::insertId();
        } else {
            // Existing user agent
            $id = (int)$query['id'];
        }

        return new BrowserUserAgent($id, $userAgent, $hash);
    }

    public function __toString()
    {
        return $this->userAgent;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}