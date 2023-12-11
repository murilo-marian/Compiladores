<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Analisador de Código</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body>
    <div class="container">
        <h1 style="margin-top: 5%">Analisador de Código</h1>
        <form class="mb-3" method="post">
            <label for="codeInput" class="form-label">Code</label>
            <textarea name="code" type="text" class="form-control mb-2" id="codeInput"></textarea>
            <button class="btn btn-primary" type="submit">
                Submit code
            </button>
        </form>
        <div class="">
            <?php
            include ("analisador\AnaliseLexica.php");
            include ("analisador\Sintatico.php");

            $lexico = new Lexico();
            if (isset($_POST['code'])) {
                $lexico = new Lexico();
                $resultado = $lexico->lex($_POST['code']);
                $sintatico = new SLR();
                if ($sintatico->parser($resultado))
                    echo "\nLinguagem aceita";
                else
                    echo "\nErro ao processar entrada";
            }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>