<?php
// Expressões regulares para os padrões dos tokens
define('INTEGER', '/\d+/');
define('ADD', '/\+/');
define('SUBTRACT', '/-/');

function lexer($input_string)
{
    $tokens = [];
    $position = 0;

    while ($position < strlen($input_string)) {
        // Ignorar espaços em branco
        if (ctype_space($input_string[$position])) {
            $position++;
            continue;
        }

        // Tentar casar com os padrões definidos
        $match = null;
        foreach ([
            ['type' => 'INTEGER', 'pattern' => INTEGER],
            ['type' => 'ADD', 'pattern' => ADD],
            ['type' => 'SUBTRACT', 'pattern' => SUBTRACT]
        ] as $tokenDef) {
            $pattern = $tokenDef['pattern'];
            $matches = [];
            if (preg_match($pattern, $input_string, $matches, 0, $position)) {
                $match = $matches[0];
                $tokens[] = ['type' => $tokenDef['type'], 'value' => $match];
                $position += strlen($match);
                break;
            }
        }

        // Se não corresponder a nenhum padrão, há um erro léxico
        if ($match === null) {
            throw new Exception("Invalid character: {$input_string[$position]}");
        }
    }

    return $tokens;
}

// Exemplo de uso
$input_code = "42 + 3 - 15";
$tokens = lexer($input_code);
print_r($tokens);
