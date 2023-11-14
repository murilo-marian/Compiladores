<?php

include "AnaliseLexica.php";
include "Sintatico.php";
// Testando
$lex = new Lexico();
$tokens = $lex->lex("
if(teste){
    switch(teste){
        case id: {
            print(teste)
        }
        default: {
            teste = 3
        }
    }
    for(id = 0; id < 3; id++) {
        print(teste + id)
    }
}
do{}while(teste)");
$slr = new SLR();
if ($slr->parser($tokens))
    echo "\nLinguagem aceita";
else
    echo "\nErro ao processar entrada";
