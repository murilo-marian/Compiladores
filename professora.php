<?php

/***
 * Gramática LR
 * EXP → EXP mais EXP2 | EXP2
 * EXP2 → EXP2 menos EXP3 | EXP3
 * EXP3 → id
 * Folllow(EXP3): { $, menos, mais}
 * Folllow(EXP2): { $, menos, mais}
 * Folllow(EXP): { $, mais}
 */

define('EXP', 0);
define('EXP2', 1);
define('EXP3', 2);
define('NAO_TERMINAIS', [0 => 'EXP', 1 => 'EPX2', 2 => 'EXP3']);

class SLR {
    private $afd;

    public function __construct() {
        $this->afd = array(
            0 => ['ACTION' => ['id' => 'S 2'], 'GOTO' => [EXP => ['$' => 1, 'mais' => 5], EXP2 => ['$' => 8, 'menos' => 9, 'mais' => 8], EXP3 => ['$' => 3, 'menos' => 3, 'mais' => 3]]],
            1 => ['ACTION' => ['$' => 'ACC '], 'GOTO' => []],
            2 => ['ACTION' => ['$' => 'R 1 2', 'mais' => 'R 1 2', 'menos' => 'R 1 2'], 'GOTO' => []],
            3 => ['ACTION' => ['$' => 'R 1 1', 'mais' => 'R 1 1', 'menos' => 'R 1 1'], 'GOTO' => []],
            5 => ['ACTION' => ['mais' => 'S 6'], 'GOTO' => []],
            6 => ['ACTION' => ['id' => 'S 2'], 'GOTO' => [EXP2 => ['$' => 7, 'menos' => 9, 'mais' => 8], EXP3 => ['$' => 3, 'menos' => 3, 'mais' => 3]]],
            7 => ['ACTION' => ['$' => 'R 3 0', 'mais' => 'R 3 0'], 'GOTO' => []],
            8 => ['ACTION' => ['$' => 'R 1 0', 'mais' => 'R 1 0'], 'GOTO' => []],
            9 => ['ACTION' => ['menos' => 'S 10'], 'GOTO' => []],
            10 => ['ACTION' => ['id' => 'S 2'], 'GOTO' => [EXP2 => ['$' => 11, 'menos' => 11, 'mais' => 11], EXP3 => ['$' => 11, 'menos' => 11, 'mais' => 11]]],
            11 => ['ACTION' => ['$' => 'R 3 1', 'mais' => 'R 3 1', 'menos' => 'R 3 1'], 'GOTO' => []]
        );
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
        while ($entrada) {
            if (array_key_exists($entrada[$i], $this->afd[end($pilha)]['ACTION'])) {
                $move = $this->afd[end($pilha)]['ACTION'][$entrada[$i]];
            } else
                return false;
            $acao = explode(' ', $move);
            echo " | Ação:" . $move;
            echo '<br/>';
            switch ($acao[0]) {
                case 'S': // Shift - Empilha e avança o ponteiro
                    array_push($pilha, $acao[1]);
                    $i++;
                    break;
                case 'R': // Reduce - Desempilha e Desvia (para indicar a redução)  
                    for ($j = 0; $j < $acao[1]; $j++)
                        array_pop($pilha);
                    echo ' | Reduziu para ' . NAO_TERMINAIS[$acao[2]];
                    $desvio = $this->afd[end($pilha)]['GOTO'][$acao[2]][$entrada[$i]];
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
$entrada = array('id', 'menos', 'id', 'mais', 'id', '$'); // considerar que cada item é um token gerado pelo analisador léxico
if ($slr->parser($entrada))
    echo "\nLinguagem aceita";
else
    echo "\nErro ao processar entrada";
