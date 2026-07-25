<?php

class stmt extends mysqli_stmt
{
    private string $types = "";
    private array $params = [];

    public function __construct(mysqli $link, string $query)
    {
        parent::__construct($link, $query);
    }

    public function mbind_reset(): void
    {
        $this->types = "";
        $this->params = [];
    }

    public function mbind_param(string $type, &$param): void
    {
        $this->types .= $type;
        $this->params[] = &$param;
    }

    public function mbind_value(string $type, $param): void
    {
        $this->types .= $type;
        $this->params[] = $param;
    }

    private function makeValuesReferenced(array $arr): array
    {
        $refs = [];
        foreach ($arr as $key => $value) {
            $refs[$key] = &$arr[$key];
        }

        return $refs;
    }

    private function mbind_apply(): void
    {
        if ($this->types !== "") {
            $params = array_merge([$this->types], $this->params);
            $refs = $this->makeValuesReferenced($params);
            call_user_func_array([$this, 'bind_param'], $refs);
        }
    }

    public function execute(): bool
    {
        $this->mbind_apply();
        return parent::execute();
    }
}

class ConexionSistema
{
    private ?mysqli $conn = null;
    private array $lista_errores = [];
    private int $filas_afectadas = 0;
    private bool $transaccion_activa = false;
    private string $Apli = "";

    public function __construct()
    {
        $conf = new ConfiguracionSistema();

        $this->Apli = $conf->getApli();
        $this->conn = new mysqli(
            $conf->getHost(),
            $conf->getUser(),
            $conf->getPass(),
            $conf->getApli()
        );

        if ($this->conn->connect_errno) {
            throw new Exception("Error de conexión MySQL: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4");
        $this->conn->autocommit(true);

        unset($conf);
    }

    public function __destruct()
    {
        $this->close();
    }

    public function get(): mysqli
    {
        return $this->conn;
    }

    public function getApplication(): string
    {
        return $this->Apli;
    }

    public function prepare(string $query): stmt
    {
        if (!$this->conn instanceof mysqli) {
            throw new Exception("La conexión no está disponible");
        }

        $stmt = new stmt($this->conn, $query);
        if (!$stmt) {
            throw new Exception("Error preparando consulta: " . $this->conn->error);
        }

        return $stmt;
    }

    private function registrarErrores($stmt = null): void
    {
        $this->lista_errores = [];

        if ($stmt instanceof mysqli_stmt && $stmt->errno) {
            if (count($stmt->error_list) > 0) {
                $this->lista_errores = $stmt->error_list;
            } else {
                $this->lista_errores = [[
                    'errno' => $stmt->errno,
                    'sqlstate' => $stmt->sqlstate,
                    'error' => $stmt->error,
                ]];
            }
            return;
        }

        if ($this->conn instanceof mysqli && $this->conn->errno) {
            if (count($this->conn->error_list) > 0) {
                $this->lista_errores = $this->conn->error_list;
            } else {
                $this->lista_errores = [[
                    'errno' => $this->conn->errno,
                    'sqlstate' => $this->conn->sqlstate,
                    'error' => $this->conn->error,
                ]];
            }
        }
    }

    private function rollbackTransaccion(): bool
    {
        if (!$this->transaccion_activa) {
            return true;
        }

        if (!$this->conn instanceof mysqli) {
            $this->transaccion_activa = false;
            return false;
        }

        $ok = $this->conn->rollback();
        if ($ok) {
            $this->transaccion_activa = false;
            $this->lista_errores = [];
        }

        return $ok;
    }

    public function begin(int $flags = 0, ?string $name = null): bool
    {
        if (!$this->conn instanceof mysqli) {
            return false;
        }

        if ($this->transaccion_activa) {
            return true;
        }

        $ok = $this->conn->begin_transaction($flags, $name);
        if ($ok) {
            $this->transaccion_activa = true;
            $this->lista_errores = [];
        } else {
            $this->registrarErrores();
        }

        return $ok;
    }

    public function commit(int $flags = 0, ?string $name = null): bool
    {
        if (!$this->transaccion_activa) {
            return true;
        }

        if (!$this->conn instanceof mysqli) {
            return false;
        }

        $ok = $this->conn->commit($flags, $name);
        if ($ok) {
            $this->transaccion_activa = false;
            $this->lista_errores = [];
        } else {
            $this->registrarErrores();
        }

        return $ok;
    }

    public function rollback(int $flags = 0, ?string $name = null): bool
    {
        if (!$this->transaccion_activa) {
            return true;
        }

        if (!$this->conn instanceof mysqli) {
            return false;
        }

        $ok = $this->conn->rollback($flags, $name);
        if ($ok) {
            $this->transaccion_activa = false;
            $this->lista_errores = [];
        } else {
            $this->registrarErrores();
        }

        return $ok;
    }

    public function consulta(string $query, array $params = []): array
    {
        $stmt = $this->prepare($query);

        try {
            foreach ($params as $param) {
                $stmt->mbind_value($param['tipo'], $param['dato']);
            }

            if (!$stmt->execute()) {
                $this->registrarErrores($stmt);
                $this->rollbackTransaccion();
                return [];
            }

            $res = $stmt->get_result();
            if (!$res) {
                $this->registrarErrores($stmt);
                $this->rollbackTransaccion();
                return [];
            }

            $data = $res->fetch_all(MYSQLI_ASSOC);
            return $data;
        } finally {
            if ($stmt instanceof mysqli_stmt) {
                $stmt->close();
            }
        }
    }

    public function ejecuta(string $query, array $params = []): int
    {
        $stmt = $this->prepare($query);

        try {
            foreach ($params as $param) {
                $stmt->mbind_value($param['tipo'], $param['dato']);
            }

            if (!$stmt->execute()) {
                $this->registrarErrores($stmt);
                $this->rollbackTransaccion();
                return 0;
            }

            $this->filas_afectadas = $stmt->affected_rows;
            if ($stmt->errno) {
                $this->registrarErrores($stmt);
                $this->rollbackTransaccion();
                return 0;
            }

            return $this->filas_afectadas;
        } finally {
            if ($stmt instanceof mysqli_stmt) {
                $stmt->close();
            }
        }
    }

    public function close(): void
    {
        if ($this->transaccion_activa) {
            $this->rollbackTransaccion();
        }

        if ($this->conn instanceof mysqli) {
            $this->conn->close();
            $this->conn = null;
        }
    }

    public function filasAfectadas(): int
    {
        return $this->filas_afectadas;
    }

    public function hayError(): bool
    {
        return count($this->lista_errores) > 0;
    }

    public function getListaErrores(): array
    {
        return $this->lista_errores;
    }
}
