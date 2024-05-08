<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Calculadora PHP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <style>
        /* Estilo personalizado */
        /* Definir um fundo colorido para a área da calculadora */
        .calculator-container {
            background-color: #f0f8ff; /* Cor de fundo */
            padding: 20px; /* Padding para espaçamento interno */
            border-radius: 15px; /* Bordas arredondadas */
            max-width: 700px; /* Limite de largura */
            margin: auto; /* Centralizar */
        }
    </style>
</head>

<body>
    <div class="calculator-container">
        <div class="bg-dark p-3 rounded mx-auto text-white">
            <h1 class="display-6 text-center">Calculadora PHP</h1>
            <form action="" method="GET">
                <div class="input-group mb-3">
                    <span class="input-group-text">Número 1</span>
                    <input type="number" step="any" class="form-control" name="num1" value="<?php echo isset($_GET['num1']) ? htmlspecialchars($_GET['num1']) : ''; ?>">
                    
                    <span class="input-group-text ms-2">Operação</span>
                    <select class="form-select" name="op">
                        <option value="" selected>Operação</option>
                        <option value="+" <?php echo isset($_GET['op']) && $_GET['op'] === '+' ? 'selected' : ''; ?>>+</option>
                        <option value="-" <?php echo isset($_GET['op']) && $_GET['op'] === '-' ? 'selected' : ''; ?>>-</option>
                        <option value="*" <?php echo isset($_GET['op']) && $_GET['op'] === '*' ? 'selected' : ''; ?>>*</option>
                        <option value="/" <?php echo isset($_GET['op']) && $_GET['op'] === '/' ? 'selected' : ''; ?>>/</option>
                        <option value="^" <?php echo isset($_GET['op']) && $_GET['op'] === '^' ? 'selected' : ''; ?>>^</option>
                        <option value="!" <?php echo isset($_GET['op']) && $_GET['op'] === '!' ? 'selected' : ''; ?>>!</option>
                    </select>
                    
                    <span class="input-group-text ms-2">Número 2</span>
                    <input type="number" step="any" class="form-control" name="num2" value="<?php echo isset($_GET['num2']) ? htmlspecialchars($_GET['num2']) : ''; ?>">
                    
                    <input type="submit" value="Calcular" class="btn btn-outline-success ms-2">
                </div>
                
                <div class="mb-2">
                    <input type="submit" name="action" value="Salvar" class="btn btn-outline-warning">
                    <input type="submit" name="action" value="Recuperar" class="btn btn-outline-secondary">
                    
                    <input type="submit" value="M" class="btn btn-outline-info">
                    <input type="submit" name="action" value="Apagar Histórico" class="btn btn-outline-danger">
                </div>
            </form>

            <?php
            // Iniciando a sessão
            session_start();

            // Funções para manipular sessão e cálculos
            function add_to_history($entry) {
                if (!isset($_SESSION['history'])) {
                    $_SESSION['history'] = [];
                }
                $_SESSION['history'][] = $entry;
            }

            function clear_history() {
                unset($_SESSION['history']);
            }

            function save_to_memory($data) {
                $_SESSION['memory'] = $data;
            }

            function retrieve_from_memory() {
                return isset($_SESSION['memory']) ? $_SESSION['memory'] : null;
            }

            function clear_memory() {
                unset($_SESSION['memory']);
            }

            function calculate_factorial($n) {
                if ($n < 0) {
                    return "Erro: Fatorial não definido para números negativos";
                }
                $result = 1;
                for ($i = 2; $i <= $n; $i++) {
                    $result *= $i;
                }
                return $result;
            }

            function perform_calculation($num1, $num2, $op) {
                switch ($op) {
                    case '+':
                        return $num1 + $num2;
                    case '-':
                        return $num1 - $num2;
                    case '*':
                        return $num1 * $num2;
                    case '/':
                        return $num2 !== 0 ? $num1 / $num2 : 'Erro: Divisão por zero!';
                    case '^':
                        return pow($num1, $num2);
                    case '!':
                        return calculate_factorial($num1);
                    default:
                        return 'Erro: Operação inválida';
                }
            }

            // Funções para exibir mensagens de erro e sucesso
            function display_error($message) {
                echo '<div class="alert alert-danger mt-2">' . $message . '</div>';
            }

            function display_alert($message) {
                echo '<div class="alert alert-info mt-2">' . $message . '</div>';
            }

            // Lógica para manipular as ações
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                // Manipulação de ações de recuperação e memória
                if (isset($_GET['action'])) {
                    if ($_GET['action'] === 'Limpar') {
                        clear_memory();
                        display_alert('Memória limpa.');
                    } elseif ($_GET['action'] === 'Recuperar') {
                        $value = retrieve_from_memory();
                        if ($value !== null) {
                            $num1 = $value['num1'];
                            $op = $value['op'];
                            $num2 = $value['num2'];
                            display_alert('Valores recuperados.');
                        } else {
                            display_alert('Nenhum valor armazenado na memória.');
                        }
                    } elseif ($_GET['action'] === 'Salvar') {
                        $num1 = isset($_GET['num1']) ? $_GET['num1'] : null;
                        $op = isset($_GET['op']) ? $_GET['op'] : null;
                        $num2 = isset($_GET['num2']) ? $_GET['num2'] : null;
                        if ($num1 !== null && $op !== null && $num2 !== null) {
                            save_to_memory(['num1' => $num1, 'op' => op, 'num2' => $num2]);
                            display_alert('Valores salvos na memória.');
                        } else {
                            display_alert('Por favor, forneça valores válidos para salvar.');
                        }
                    } elseif ($_GET['action'] === 'Apagar Histórico') {
                        clear_history();
                        display_alert('Histórico apagado.');
                    }
                }

                // Manipulação do cálculo
                if (isset($_GET['num1']) && isset($_GET['num2']) && isset($_GET['op'])) {
                    $num1 = $_GET['num1'];
                    $num2 = $_GET['num2'];
                    $op = $_GET['op'];

                    if ($op !== '!' && ($num1 === '' || $num2 === '')) {
                        display_error('Erro: Todos os campos (Número 1, Operação, Número 2) devem ser preenchidos.');
                    } else {
                        if ($num1 !== '' && $num2 !== '') {
                            $num1 = (float)$num1;
                            $num2 = (float)$num2;
                        }

                        $result = perform_calculation($num1, $num2, $op);

                        if (is_numeric($result)) {
                            $entry = "$num1 $op $num2 = $result";
                            add_to_history($entry);
                            save_to_memory(['num1' => $num1, 'op' => $op, 'num2' => $num2]);

                            display_alert('Resultado: ' . $result);
                            echo '<div class="alert alert-info mt-2"><strong>Histórico:</strong></div>';
                            if (isset($_SESSION['history'])) {
                                foreach ($_SESSION['history'] as $operation) {
                                    echo '<div>' . htmlspecialchars($operation) . '</div>';
                                }
                            }
                        } else {
                            display_error($result);
                        }
                    }
                }
            }
            ?>
        </div>
    </div>
</body>

</html>
