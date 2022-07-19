<?php 

class Testy {

    private $db;

    function __construct($d) {
        $this->db = $d;
    }

    public function checkIfExists($nazwaTestu) {
        $sql = "SELECT * FROM testy WHERE test_nazwa = ?";
        $stmt= $this->db->prepare($sql);
        $stmt->execute([$nazwaTestu]); 
        return $stmt->fetch();
    }

    public function insertTest($post) {
        $test_nazwa = htmlspecialchars(trim($post['test_nazwa']));
        $test_opis = htmlspecialchars(trim($post['test_opis']));
        $test_instrukcja = htmlspecialchars(trim($post['test_instrukcja']));

        $sql = "INSERT INTO testy (
         test_nazwa, 
         test_opis,
         test_instrukcja
         ) VALUES (?,?,?)";
        $stmt= $this->db->prepare($sql);
        return $stmt->execute([$test_nazwa,$test_opis,$test_instrukcja]);
    }

    public function getTestById($id) {
        $sql = "SELECT * FROM testy WHERE id_test=?";
        $stmt= $this->db->prepare($sql);
        $stmt->execute([$id]); 
        return $stmt->fetch();
    }

    public function getAllTesty() {
        $sql = "SELECT * FROM testy";
        return $this->db->query($sql)->fetchAll();
    }

    public function getAllIdTests() {
        $sql = "SELECT id_test FROM testy";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function updateTest($id,$post) {

        $test_nazwa = htmlspecialchars(trim($post['test_nazwa']));
        $test_opis = htmlspecialchars(trim($post['test_opis']));
        $test_instrukcja = htmlspecialchars(trim($post['test_instrukcja']));

        $sql = "UPDATE testy SET 
         test_nazwa=?, 
         test_opis=?,
         test_instrukcja=?
         WHERE 	id_test=?";
        $stmt= $this->db->prepare($sql);
        $stmt->execute([$test_nazwa,$test_opis,$test_instrukcja,$id]);
    }

    public function loadData($testy,$privaliges) {
        if(count($testy) === 0) {
            echo "<p class='empty-array'>Brak danych</p>";
        } else {
            echo "<table class='table table-striped'>
            <caption>Lista testów</caption>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nazwa testu</th>
                    <th>Opis  - skrót</th>
                    <th>Akcja</th>";
            echo "</tr></thead><tbody>";
            foreach($testy as $test) {
                $id = $test["id_test"];
                $nazwa = $test['test_nazwa'];
                $opis = $test['test_opis'];
                echo "<tr>";
                    echo "<td>$id</td>".
                        "<td>$nazwa</td>".
                        "<td>$opis</td><td class='actions'>";
                        if($privaliges["testy"][0] === 1) {
                            echo "<button data-href='testy.php?show=true&id=$id' class='pracownik-open open-button-$id'>Otwórz</button>";
                        }
                        if($privaliges["testy"][3] === 1) {
                            echo "<button data-href='testy.php?update=true&id=$id' class='pracownik-edytuj edytuj-button-$id'>Edytuj</button>";
                        }
                echo "</td></tr>";
            }
            echo "</tbody><table>";
        }
    }

    public function loadUpdateData($data,$id,$isUpdate,$justShow) {
            $_id = $isUpdate ? $id : null;
            $nazwa =  $isUpdate ? htmlspecialchars(trim($data['test_nazwa'])) : null;
            $opis_test = $isUpdate ? htmlspecialchars(trim($data['test_opis'])) : null;
            $instrukcja = $isUpdate ? htmlspecialchars(trim($data['test_instrukcja'])) : null;

            $opis = $isUpdate ? $justShow ? "Zobacz test" : "Zaktualizuj dane" : "Dodaj test"; 
            $save = $isUpdate ? "save" : "add";
            $options = "<option></option>"; 
        echo "<form method='POST' action='' id='update-form' name='update-form' class='update-form'>";
        echo "<h2 class='mb-3'>$opis</h2>";
        if($isUpdate) {
            echo "<div class='mb-3'>";
            echo "<input name='id' value='$_id' type='text' required class='form-control' id='id'>";
            echo "</div>";
        }
        echo "<div class='mb-3'>";
        echo "  <label for='test_nazwa' class='form-label'>Nazwa testu</label>";
        echo "  <input name='test_nazwa' value='$nazwa' type='text' maxlength='50' required class='form-control' id='test_nazwa'>";
        echo "  <div class='form-text'>Maksymalnie 50 znaków</div>";
        echo "</div>";
        echo "<div class='mb-3'>";
        echo "  <label for='test_opis' class='form-label'>Opis testu</label>";
        echo "  <input name='test_opis' value='$opis_test' type='text' maxlength='100' required class='form-control' id='test_opis'>";
        echo "  <div class='form-text'>Maksymalna długość to 100 znaków</div>";
        echo "</div>";
        echo "<div class='mb-3'>";
        echo "  <label for='test_instrukcja' class='form-label'>Instrukcja</label>";
        echo "  <textarea id='test_instrukcja' required class='form-control' name='test_instrukcja' rows='5' cols='33'>$instrukcja</textarea>";
        echo "</div>";
        if(!$justShow) {
            echo "<button type='submit' data-href='testy.php?$save=true' id='update-form-button' class='save-update'>Zapisz</button>";
        }
        echo "</form>";
    }
}

