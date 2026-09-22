<?php
/**
 * Database — central PDO access point.
 * Replaces the old classes/conn.php.
 */
require_once __DIR__ . '/../Config/config.php';

class Database
{
    private static ?PDO $instance = null;
    private static bool $attempted = false;

    /**
     * Lazily connects on first real use. Static/error pages (404, 403,
     * 500, offline) never touch $conn, so they keep rendering even if
     * the database is unreachable — unlike the old eager `require
     * 'classes/conn.php'` which died immediately on any page that
     * included it, whether or not that page actually queried anything.
     */
    public static function connection(): ?PDO
    {
        if (self::$instance === null && !self::$attempted) {
            self::$attempted = true;
            try {
                self::$instance = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                    DB_USER,
                    DB_PASS
                );
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                error_log('Database connection failed: ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}

/**
 * Backward-compatible $conn global. Lots of legacy code (now living
 * inside Controllers) references $conn / $pdo directly rather than
 * Database::connection(). This is a lazy proxy: it only opens the
 * real connection the first time a method/property is actually
 * touched on it, so pages that never touch the database (404/403/500/
 * offline, static pages, etc.) keep working even if the database is
 * down. If a page DOES use it and the DB is unreachable, it dies with
 * the same friendly message the old classes/conn.php used to show.
 */
class LazyPDOProxy
{
    public function __call($name, $args)
    {
        $real = Database::connection();
        if ($real === null) {
            die("Error: Failed to connect to database!");
        }
        return $real->$name(...$args);
    }

    public function __get($name)
    {
        $real = Database::connection();
        if ($real === null) {
            die("Error: Failed to connect to database!");
        }
        return $real->$name;
    }
}

$conn = new LazyPDOProxy();
