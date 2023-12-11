<?php


class SLR {
    private $afd;

    //ADICIONAR PONTO E VIRGULA NO LEXICO

    public function __construct() {
        $teste = file_get_contents("././tabelas/transicoesSintatico.json");

        $this->afd = json_decode($teste, true);
    }

    /***
     * Entrada deve ser a lista de tokens gerada pelo analisador léxico
     */
    public function parser($entrada) {
        include_once "Token.php";
        include_once "TabelaSimbolos.php";

        $tokens;
        for ($i = 0; $i < count($entrada); $i++) {
            $tokens[] = $entrada[$i]->getToken();
        }

        $tokens[] = '$';

        $pilha = array();
        array_push($pilha, 0);
        echo '<br/>';
        $tabelaSimbolos = [];
        $tabelaSimbolos[] = new TabelaSimbolos();
        $i = 0;
        $tipo = null;
        while ($tokens) {

            echo "\nPilha:" . implode(' ', $pilha);
            echo '<br/>';

            if (array_key_exists($tokens[$i], $this->afd[end($pilha)]["ACTION"])) {
                $move = $this->afd[end($pilha)]['ACTION'][$tokens[$i]];
            } else if (array_key_exists("", $this->afd[end($pilha)]["ACTION"])) {
                $move = $this->afd[end($pilha)]['ACTION'][""];
            } else {
                return false;
            }

            $acao = explode(' ', $move);
            echo " | Ação:" . $move;
            echo '<br/>';
            switch ($acao[0]) {
                case 'S': // Shift - Empilha e avança o ponteiro
                    array_push($pilha, $acao[1]);
                    $i++;
                    if ($tokens[$i - 1] == "ID" && isset($tipo)) {
                        foreach ($tabelaSimbolos as $tabela) {
                            if ($tabela->exists($entrada[$i - 1]->getLexema())) {
                                echo 'ERROR - VARIABLE ALREADY DECLARED';
                                exit();
                            }
                        }
                        end($tabelaSimbolos)->insert($entrada[$i - 1]->getLexema(), $tipo);
                        echo 'added to table';
                    } else if ($tokens[$i -1] == "ID") {
                        $exists = false;
                        foreach ($tabelaSimbolos as $tabela) {
                            if ($tabela->exists($entrada[$i - 1]->getLexema())) {
                                $exists = true;
                                break;
                            }
                        }
                        if (!$exists) {
                            echo 'ERROR - VARIABLE NOT DECLARED';
                            exit();
                        }
                    }

                    if ($tokens[$i - 1] == "ABRECHAVES") {
                        $tabelaSimbolos[] = new TabelaSimbolos;
                    } else if ($tokens[$i - 1] == "FECHACHAVES") {
                        array_pop($tabelaSimbolos);
                    }

                    $tipo = null;
                    break;
                case 'R': // Reduce - Desempilha e Desvia (para indicar a redução)
                    if ($acao[2] == "TIPO") {
                        $tipo = $entrada[$i - 1]->getLexema();
                        echo 'tipo detected';
                    } else if ($tokens[$i] == "ID" && isset($tipo)) {
                        end($tabelaSimbolos)->insert($entrada[$i]->getLexema(), $tipo);
                        echo 'added to table';
                        $tipo = null;
                    }else {
                        $tipo = null;
                    }

                    for ($j = 0; $j < $acao[1]; $j++) {
                        array_pop($pilha);
                    }
                    echo '<br/>';
                    echo '<br/>';
                    echo ' | Reduziu para ' . $acao[2];
                    echo '<br/>';

                    if (array_key_exists($tokens[$i], $this->afd[end($pilha)]['GOTO'][$acao[2]])) {
                        $desvio = $this->afd[end($pilha)]['GOTO'][$acao[2]][$tokens[$i]];
                    } else if (array_key_exists("", $this->afd[end($pilha)]['GOTO'][$acao[2]])) {
                        $desvio = $this->afd[end($pilha)]['GOTO'][$acao[2]][""];
                    }
                    array_push($pilha, $desvio);
                    break;
                case 'ACC': // Accept
                    echo 'Ok';
                    print_r($tabelaSimbolos);
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
