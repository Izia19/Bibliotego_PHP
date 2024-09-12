<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>czytelnicy</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php
        $connected = mysqli_connect("localhost", "root", "", "biblioteka2"); //połączenie z bazą danych
        include 'funkcje_biblioteka.php'; 
        session_start();

        echo '<nav>
            <a href = "ksiazki.php"> Ksiazki </a>
            <a href = "autorzy.php"> Autorzy </a>
            <a href = "biblioteka.php"> Biblioteka </a>
            <div class = "animation start-Czytelnicy"> </div>
        </nav> <br> <br> <br> '; //nawigacja

        echo '<div class = "lewa">';
        echo '<form method = "post" action = "http://localhost/iza/biblioteka/czytelnicy.php">
            <table>
                <tr>
                    <td> Imie: <input class = "wprowadz" name = "imie"> </td>
                    <td> Nazwisko: <input class = "wprowadz" name = "nazwisko"> </td> 
                </tr>
                
            </table> <br>
            <input class = "btn" type = "submit" name = "wyswietl" value = "Wyświetl">
            <input class = "btn" type = "submit" name = "wypozyczone_ksiazki" value = "Wypożyczone">
            <input class = "btn" type = "submit" name = "dodaj" value = "Dodaj"> 
            </form>';
                    
        echo '</div>';

        if(@$_POST["dodaj"] == true) //jesli kliknie przycisk zostaje przekierowany do innej strony
        {
            if($_POST['imie'] == null || $_POST['nazwisko'] == null)
            {
                echo '<div class = "komunikaty"> Podaj imie i nazwisko </div>';
            }
            else
            {
                dodaj_czytelnika($connected);
            }
        }
        else if(@$_POST["wyswietl"] == true)
        {
            wyswietl_czytelnikow($connected);
        }
        else if(@$_POST["wypozyczone_ksiazki"] == true)
        {
            wyswietl_wypozyczone_ksiazki($connected);
        }
        else if(isset($_POST["usun"]))
        {
            usun_czytelnika($connected);
        }
        else if(isset($_POST["przejdz_do_edycji"]))
        {
            $ID_osoby = $_POST['przejdz_do_edycji'];
            $_SESSION['przejdz_do_edycji'] = $ID_osoby; // Przypisanie wartości do zmiennej sesji    
            przejscie_do_edycji_czytelnik($connected);
        }
        else if(isset($_POST["edytuj"]))
            {
                if($_POST["nowe_imie"] == null || $_POST["nowe_nazwisko"] == null)
                {
                    echo '<div class = "komunikaty"> Musisz podac imie i nazwisko </div>';
                }
                else
                {
                    edytuj_czytelnika($connected);
                }
            }
    ?>
    
</body>
</html>