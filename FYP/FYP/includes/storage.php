<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

const DB_HOST = '127.0.0.1';
const DB_NAME = 'blood_bridge';
const DB_USER = 'root';
const DB_PASS = '';

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}

function allowed_tables(): array
{
    return [
        'donors',
        'health_records',
        'profile_updates',
        'documents',
        'appointments',
        'inventory_updates',
        'appointment_decisions',
        'announcements',
        'donation_records',
        'matching_alerts',
        'appointment_slots',
    ];
}

function assert_table(string $name): void
{
    if (!in_array($name, allowed_tables(), true)) {
        throw new InvalidArgumentException('Invalid table name.');
    }
}

function table_columns(string $name): array
{
    assert_table($name);

    static $columns = [];
    if (isset($columns[$name])) {
        return $columns[$name];
    }

    $statement = db()->query('DESCRIBE `' . $name . '`');
    $columns[$name] = array_column($statement->fetchAll(), 'Field');

    return $columns[$name];
}

function normalize_db_value(mixed $value): mixed
{
    if (is_array($value)) {
        return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    if (is_string($value)) {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $value) === 1) {
            return str_replace('T', ' ', $value) . ':00';
        }
    }

    return $value;
}

function decode_record(array $record): array
{
    if (isset($record['answers']) && is_string($record['answers'])) {
        $answers = json_decode($record['answers'], true);
        $record['answers'] = is_array($answers) ? $answers : [];
    }

    return $record;
}

function read_records(string $name, array $default = []): array
{
    assert_table($name);

    try {
        $statement = db()->query('SELECT * FROM `' . $name . '` ORDER BY created_at DESC, id DESC');
        $records = array_map('decode_record', $statement->fetchAll());
    } catch (PDOException) {
        return $default;
    }

    return $records;
}

function write_records(string $name, array $records): void
{
    assert_table($name);
    db()->exec('TRUNCATE TABLE `' . $name . '`');

    foreach ($records as $record) {
        append_record($name, $record);
    }
}

function append_record(string $name, array $record): array
{
    assert_table($name);

    $columns = array_values(array_intersect(table_columns($name), array_keys($record)));
    $columns = array_values(array_filter($columns, static fn (string $column): bool => $column !== 'id' && $column !== 'created_at'));

    if (!$columns) {
        throw new InvalidArgumentException('No valid columns were provided.');
    }

    $placeholders = array_map(static fn (string $column): string => ':' . $column, $columns);
    $sql = 'INSERT INTO `' . $name . '` (`' . implode('`, `', $columns) . '`) VALUES (' . implode(', ', $placeholders) . ')';
    $statement = db()->prepare($sql);

    foreach ($columns as $column) {
        $statement->bindValue(':' . $column, normalize_db_value($record[$column] ?? null));
    }

    $statement->execute();

    $record['id'] = (int)db()->lastInsertId();
    $record['created_at'] = date('Y-m-d H:i:s');

    return $record;
}

function post_value(string $key, string $default = ''): string
{
    return trim((string)($_POST[$key] ?? $default));
}

function flash(string $title, string $message, string $type = 'ok'): void
{
    $_SESSION['flash'] = [
        'title' => $title,
        'message' => $message,
        'type' => $type,
    ];
}

function redirect_to(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function flash_markup(): string
{
    if (empty($_SESSION['flash'])) {
        return '';
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    $title = htmlspecialchars((string)$flash['title'], ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars((string)$flash['message'], ENT_QUOTES, 'UTF-8');
    $type = htmlspecialchars((string)$flash['type'], ENT_QUOTES, 'UTF-8');

    return '<div class="server-alert ' . $type . '"><strong>' . $title . '</strong><span>' . $message . '</span></div>';
}

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function delete_record(string $name, int $id): void
{
    assert_table($name);

    $statement = db()->prepare('DELETE FROM `' . $name . '` WHERE id = :id');
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
}
