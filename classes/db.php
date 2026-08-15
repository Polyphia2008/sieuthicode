<?php

require_once __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('Asia/Ho_Chi_Minh');

class DB
{
    private $connect = null;

    public function connect()
    {
        if ($this->connect instanceof mysqli) {
            return $this->connect;
        }

        if (!extension_loaded('mysqli')) {
            http_response_code(500);
            exit('Hosting chưa bật PHP extension mysqli. Vui lòng bật mysqli trong cPanel.');
        }

        // PHP 8.1 enables strict mysqli reporting by default. Disable exceptions
        // here so shared hosting receives a useful deployment message instead of
        // an uncaught mysqli_sql_exception/blank HTTP 500 page.
        mysqli_report(MYSQLI_REPORT_OFF);
        $this->connect = mysqli_connect(
            DB_HOST,
            DB_USERNAME,
            DB_PASSWORD,
            DB_DATABASE,
            DB_PORT
        );

        if (!$this->connect) {
            error_log('Database connection failed: ' . mysqli_connect_error());
            http_response_code(500);
            exit('Không thể kết nối cơ sở dữ liệu. Hãy kiểm tra config.php và quyền MySQL trong cPanel.');
        }

        mysqli_set_charset($this->connect, 'utf8mb4');
        return $this->connect;
    }

    public function escape($value)
    {
        $this->connect();
        return mysqli_real_escape_string($this->connect, (string) $value);
    }

    public function get_id_insert()
    {
        $this->connect();
        return mysqli_insert_id($this->connect);
    }

    /**
     * Số hàng bị ảnh hưởng bởi câu INSERT/UPDATE/DELETE gần nhất trên kết nối
     * hiện tại. Dùng cho so-sánh-và-hoán-đổi (compare-and-swap) nguyên tử —
     * ví dụ rotation token phải kiểm tra affected_rows === 1 để fail-closed
     * khi token cũ đã bị một request khác đổi trước (race condition).
     * Trả về -1 nếu truy vấn trước đó lỗi.
     */
    public function affected_rows()
    {
        $this->connect();
        return mysqli_affected_rows($this->connect);
    }

    public function dis_connect()
    {
        if ($this->connect instanceof mysqli) {
            mysqli_close($this->connect);
            $this->connect = null;
        }
    }

    public function query($sql)
    {
        $this->connect();
        $result = mysqli_query($this->connect, $sql);
        if ($result === false) {
            $this->logQueryError($sql);
        }
        return $result;
    }

    public function insert($table, $data)
    {
        $this->connect();
        $fields = [];
        $values = [];
        foreach ($data as $key => $value) {
            $fields[] = $key;
            $values[] = $value === null
                ? 'NULL'
                : "'" . mysqli_real_escape_string($this->connect, (string) $value) . "'";
        }

        $sql = 'INSERT INTO ' . $table . '(' . implode(',', $fields) . ') VALUES (' . implode(',', $values) . ')';
        return $this->query($sql);
    }

    public function update($table, $data, $where)
    {
        $this->connect();
        $assignments = [];
        foreach ($data as $key => $value) {
            $escapedValue = $value === null
                ? 'NULL'
                : "'" . mysqli_real_escape_string($this->connect, (string) $value) . "'";
            $assignments[] = $key . ' = ' . $escapedValue;
        }

        $sql = 'UPDATE ' . $table . ' SET ' . implode(',', $assignments) . ' WHERE ' . $where;
        return $this->query($sql);
    }

    public function update_value($table, $data, $where, $limit)
    {
        $this->connect();
        $assignments = [];
        foreach ($data as $key => $value) {
            $assignments[] = $key . " = '" . mysqli_real_escape_string($this->connect, (string) $value) . "'";
        }

        $sql = 'UPDATE ' . $table . ' SET ' . implode(',', $assignments) . ' WHERE ' . $where . ' LIMIT ' . (int) $limit;
        return $this->query($sql);
    }

    public function cong($table, $data, $sotien, $where)
    {
        return $this->query('UPDATE `' . $table . '` SET `' . $data . '` = `' . $data . '` + \'' . $sotien . '\' WHERE ' . $where);
    }

    public function tru($table, $data, $sotien, $where)
    {
        return $this->query('UPDATE `' . $table . '` SET `' . $data . '` = `' . $data . '` - \'' . $sotien . '\' WHERE ' . $where);
    }

    public function site($key)
    {
        $this->connect();
        $escapedKey = mysqli_real_escape_string($this->connect, (string) $key);
        $row = $this->get_row("SELECT `value` FROM `options` WHERE `key` = '{$escapedKey}' LIMIT 1");
        return $row ? $row['value'] : null;
    }

    public function remove($table, $where)
    {
        return $this->query('DELETE FROM ' . $table . ' WHERE ' . $where);
    }

    public function get_list($sql)
    {
        $this->connect();
        $result = mysqli_query($this->connect, $sql);
        if ($result === false) {
            $this->logQueryError($sql);
            return [];
        }

        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        mysqli_free_result($result);
        return $rows;
    }

    public function get_row($sql)
    {
        $this->connect();
        $result = mysqli_query($this->connect, $sql);
        if ($result === false) {
            $this->logQueryError($sql);
            return false;
        }

        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        return $row ?: false;
    }

    public function num_rows($sql)
    {
        $this->connect();
        $result = mysqli_query($this->connect, $sql);
        if ($result === false) {
            $this->logQueryError($sql);
            return 0;
        }

        $count = mysqli_num_rows($result);
        mysqli_free_result($result);
        return $count;
    }

    private function logQueryError($sql)
    {
        error_log('MySQL error: ' . mysqli_error($this->connect) . ' | Query: ' . $sql);
    }
}
