<?php


class SLR {
    private $afd;

    //ADICIONAR PONTO E VIRGULA NO LEXICO

    public function __construct() {
        $teste = file_get_contents("./transicoesSintatico.json", "transicoesSintatico.json");

        $this->afd = $teste = json_decode($teste, true);
    }

    /***
     * Entrada deve ser a lista de tokens gerada pelo analisador léxico
     */
    public function parser($entrada) {

        $pilha = array();
        array_push($pilha, 0);
        echo "\nPilha:" . implode(' ', $pilha);
        echo '<br/>';
        echo '<br/>';
        $i = 0;
        $ERRO = 0;
        $ENTRADAERRO = $entrada[0];
        while ($entrada) {
            if (array_key_exists($entrada[$i], $this->afd[end($pilha)]["ACTION"])) {
                echo "tem1";
                $move = $this->afd[end($pilha)]['ACTION'][$entrada[$i]];
            } else if (array_key_exists("", $this->afd[end($pilha)]["ACTION"])) {
                echo "tem2";
                $move = $this->afd[end($pilha)]['ACTION'][""];
            } else {
                echo "false";
                return false;
            }

            $acao = explode(' ', $move);
            echo " | Ação:" . $move;
            echo '<br/>';
            switch ($acao[0]) {
                case 'S': // Shift - Empilha e avança o ponteiro
                    array_push($pilha, $acao[1]);
                    $i++;
                    break;
                case 'R': // Reduce - Desempilha e Desvia (para indicar a redução)  
                    for ($j = 0; $j < $acao[1]; $j++) {
                        array_pop($pilha);
                        echo '<br/>';
                    }
                    echo '<br/>';
                    echo ' | Reduziu para ' . $acao[2];
                    echo '<br/>';
                    if (array_key_exists($entrada[$i], $this->afd[end($pilha)]['GOTO'][$acao[2]])) {
                        $desvio = $this->afd[end($pilha)]['GOTO'][$acao[2]][$entrada[$i]];
                    } else if (array_key_exists("", $this->afd[end($pilha)]['GOTO'][$acao[2]])) {
                        $desvio = $this->afd[end($pilha)]['GOTO'][$acao[2]][""];
                    }
                    array_push($pilha, $desvio);
                    break;
                case 'ACC': // Accept
                    echo 'Ok';
                    return true;
                default:
                    echo 'Erro';
                    return false;
            }
            echo "\nPilha:" . implode(' ', $pilha);
            echo '<br/>';
            echo '<br/>';
        }
    }
}

// Testando
$slr = new SLR();
$entrada = array('IF', 'ABREPARENTESES', 'ID', 'SOMA', 'CONST', 'MAIOR', 'CONST', 'FECHAPARENTESES', 'ABRECHAVES', "WHILE", 'ABREPARENTESES', 'ID', 'MENOR', 'ID', 'FECHAPARENTESES', 'ABRECHAVES', 'FECHACHAVES', 'FECHACHAVES', '$'); // considerar que cada item é um token gerado pelo analisador léxico
if ($slr->parser($entrada))
    echo "\nLinguagem aceita";
else
    echo "\nErro ao processar entrada";
