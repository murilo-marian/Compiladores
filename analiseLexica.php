<?php

include "Token.php";

$teste = file_get_contents("./teste.json", "teste.json");
$teste = json_decode($teste, true);
$estado = "INICIO";
$cadeia = "if(3 == 2) {
    print(23)
}";
$match = false;
$cadeiaSplit = mb_str_split($cadeia);
$tokens = "";
$posicao = 0;
$posInicial = 0;
$posFinal = 0;
$linha = 0;
$arrayTokens;

echo '<pre>';
print_r($teste);
echo '</pre>';
foreach ($cadeiaSplit as $lexema) {
    $posicao++; //contador da posicao do caractere


    if ($lexema == "\n") { //contador de linhas
        $linha++;
    }

    while (!$match) {
        if ($estado == "INICIO") {
            $posInicial = $posicao;
        }

        echo '<pre>';
        print_r($teste);
        echo '</pre>';
        foreach ($teste[$estado] as $padrao) {
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
            $arrayTokens[] = matchFinal($tokens, $estado, $linha, $posInicial, $posFinal);
            $tokens = "";
            $estado = "INICIO";
            echo "mudado pra inicio <br />";
            //QUANDO DER PAU AQUI E O ESTADO ATUAL TIVER "FINAL" NO NOME, ADICIONAR PROS TOKENS
        }
    }
    $match = false;
}


function matchFinal($tokens, $estado, $linha, $posInicial, $posFinal) {
    if (preg_match("[FINAL]", $estado)) {
        $objetoToken = new Token(str_replace("FINAL", "", $estado), $tokens, $linha, $posInicial, $posFinal);

        echo "----------------------------- <br />";
        echo "TOKEN: " . $objetoToken->getToken() . "<br />";
        echo "LEXEMA: " . $tokens . "<br />";
        echo "LINHA: " . $linha . "<br />";
        echo "POSICAO INICIAL: " . $posInicial . "<br />";
        echo "POSICAO FINAL: " . $posFinal . "<br />";
        echo "----------------------------- <br />";

        return $objetoToken;
    }
}

//pra cada produção vai ser uma função
//ex:
