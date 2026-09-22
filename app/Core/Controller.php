<?php
/**
 * Base Controller — every Controller in app/Controllers extends this.
 * Provides small helpers for rendering Views and JSON responses.
 * $conn, $bmis, $residentbmis, $staffbmis, $userdetails are made
 * available to controller methods as instance properties (mirroring
 * the globals the legacy code was originally written against). Each
 * generated controller method re-hydrates these into local scope at
 * its very top (see app/Controllers/*.php) since the moved page logic
 * references them as bare variables, exactly as it did when it was a
 * top-level procedural script.
 */
abstract class Controller
{
    protected $conn; // LazyPDOProxy (behaves like PDO, connects on first real use)
    protected BMISClass $bmis;
    protected ResidentClass $residentbmis;
    protected StaffClass $staffbmis;
    protected ?array $userdetails;

    public function __construct()
    {
        global $conn, $bmis, $residentbmis, $staffbmis, $userdetails;
        $this->conn         = $conn;
        $this->bmis         = $bmis;
        $this->residentbmis = $residentbmis;
        $this->staffbmis    = $staffbmis;
        $this->userdetails  = $userdetails ?? null;
    }

    /**
     * Render a View file. Because the legacy pages relied on plain
     * variable scope (not an explicit data array) between the "logic"
     * portion and the "HTML" portion of each page, we extract() the
     * given data into local scope before including the view so any
     * variable set in the controller method is visible to it exactly
     * as before. The view is require()'d from inside the calling
     * method, so it also inherits that method's local scope directly
     * (PHP include/require share the caller's scope).
     */
    protected function view(string $viewPath, array $data = []): void
    {
        extract($data);
        require APP_PATH . '/Views/' . $viewPath;
    }

    protected function json($data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
