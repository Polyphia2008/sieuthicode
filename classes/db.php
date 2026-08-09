<?php
// statically decompiled from db.php  [structured; all 15 record(s) structured]

include_once __DIR__ . '/../vendor/autoload.php';
date_default_timezone_set('Asia/Ho_Chi_Minh');

class DB
{
    private $connect;

    public function connect()
    {
        if (!$this->connect) {
            if (!$this->connect) {
                $this->connect = mysqli_connect('localhost', username, password, database) or exit('Error => DATABASE');
                mysqli_query($this->connect, 'set names \'utf8mb4\'');
            }
        }
    }

    public function get_id_insert()
    {
        $this->connect();
        return mysqli_insert_id($this->connect);
    }

    public function dis_connect()
    {
        if ($this->connect) {
            mysqli_close($this->connect);
        }
    }

    public function query($sql)
    {
        $this->connect();
        $row = $this->connect->query($sql);
        return $row;
    }

    public function insert($table, $data)
    {
        $this->connect();
        $field_list = '';
        $value_list = '';
        foreach ($data as $__key => $value) {
            $key = $__key;
            $field_list .= ',' . $key;
            $value_list .= ',\'' . mysqli_real_escape_string($this->connect, $value) . '\'';
        }
        $sql = 'INSERT INTO ' . $table . '(' . trim($field_list, ',') . ') VALUES (' . trim($value_list, ',') . ')';
        return mysqli_query($this->connect, $sql);
    }

    public function update($table, $data, $where)
    {
        $this->connect();
        $sql = '';
        foreach ($data as $__key => $value) {
            $key = $__key;
            if ($value === null) {
                $escapedValue = 'NULL';
            } else {
                $escapedValue = '\'' . mysqli_real_escape_string($this->connect, $value) . '\'';
            }
            $sql .= $key . ' = ' . $escapedValue . ',';
        }
        $sql = 'UPDATE ' . $table . ' SET ' . rtrim($sql, ',') . ' WHERE ' . $where;
        return mysqli_query($this->connect, $sql);
    }

    public function update_value($table, $data, $where, $value1)
    {
        $this->connect();
        $sql = '';
        foreach ($data as $__key => $value) {
            $key = $__key;
            $sql .= $key . ' = \'' . mysqli_real_escape_string($this->connect, $value) . '\',';
        }
        $sql = 'UPDATE ' . $table . ' SET ' . trim($sql, ',') . ' WHERE ' . $where . ' LIMIT ' . $value1;
        return mysqli_query($this->connect, $sql);
    }

    public function cong($table, $data, $sotien, $where)
    {
        $this->connect();
        $row = $this->connect->query('UPDATE `' . $table . '` SET `' . $data . '` = `' . $data . '` + \'' . $sotien . '\' WHERE ' . $where . ' ');
        return $row;
    }

    public function tru($table, $data, $sotien, $where)
    {
        $this->connect();
        $row = $this->connect->query('UPDATE `' . $table . '` SET `' . $data . '` = `' . $data . '` - \'' . $sotien . '\' WHERE ' . $where . ' ');
        return $row;
    }

    public function site($data)
    {
        $this->connect();
        $row = $this->connect->query('SELECT * FROM `options` WHERE `key` = \'' . $data . '\' ')->fetch_array();
        return $row['value'];
    }

    public function remove($table, $where)
    {
        $this->connect();
        $sql = 'DELETE FROM ' . $table . ' WHERE ' . $where;
        return mysqli_query($this->connect, $sql);
    }

    public function get_list($sql)
    {
        $this->connect();
        $result = mysqli_query($this->connect, $sql);
        if (!$result) {
            exit('Câu truy vấn bị sai');
        } else {
            $return = [];
            while (true) {
                $row = mysqli_fetch_assoc($result);
                if (!($row)) { break; }
                $return[] = $row;
            }
            mysqli_free_result($result);
            return $return;
        }
    }

    public function get_row($sql)
    {
        $this->connect();
        $result = mysqli_query($this->connect, $sql);
        if (!$result) {
            exit('Câu truy vấn bị sai');
        } else {
            $row = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
            if ($row) {
                return $row;
            } else {
                return false;
            }
        }
    }

    public function num_rows($sql)
    {
        $this->connect();
        $result = mysqli_query($this->connect, $sql);
        if (!$result) {
            exit('Câu truy vấn bị sai');
        } else {
            $row = mysqli_num_rows($result);
            mysqli_free_result($result);
            if ($row) {
                return $row;
            } else {
                return false;
            }
        }
    }
}
