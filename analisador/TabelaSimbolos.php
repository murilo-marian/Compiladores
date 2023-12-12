<?php
class TabelaSimbolos {
    private $variable = array();

    // Insere um símbolo na tabela do escopo atual
    public function insert($name, $type) {
        if (!$this->exists($name)) {
            $this->variable[$name] = $type;
            return true; // Inserção bem-sucedida
        }
        return false; // Símbolo já existe no escopo
    }

    // Verifica se um símbolo existe na tabela do escopo atual
    public function exists($name) {
        return array_key_exists($name, $this->variable);
    }

    public function __getVariavel() {
        return $this->variable;
}
}