      <?php
        require "Network.php";
        echo '<div class="main">
            <form action="index.php" method="post" style="margin-left:20px">
                <input type="number" min="0" max="255" name="n1">.
                <input type="number" min="0" max="255" name="n2">.
                <input type="number" min="0" max="255" name="n3">.
                <input type="number" min="0" max="255" name="n4">/
                <input type="number" min="0" max="30" name="prefix">
                <input type="submit" value="Send">
            </form>';
        $n1 = filter_input(INPUT_POST, "n1");
        $n2 = filter_input(INPUT_POST, "n2");
        $n3 = filter_input(INPUT_POST, "n3");
        $n4 = filter_input(INPUT_POST, "n4");
        $prefix = filter_input(INPUT_POST, "prefix");

        /**proces ověření zda je zadaná adresa kompletní a validní */
        if ($prefix != NULL) {
            $power = 32 - $prefix;
        }
        if ($n1 != NULL && $n2 != NULL && $n3 != NULL && $n4 != NULL && $prefix != NULL) {
            if ($prefix >= 24) {
                $param = $n4 % pow(2, $power);
            } else if ($prefix >= 16) {
                $param = $n3 % pow(2, $power - 8);
                if($n4>0){
                    $param=1;
                }
            } else if ($prefix >= 8) {
                $param = $n2 % pow(2, $power - 16);
                if($n4>0||$n3>0){
                    $param=1;
                }
            } else if ($prefix >= 0) {
                $param = $n1 % pow(2, $power - 24);
                if($n4>0||$n3>0||$n2>0){
                    $param=1;
                }
            }
        }

        if ($n1 == NULL || $n2 == NULL || $n3 == NULL || $n4 == NULL || $prefix == NULL) {
            echo "Všechny 4 části adresy a prefix musí být zadány.";
        } else if ($param != 0) {
            echo "Adresa sítě není validní.";
        } else {

            $network = new Network($n1, $n2, $n3, $n4, $prefix);
            $network->getHostCount();
        }
