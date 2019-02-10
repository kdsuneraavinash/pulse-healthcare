<?php declare(strict_types=1);

namespace Pulse\Framework;

use DB;
use Pulse\Utils;

class UserAgent
{
    private $userAgent;
    private $hash;
    private $id;

    /**
     * UserAgent constructor.
     * @param int $id ID of the user agent
     * @param string $userAgent User agent text
     * @param string $hash Hashed user agent
     */
    private function __construct(int $id, string $userAgent, string $hash)
    {
        $this->id = $id;
        $this->userAgent = $userAgent;
        $this->hash = $hash;
    }

    public static function fromCurrentUserAgent(): UserAgent
    {
        $userAgent = Utils::getUserAgent();
        $hash = sha1($userAgent);

        $query = DB::queryFirstRow("SELECT id FROM user_agents WHERE hash=UNHEX(%s)", $hash);
        if ($query == null) {
            // New user agent
            DB::insert('user_agents', array(
                'user_agent' => $userAgent,
                'hash' => DB::sqleval("UNHEX('$hash')")
            ));
            $id = (int)DB::insertId();
        } else {
            // Existing user agent
            $id = (int)$query['id'];
        }

        return new UserAgent($id, $userAgent, $hash);
    }

    /**
     * @return int User Agent ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->userAgent;
    }
}