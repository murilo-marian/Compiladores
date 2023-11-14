<?php

class Lexico {
    private $transicoes;
    public function __construct() {
        $teste = file_get_contents("./teste.json", "teste.json");

        $this->transicoes = $teste = json_decode($teste, true);
    }

    public function lex($cadeia) {
        include "Token.php";

        $cadeia .= '$';
        $estado = "INICIO";
        $match = false;
        $cadeiaSplit = mb_str_split($cadeia);
        $tokens = "";
        $posicao = 0;
        $posInicial = 0;
        $posFinal = 0;
        $linha = 0;
        $arrayTokens;

        foreach ($cadeiaSplit as $lexema) {
            $posicao++; //contador da posicao do caractere

            if ($lexema == "\n") { //contador de linhas
                $linha++;
            }

            while (!$match) {
                if ($estado == "INICIO") {
                    $posInicial = $posicao;
                }

                foreach ($this->transicoes[$estado] as $padrao) {
                    if (preg_match(key($padrao), $lexema)) {
                        $tokens .= $lexema;
                        $estado = $padrao[key($padrao)];
                        echo "MUDANCA DE ESTADO: " . $estado . "<br />";
                        $match = true;
                        break;
                    }
                }
                if (!$match) {
                    if ($estado == "INICIO") {
                        echo "ERRO <br />";
                        echo "LEXEMA: " . $lexema . "<br />";
                        echo "LINHA: " . $linha . "<br />";
                        echo "POSICAO: " . $posicao . "<br />";
                        exit();
                    }
                    $match == false;
                    $posFinal = $posicao - 1;
                    $arrayTokens[] = $this->matchFinal($tokens, $estado, $linha, $posInicial, $posFinal);
                    $tokens = "";
                    $estado = "INICIO";
                    echo "mudado pra inicio <br />";
                    //QUANDO DER PAU AQUI E O ESTADO ATUAL TIVER "FINAL" NO NOME, ADICIONAR PROS TOKENS
                }
                if ($lexema == '$') {
                    return $arrayTokens;
                }
            }
            $match = false;
        }
        return $arrayTokens;
    }



    public function matchFinal($tokens, $estado, $linha, $posInicial, $posFinal) {
        if (preg_match("[FINAL]", $estado)) {
            $objetoToken = new Token(str_replace("FINAL", "", $estado), $tokens, $linha, $posInicial, $posFinal);

            echo "----------------------------- <br />";
            echo "TOKEN: " . $objetoToken->getToken() . "<br />";
            echo "LEXEMA: " . $tokens . "<br />";
            echo "LINHA: " . $linha . "<br />";
            echo "POSICAO INICIAL: " . $posInicial . "<br />";
            echo "POSICAO FINAL: " . $posFinal . "<br />";
            echo "----------------------------- <br />";

            return $objetoToken->getToken();
        }
    }
}
