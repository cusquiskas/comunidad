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

    private function mbind_apply(): void
    {
        if ($this->types !== "") {
            $this->bind_param($this->types, ...$this->params);
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
    private mysqli $conn;
    private array $lista_errores = [];
    private int $filas_afectadas = 0;

    public function __construct()
    {
        $conf = new ConfiguracionSistema();

        $this->conn = new mysqli(
            $conf->getHost(),
            $conf->getUser(),
            $conf->getPass(),
            $conf->getApli()
        );

        if ($this->conn->connect_errno) {
            throw new Exception("Error de conexión MySQL: " . $this->conn->connect_error);
        }

        // Estado limpio SIEMPRE
        $this->conn->set_charset("utf8mb4");
        $this->conn->autocommit(true);

        unset($conf);
    }

    public function prepare(string $query): mysqli_stmt
    {
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Error preparando consulta: " . $this->conn->error);
        }

        return $stmt;
    }

    public function consulta(string $query, array $params = []): array
    {
        $stmt = $this->prepare($query);

        if (!empty($params)) {
            $this->bindParams($stmt, $params);
        }

        if (!$stmt->execute()) {
            throw new Exception("Error ejecutando consulta: " . $stmt->error);
        }

        $res = $stmt->get_result();
        $data = $res->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $data;
    }

    public function ejecuta(string $query, array $params = []): int
    {
        $stmt = $this->prepare($query);

        if (!empty($params)) {
            $this->bindParams($stmt, $params);
        }

        if (!$stmt->execute()) {
            throw new Exception("Error ejecutando sentencia: " . $stmt->error);
        }

        $this->filas_afectadas = $stmt->affected_rows;
        $stmt->close();

        return $this->filas_afectadas;
    }

    private function bindParams(mysqli_stmt $stmt, array $params): void
    {
        $types = "";
        $values = [];

        foreach ($params as $p) {
            $types .= $p['tipo'];
            $values[] = $p['dato'];
        }

        $stmt->bind_param($types, ...$values);
    }

    public function close(): void
    {
        if ($this->conn) {
            $this->conn->close();
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
