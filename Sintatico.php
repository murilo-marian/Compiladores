<?php

class Sintatico {

    private $cont = 0;
    private $lexico;

    public function __construct($listaTokens) {
        $this->lexico = $listaTokens;
    }

    /*  { "/[s]/": "SWITCH" },
        { "/[w]/": "WHILE" },
        { "/[d]/": "DO" },
        { "/[p]/": "PRINT" },
        { "/[i]/": "IF" },*/

    function term($tk) {
        //comparar o esperado {parametro tk} com o próximo token da entrada
        return $tk == $this->lexico[$this->cont++]->getToken();
    }

    function PROGRAMA() {
        return $this->LISTA_COMANDOS();
    }

    function LISTA_COMANDOS() {
        return $this->COMANDO() && $this->LISTA_COMANDOS() || $this->LISTA_COMANDOS2();
    }

    function LISTA_COMANDOS2() {
        return true;
    }

    function COMANDO() {
        return $this->IF() || $this->WHILE() || $this->FOR() || $this->FOREACH() || $this->SWITCH() || $this->DO() || $this->PRINT();
    }


    public function LISTA_VAR() {
        return $this->LISTA_VAR1() || $this->LISTA_VAR2();
    }

    public function LISTA_VAR1() {
        return $this->VAR() && $this->term('virgula') && $this->LISTA_VAR();
    }

    public function LISTA_VAR2() {
        return true; //vazio sempre true
    }

    public function VAR() {
        return $this->TIPO() && $this->term('id');
    }
}
