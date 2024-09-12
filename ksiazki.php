<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ksiazki</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php
        $connected = mysqli_connect("localhost", "root", "", "biblioteka2"); //połączenie z bazą danych
        include 'funkcje_biblioteka.php'; 
        session_start();

        echo '<nav>
            <a href = "biblioteka.php"> Biblioteka </a>
            <a href = "autorzy.php"> Autorzy </a>
            <a href = "czytelnicy.php"> Czytelnicy </a>
            <div class = "animation start-Ksiazki"> </div>
        </nav> <br> <br> <br> '; //nawigacja

        echo '<div class = "lewa">';
        echo '<form method = "post" action = "http://localhost/iza/biblioteka/ksiazki.php">
            <table>
                <tr>
                    <td> Tytuł: <input class = "wprowadz" name = "tytul"> </td>
                    <td> Autorzy: <input class = "wprowadz" name = "wyszukiwarka"> </td> 
                    <td> 
                        <button type = "submit" class = "wyszukaj" name = "wyszukaj">
                            <i class="fas fa-search"> </i>
                        </button> 
                    </td>';

                    $kwarenda = "SELECT ID, Gatunek
                    FROM gatunki"; //wyswietla dostepne przedmioty
                    $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");
                    echo '<td> Gatunek: <select class = "wprowadz" name = "gatunek">';
                    while($wypisz = mysqli_fetch_row($wynik))
                    {    
                        echo "<option value = '" . $wypisz[0] . "'>" . $wypisz[1]. "</option>"; //wyswietla dostepne przedmioty jako select            
                    }           
                    echo '</select> 
                </tr>
            </table> <br>
            <input class = "btn" type = "submit" name = "wyswietl" value = "Wyświetl">
            </form>';
                    
        echo '</div>';

        if(@$_POST["dodaj"] == true) //jesli kliknie przycisk zostaje przekierowany do innej strony
        {
            if($_POST['znacznik'] == null)
            {
                echo '<div class = "komunikaty"> Wybierz autora </div>';
            }
            else
            {
                $ID_autor = $_POST['znacznik'];
                dodaj_ksiazke($connected, $ID_autor);
            }
        }
        else if(isset($_POST['wyszukaj']))
        {
            $Tytul = $_POST['tytul'];
            $ID_gatunku = $_POST['gatunek'];

            if($_POST["wyszukiwarka"] == null || $Tytul == null || $ID_gatunku == null)
            {
                echo '<div class = "komunikaty"> Wpisz tytul i wyszukaj autora </div>';
            }
            else
            {
                $_SESSION['tytul'] = $Tytul;
                $_SESSION['gatunek'] = $ID_gatunku;

                wyszukaj_autora($connected);
            }
        }
        else if(@$_POST["wyswietl"] == true)
        {
            wyswietl_ksiazki($connected);
        }
        else if(isset($_POST["usun"]))
        {
            usun_ksiazke($connected);
        }
        else if(isset($_POST["przejdz_do_edycji"]))
        {
            $ID_ksiazki = $_POST['przejdz_do_edycji'];
            $_SESSION['przejdz_do_edycji'] = $ID_ksiazki; // Przypisanie wartości do zmiennej sesji    
            przejscie_do_edycji_ksiazka($connected);
        }
        else if(isset($_POST["edytuj"]))
            {
                if($_POST["nowy_tytul"] == null)
                {
                    echo '<div class = "komunikaty"> Musisz podac tytuł </div>';
                }
                else
                {
                    edytuj_ksiazke($connected);
                }
            }
    ?>
    
</body>
</html>