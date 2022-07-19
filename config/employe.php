<?php 

class Employ {

    private $db;

    function __construct($d) {
        $this->db = $d;
    }

    public function checkIfExists($login) {
        $sql = "SELECT * FROM pracownicy WHERE login=?";
        $stmt= $this->db->prepare($sql);
        $stmt->execute([$login]); 
        return $stmt->fetch();
    }

    public function insertEmploy($imie,$nazwisko,$login,$password,$stanowisko) {
        $sql = "INSERT INTO pracownicy (nazwisko, imie, stanowisko,login,password) VALUES (?,?,?,?,?)";
        $stmt= $this->db->prepare($sql);
        return $stmt->execute([$nazwisko,$imie, $stanowisko,$login,$password]);
    }

    public function getUser($login,$password) {
        $sql = "SELECT * FROM pracownicy WHERE login=? AND password=?";
        $stmt= $this->db->prepare($sql);
        $stmt->execute([$login,$password]); 
        return $stmt->fetch();
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM pracownicy WHERE id_pracownik=?";
        $stmt= $this->db->prepare($sql);
        $stmt->execute([$id]); 
        return $stmt->fetch();
    }

    public function getSessionUser($login) {
        $sql = "SELECT * FROM pracownicy WHERE login=?";
        $stmt= $this->db->prepare($sql);
        $stmt->execute([$login]); 
        return $stmt->fetch();
    }

    public function isTester($login) {
        $sql = "SELECT id_pracownik, stanowisko FROM pracownicy WHERE login=?";
        $stmt= $this->db->prepare($sql);
        $stmt->execute([$login]); 
        $wynik = $stmt->fetch();
        if($wynik['stanowisko'] === 'tester') {
            return $wynik['id_pracownik'];
        }else {
            return -1;
        }
    }

    public function getAllTesters() {
        $sql = "SELECT * FROM pracownicy WHERE stanowisko = 'tester'";
        return $this->db->query($sql)->fetchAll();
    }

    public function getAllEmployers() {
        $sql = "SELECT * FROM pracownicy";
        return $this->db->query($sql)->fetchAll();
    }

    public function deleteEmployee($id) {
        $this->db->prepare("DELETE FROM pracownicy WHERE id_pracownik=?")->execute([$id]);
    }

    public function updateEmployee($id,$post) {
        $imie = htmlspecialchars(trim($post['imie']));
        $nazwisko = htmlspecialchars(trim($post['nazwisko']));
        $login = htmlspecialchars(trim($post['login']));
        $password = htmlspecialchars(trim($post['password']));
        $stanowisko = htmlspecialchars(trim($post['stanowisko']));

        $sql = "UPDATE pracownicy SET nazwisko=?, imie=?, login=?, password=?, stanowisko=? WHERE id_pracownik=?";
        $stmt= $this->db->prepare($sql);
        $stmt->execute([$imie, $nazwisko, $login, $password,$stanowisko, $id]);
    }

    public function loadData($employers,$privaliges,$user) {
        echo "<table class='table table-striped'>
        <caption>Lista pracowników</caption>
        <thead>
            <tr>
                <th>ID</th>
                <th>Imię</th>
                <th>Nazwisko</th>
                <th>Login</th>
                <th>Hasło</th>
                <th>Stanowisko</th>";
                if($privaliges["raporty"][3] === 1) {
                    echo "<th class='center'>Edytuj</th>";
                 }
                 /*if($privaliges["pracownicy"][1] === 1) {
                    echo "<th class='center'>Usuń</th>";
                 }*/
        echo "</tr></thead><tbody>";
        foreach($employers as $employer) {
            $id = $employer["id_pracownik"];
            $imie = $employer["imie"];
            $nazwisko = $employer["nazwisko"];
            $login = $employer["login"];
            $password = $employer["password"];
            $stanowisko = $employer["stanowisko"];
            $disableFunction = $user["login"] !== $login ? "employers.php?delete=true&id=".$id : "javascript:void(0)";
            $disableClass = $user["login"] === $login ? "disable-button" : "standart-button";
            echo "<tr>";
                echo "<td>$id</td>".
                    "<td>$imie</td>".
                    "<td>$nazwisko</td>".
                    "<td>$login</td>".
                    "<td>$password</td>".
                    "<td>$stanowisko</td>";
                    if($privaliges["raporty"][3] === 1) {
                        echo "<td><button data-href='employers.php?update=true&id=$id' class='pracownik-edytuj edytuj-button-$id'>Edytuj</button></td>";
                    }
                    /*if($privaliges["pracownicy"][1] === 1) {
                        echo "<td><button data-href='$disableFunction' class='$disableClass pracownik-usun usun-button-$id'>Usuń</button></td>";
                    }*/
            echo "</tr>";
        }
        echo "</tbody><table>";
        if($privaliges["raporty"][2] === 1){
            echo "<p><button data-href='employers.php?new=true' type='submit' class='save-add'>Dodaj pracownika</button></p>";
        }
    }

    public function loadUpdateData($post,$id,$sessionUser,$isUpdate) {
            $_id = $isUpdate ? $id : null;
            $imie = $isUpdate ? htmlspecialchars(trim($post['imie'])) : null;
            $nazwisko = $isUpdate ? htmlspecialchars(trim($post['nazwisko'])) : null;
            $login = $isUpdate ? htmlspecialchars(trim($post['login'])) : null;
            $password = $isUpdate ? htmlspecialchars(trim($post['password'])) : null;
            $stanowisko = $isUpdate ? htmlspecialchars(trim($post['stanowisko'])) : null;
            $opis = $isUpdate ? "Zaktualizuj dane" : "Dodaj pracownika"; 
            $save = $isUpdate ? "save" : "add";
            $additionaUrl = $sessionUser === $login && $isUpdate ? "&need-login=true" : null;

        echo "<form method='POST' action='' id='update-form' name='update-form' class='update-form'>";
        echo "<h2 class='mb-3'>$opis</h2>";
        if($isUpdate) {
            echo "<div class='mb-3'>";
            echo "<input name='id' value='$_id' type='text' required class='form-control' id='id'>";
            echo "</div>";
        }
        echo "<div class='mb-3'>";
        echo "<label for='imie' class='form-label'>Imię</label>";
        echo "<input name='imie' value='$imie' type='text' maxlength='80' required class='form-control' id='imie'>";
        echo "<div class='form-text'>Maksymalna długość to 80 znaków</div>";
        echo "</div>";
        echo "<div class='mb-3'>";
        echo "  <label for='nazwisko' class='form-label'>Nazwisko</label>";
        echo "  <input name='nazwisko' value='$nazwisko' type='text' maxlength='80' required class='form-control' id='nazwisko'>";
        echo "  <div class='form-text'>Maksymalna długość to 80 znaków</div>";
        echo "</div>";
        echo "<div class='mb-3'>";
        echo "  <label for='login' class='form-label'>Login</label>";
        echo "  <input name='login' value='$login' type='text' maxlength='80' required class='form-control' id='login' aria-describedby='imie'>";
        echo "  <div class='form-text'>Maksymalna długość to 80 znaków</div>";
        echo "</div>";
        echo "<div class='mb-3'>";
        echo "  <label for='password' class='form-label'>Hasło</label>";
        echo "  <input name='password' value='$password' type='password' minlength='10' maxlength='20' required class='form-control' id='password'>";
        echo "  <div class='form-text'>Długość pomiędzy 10 a 20 znaków</div>";
        echo "</div>";
        echo "<div class='mb-3'>";
        echo "    <label for='stanowisko' class='form-label'>Stanowisko</label>";
        echo "    <select required name='stanowisko' id='stanowisko' class='form-select'>";
            $options = "<option></option>"; 
            foreach(getStanowiska() as $stanowisk) {
                $options .= '<option ' 
                .( $stanowisk == $stanowisko ? 'selected="selected"' : '' ) . '>' 
                .  $stanowisk 
                . '</option>';
            }
        echo  $options;    
        echo "</select>";
        echo "</div>";
        if($isUpdate && $additionaUrl !== null) {
            echo "<p class='need-login'>Uwaga będzie potrzebne przelogowanie</p>";
        }
        echo "<button type='submit' data-href='employers.php?$save=true$additionaUrl' id='update-form-button' class='save-update'>Zapisz</button>";
        echo "</form>";
    }

    public function getPrivilages($type) {
        $privilages = getPrivilagesArray();
        switch($type) {
            case "kierownik":
                 return  $privilages['kierownik'];
            case "tester":
                return  $privilages['tester'];          
        }
    }
    public function loadPrivilages() {
        $privilages = getPrivilagesArray();
        echo "<table class='table table-striped uprawnienia'>
        <thead>
            <tr>
                <th>Stanowisko</th>
                <th colspan='4'>Testy</th>
                <th colspan='4'>Terminarz</th>
                <th colspan='4'>Raporty</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Uprawnienia:</td>
                <td>Search</td>
                <td>Delete</td>
                <td>Insert</td>
                <td>Update</td>
                <td>Search</td>
                <td>Delete</td>
                <td>Insert</td>
                <td>Update</td>
                <td>Search</td>
                <td>Delete</td>
                <td>Insert</td>
                <td>Update</td>
            </tr>";
            foreach(getStanowiska() as $stanowisk) {
                $stanowisko = $privilages[$stanowisk];
                echo "<tr><td>$stanowisk</td>";
                foreach($stanowisko as $key) {
                    foreach($key as $ke) {
                        echo "<td>$ke</td>"; 
                    }
                }
                echo "</tr>";
            }
        echo "</tbody><table>";
    }
}

