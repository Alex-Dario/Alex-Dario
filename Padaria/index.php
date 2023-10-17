<!DOCTYPE html>
<html>

<head>
    <title>Relatório de Consumo de Ingredientes</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Relatório de Consumo de Ingredientes</h1>

    <form method="post">
        <label for="data_inicio">Data de Início:</label>
        <input type="date" name="data_inicio" required>

        <label for="data_fim">Data de Fim:</label>
        <input type="date" name="data_fim" required>

        <input type="submit" value="Gerar Relatório">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $data_inicio = strtotime($_POST["data_inicio"]);
        $data_fim = strtotime($_POST["data_fim"]);

        $csvFile = "Padaria_Bom_Dia.csv"; // Nome do arquivo CSV

        if (($handle = fopen($csvFile, "r")) !== false) {
            $consumo_total_por_ingrediente = [];
            $consumo_total = 0;

            // Ignorar a primeira linha (cabeçalhos)
            fgetcsv($handle);

            while (($data = fgetcsv($handle, 1000, ";")) !== false) {
                $data_consumo = strtotime($data[0]);

                if ($data_consumo >= $data_inicio && $data_consumo <= $data_fim) {
                    for ($i = 1; $i < count($data); $i++) {
                        $ingrediente = "Ingrediente_" . $i;
                        $quantidade = $data[$i];

                        if (is_numeric($quantidade)) {
                            if (!isset($consumo_total_por_ingrediente[$ingrediente])) {
                                $consumo_total_por_ingrediente[$ingrediente] = 0;
                            }
                            $consumo_total_por_ingrediente[$ingrediente] += $quantidade;

                            $consumo_total += $quantidade;
                        }
                    }
                }
            }

            fclose($handle);

            echo "<h2>Consumo Total de Ingredientes no Período:</h2>";
            echo "Período: " . date("Y-m-d", $data_inicio) . " a " . date("Y-m-d", $data_fim) . "<br>";
            echo "Consumo Total: $consumo_total unidades<br>";

            echo "<h2>Consumo por Ingrediente no Período:</h2>";
            echo "<ul>";
            foreach ($consumo_total_por_ingrediente as $ingrediente => $quantidade) {
                echo "<li>$ingrediente: $quantidade unidades</li>";
            }
            echo "</ul>";
        } else {
            echo "Erro ao abrir o arquivo CSV.";
        }
    }
    ?>
</body>

</html>