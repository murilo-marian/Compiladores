<?php
class Token {
    private $token;
    private $lexema;
    private $linha;
    private $posicaoInicial;
    private $posicaoFinal;

    function __construct($token, $lexema, $linha, $posicaoInicial, $posicaoFinal) {
        $this->token = $token;
        $this->lexema = $lexema;
        $this->linha = $linha;
        $this->posicaoInicial = $posicaoInicial;
        $this->posicaoFinal = $posicaoFinal;
    }

    public function getToken() {
        return $this->token;
    }

    public function setToken($value) {
        $this->token = $value;
    }

    public function getLexema() {
        return $this->lexema;
    }

    public function setLexema($value) {
        $this->lexema = $value;
    }

    public function getLinha() {
        return $this->linha;
    }

    public function setLinha($value) {
        $this->linha = $value;
    }

    public function getPosicaoInicial() {
        return $this->posicaoInicial;
    }

    public function setPosicaoInicial($value) {
        $this->posicaoInicial = $value;
    }

    public function getPosicaoFinal() {
        return $this->posicaoFinal;
    }

    public function setPosicaoFinal($value) {
        $this->posicaoFinal = $value;
    }
}
