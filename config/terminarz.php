<?php 

class Terminarz {

    private $db;

    function __construct($d) {
        $this->db = $d;
    }

    public function checkIfExists($id) {
        $sql = "SELECT * FROM terminarz WHERE id_terminarz = ?";
        $stmt= $this->db->prepare($sql);
        $stmt->execute([$id]); 
        return $stmt->fetch();
    }

    public function insertTerminarz($post) {
        $id_test = htmlspecialchars(trim($post['id_test']));
        $id_pracownik = htmlspecialchars(trim($post['id_pracownik']));
        $terminarz_datadodania = date("Y-m-d");
        $terminarz_data_uruchomienia = htmlspecialchars(trim($post['terminarz_data_uruchomienia']));
        $terminarz_data_zakonczenia =  htmlspecialchars(trim($post['terminarz_data_zakonczenia']));
        $terminarz_statustestu =  "OCZEKUJACY";
    
        $sql = "INSERT INTO terminarz (
         id_test,
         id_pracownik,
         terminarz_datadodania,
         terminarz_data_uruchomienia,
         terminarz_data_zakonczenia,
         terminarz_statustestu
         ) VALUES (?,?,?,?,?,?)";
        $stmt= $this->db->prepare($sql);
        return $stmt->execute([$id_test,$id_pracownik,$terminarz_datadodania,$terminarz_data_uruchomienia,$terminarz_data_zakonczenia,$terminarz_statustestu]);
    }

    public function geTerminarzById($id) {
        $sql = "SELECT * FROM terminarz WHERE id_terminarz = ?";
        $stmt= $this->db->prepare($sql);
        $stmt->execute([$id]); 
        return $stmt->fetch();
    }

    public function getTestsWhichHasNoStatus($testy) {
       $all = $this->getAll();
       $testyToReturn = array();
       $isObecny = false;
       foreach($testy as $tescik) {
           foreach($all as $testyTerminarz) {
               if($tescik['id_test'] === $testyTerminarz['id_test']) {
                 $isObecny = true;
               }
           }
           if(!$isObecny) {
                $testyToReturn[]=$tescik;
           }
           $isObecny = false;
       }
       return $testyToReturn;
    }

    public function getAll() {
        $sql = "SELECT * FROM terminarz";
        return $this->db->query($sql)->fetchAll();
    }

    public function updateStatus($id,$status) {
        $sql = "UPDATE terminarz SET terminarz_statustestu = ? WHERE id_terminarz = ?";
        $stmt= $this->db->prepare($sql);
        $stmt->execute([$status,$id]);
    }
    public function saveFile($id_term,$file) {

        $fileName = $file['name'];
        $tmpName  = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileType = $file['type'];

        $fp      = fopen($tmpName, 'r');
        $content = fread($fp, filesize($tmpName));
        $content = addslashes($content);
        fclose($fp);

        $fileName = addslashes($fileName);
        

        $sql = "INSERT INTO upload (name, size, type, content ) VALUES (?, ?, ?, ?)";
        $stmt= $this->db->prepare($sql);
        $stmt->execute([$fileName,$fileSize,$fileType,$content]);
        $id = $this->db->lastInsertId();

        $sql = "UPDATE terminarz SET 
            file_id = ?
         WHERE 	id_terminarz = ?";

        $stmt= $this->db->prepare($sql);
        $stmt->execute([$id,$id_term]);
    }
    public function saveUwagi($id,$uwagi) {
        $ii = htmlspecialchars(trim($id));
        $uu = htmlspecialchars(trim($uwagi));

        $sql = "UPDATE terminarz SET 
            uwagi = ?
         WHERE 	id_terminarz = ?";

        $stmt= $this->db->prepare($sql);
        $stmt->execute([$uu,$ii]);
    }

    public function getUwagaAndFileById($id) {
        $sql = "SELECT uwagi,file_id FROM terminarz WHERE id_terminarz = ?";
        $stmt= $this->db->prepare($sql);
        $stmt->execute([$id]);
        $dane = $stmt->fetch();
        $sql = "SELECT id, name, size, type FROM upload WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dane['file_id']]);
        $plik = $stmt->fetch();

        return array($dane['uwagi'],$plik);
    }

    public function getAllByTester($idTestera) {
        $sql = "SELECT * FROM terminarz WHERE id_pracownik = ?";
        $stmt= $this->db->prepare($sql);
        $stmt->execute([$idTestera]); 
        return $stmt->fetchAll();
    }

    public function getFile($id) {
        $sql = "SELECT name, size, type, content FROM upload WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $plik = $stmt->fetch();
        return $plik;
    }

    private function getTester($pracownicy, $id) {
        foreach($pracownicy as $pracownik) {
            if($pracownik['id_pracownik'] === $id) {
                return $pracownik['imie']." ".$pracownik['nazwisko'];
                break;
            }
        }
        return "Błąd";
    }

    private function getTest($testy, $id) {
        foreach($testy as $test) {
            if($test['id_test'] === $id) {
                return $test['test_nazwa'];
                break;
            }
        }
        return "Błąd";
    }

    public function loadTest($test,$id_terminarz,$uwagi,$plik) {
        $_id = $test['id_test'];
        $nazwa =   htmlspecialchars(trim($test['test_nazwa']));
        $opis_test = htmlspecialchars(trim($test['test_opis']));
        $instrukcja = htmlspecialchars(trim($test['test_instrukcja']));
        $file_name = is_array($plik) ? $plik['name'] : "" ;
        $id_file = is_array($plik) ? $plik['id'] : "" ;
        echo "<form method='POST' enctype='multipart/form-data' action='' id='update-form' name='update-form' class='update-form'>";
        echo "<h2 class='mb-3'>Wynik testu</h2>";
        echo "<div class='mb-3'>";
        echo "  <input name='id' value='$_id' type='text' required class='form-control disabled' id='id'>";
        echo "</div>";
        echo "<div class='mb-3'>";
        echo "  <label for='test_nazwa' class='form-label'>Nazwa testu</label>";
        echo "  <input name='test_nazwa' value='$nazwa' type='text' maxlength='50' required class='form-control disabled' id='test_nazwa'>";
        echo "  <div class='form-text'>Maksymalnie 50 znaków</div>";
        echo "</div>";
        echo "<div class='mb-3'>";
        echo "  <label for='test_opis' class='form-label'>Opis testu</label>";
        echo "  <input name='test_opis' value='$opis_test' type='text' maxlength='100' required class='form-control disabled' id='test_opis'>";
        echo "  <div class='form-text'>Maksymalna długość to 100 znaków</div>";
        echo "</div>";
        echo "<div class='mb-3'>";
        echo "  <label for='test_instrukcja' class='form-label'>Instrukcja</label>";
        echo "  <textarea id='test_instrukcja' required class='form-control disabled' name='test_instrukcja' rows='5' cols='33'>$instrukcja</textarea>";
        echo "</div>";
        echo "<div class='mb-3'>";
        echo "  <label for='uwagi' class='form-label'>Uwagi</label>";
        echo "  <textarea id='uwagi' class='form-control' name='uwagi' rows='5' cols='33'>$uwagi</textarea>";
        echo "</div>";
        echo "<div class='mb-3'>";
        echo "  <label for='file' class='form-label'>Wybierz plik do wysłania</label>";
        echo "  <input type='hidden' name='MAX_FILE_SIZE' value='50000' />";
        if($file_name != "") {
            echo "  <p id='name-file' style='color:var(--main-bg-color);'>Dodano wcześniej plik o nazwie: $file_name</p><p>
            <a class='normal-button' href='terminarz.php?donwload=true&id=$id_file'>Ściągnij plik</a>
            </p>";
        }
        echo "  <input name='file' id='file' type='file' accept='.txt' />";
        echo "</div>";
        echo "  <input type='hidden' id='id_terminarz' name='id_terminarz' value='$id_terminarz'>";
        echo "<button type='submit' data-href='terminarz.php?save=true' id='update-form-button' class='save-update'>Zapisz</button>";
        echo "</form>";
    }


    public function loadDataAll($terminarze,$testerzy,$testy,$privaliges) {
        if(count($terminarze) == 0) {
           echo "<p class='empty-array'>Brak zadań</p>";
       } else {
           echo "<div class='wyszukiwanie'></div>";
           echo "<table id='sortable' class='table table-striped sortable'>
           <caption>Lista Testów</caption>
           <thead>
               <tr>
                   <th>Id</th>
                   <th>Tester</th>
                   <th>Data dodania</th>
                   <th>Data uruchomienia</th>
                   <th>Data zakończenia</th>
                   <th>Nazwa testu</th>
                   <th>Status</th>
                   <th>Plik</th>";
           echo "</tr></thead><tbody>";
           foreach($terminarze as $terminarz) {
               $id_test = $terminarz['id_test'];
               $id_terminarz = $terminarz["id_terminarz"];
               $test = $this->getTest($testy, $id_test);
               $terminarz_datadodania = $terminarz['terminarz_datadodania'];
               $terminarz_data_uruchomienia = $terminarz['terminarz_data_uruchomienia'];
               $terminarz_data_zakonczenia = $terminarz['terminarz_data_zakonczenia'];
               $tester = $this->getTester($testerzy,$terminarz['id_pracownik']);
               $terminarz_statustestu = $terminarz['terminarz_statustestu'];
               $plik = $this->getFile($id_terminarz); 
               echo "<tr>";
                  echo "<td>$id_terminarz</td>".
                       "<td>$tester</td>".
                       "<td>$terminarz_datadodania</td>".
                       "<td>$terminarz_data_uruchomienia</td>".
                       "<td>$terminarz_data_zakonczenia</td>".
                       "<td>$test</td>".
                       "<td class='$terminarz_statustestu'>$terminarz_statustestu</td>";
                       if(is_array($plik)) {
                        $plik = $plik['name'];  
                    echo "<td><a class='donwload-button' href='terminarz.php?donwload=true&id=$id_terminarz'>Ściągnij plik: $plik</a></td>"; 
                       }
               echo "</tr>";
           }
           echo "</tbody><table>";
       }
       echo "<script type='text/javascript'>
           sorttable.makeSortable(document.getElementById('sortable'));
       </script>";
   }

    public function loadData($terminarze,$testerzy,$testy,$privaliges) {
         if(count($terminarze) == 0) {
            echo "<p class='empty-array'>Brak zadań</p>";
        } else {
            echo "<div class='wyszukiwanie'></div>";
            echo "<table id='sortable' class='table table-striped sortable'>
            <caption>Lista Testów</caption>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Tester</th>
                    <th>Data dodania</th>
                    <th>Data uruchomienia</th>
                    <th>Data zakończenia</th>
                    <th>Nazwa testu</th>
                    <th>Status</th>
                    <th>Wynik</th>";
            echo "</tr></thead><tbody>";
            foreach($terminarze as $terminarz) {
                $id_test = $terminarz['id_test'];
                $id_terminarz = $terminarz["id_terminarz"];
                $test = $this->getTest($testy, $id_test);
                $terminarz_datadodania = $terminarz['terminarz_datadodania'];
                $terminarz_data_uruchomienia = $terminarz['terminarz_data_uruchomienia'];
                $terminarz_data_zakonczenia = $terminarz['terminarz_data_zakonczenia'];
                $tester = $this->getTester($testerzy,$terminarz['id_pracownik']);
                $terminarz_statustestu = $terminarz['terminarz_statustestu'];
                echo "<tr>";
                    echo "<td>$id_terminarz</td>".
                        "<td>$tester</td>".
                        "<td>$terminarz_datadodania</td>".
                        "<td>$terminarz_data_uruchomienia</td>".
                        "<td>$terminarz_data_zakonczenia</td>".
                        "<td>$test</td>".
                        "<td class='$terminarz_statustestu'>$terminarz_statustestu</td>";
                        if($privaliges["terminarz"][1] === 1) {
                            echo "<td><button data-href='terminarz.php?newFromTable=true&id=$id_test&term=$id_terminarz' class='pracownik-open open-button-$id_test'>Otwórz</button></td>";
                        }
                echo "</tr>";
            }
            echo "</tbody><table>";
        }
        echo "<script type='text/javascript'>
            sorttable.makeSortable(document.getElementById('sortable'));
        </script>";
    }
    
    public function loadUpdateData($testerzy,$testy) {
            $today = date('Y-m-d');
            $opis = "Dodaj zadanie"; 
            $save = "add";
            $options = "<option></option>";    
        echo "<form method='POST' action='' id='update-form' name='update-form' class='update-form'>";
        echo "<h2 class='mb-3'>$opis</h2>";
        echo "<div class='mb-3'>";
        echo "  <label for='terminarz_datadodania' class='form-label date-label'>Data dodania</label>";
        echo "  <input type='date' id='terminarz_datadodania' name='terminarz_datadodania' value='$today' readonly />";
        echo "</div>";
        echo "<div class='mb-3'>";
        echo "  <label for='terminarz_data_uruchomienia' class='form-label date-label'>Data uruchomienia</label>";
        echo "  <input type='date' id='terminarz_data_uruchomienia' name='terminarz_data_uruchomienia' min='$today' />";
        echo "</div>";
        echo "<div class='mb-3'>";
        echo "  <label for='terminarz_data_zakonczenia' class='form-label date-label'>Data zakończenia</label>";
        echo "  <input type='date' id='terminarz_data_zakonczenia' name='terminarz_data_zakonczenia' min='$today' />";
        echo "</div>";
        echo "<div class='mb-3'>";
        echo "    <label for='id_pracownik' class='form-label'>Tester</label>";
        echo "    <select required name='id_pracownik' id='id_pracownik' class='form-select'>";
            foreach($testerzy as $tester) {
                $options .= '<option '
                ."value='".$tester['id_pracownik']."'>"
                .$tester['imie']." ".$tester['nazwisko'] 
                .'</option>';
            }
        echo  $options;
        $options = "<option></option>";     
        echo "</select>";
        echo "</div>";
        echo "<div class='mb-3'>";
        echo "    <label for='id_test' class='form-label'>Test</label>";
        echo "    <select required name='id_test' id='id_test' class='form-select'>";
            foreach($testy as $test) {
                $options .= '<option '
                ."value='".$test['id_test']."'>"
                .$test['test_nazwa']
                .'</option>';
            }
        echo  $options;
        $options = "<option></option>";     
        echo "</select>";
        echo "</div>";
        echo "<button type='submit' data-href='terminarz.php?$save=true' id='update-form-button' class='save-update'>Zapisz</button>";
        echo "</form>";
        echo "<script type='text/javascript'>
            setMin('#terminarz_data_uruchomienia','#terminarz_data_zakonczenia'); 
        </script>";
    }
}

