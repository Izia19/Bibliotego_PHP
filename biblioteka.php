<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>biblioteka</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        $connected = mysqli_connect("localhost", "root", "", "biblioteka2"); //połączenie z bazą danych
        include 'funkcje_biblioteka.php'; 
        session_start();

        echo '<nav>
            <a href = "ksiazki.php"> Ksiazki </a>
            <a href = "autorzy.php"> Autorzy </a>
            <a href = "czytelnicy.php"> Czytelnicy </a>
            <div class = "animation start-Ksiazki"> </div>
        </nav> <br> <br> <br>'; //nawigacja

        echo '<div class = "lewa">';
        echo '<form method = "post" action = "http://localhost/iza/biblioteka/biblioteka.php">
            <table>
                <tr>
                    <td> Ksiazki: <input class = "wprowadz" name = "wyszukiwarka"> </td> 
                    <td> 
                        <button type = "submit" class = "wyszukaj" name = "wyszukaj">
                            <i class="fas fa-search"> </i>
                        </button> 
                    </td>
                </tr>
                <tr>
                    <td> Tytuł: <input class = "wprowadz" name = "tytul"> </td> 
                    <td> <input class = "btn" type = "submit" name = "zwroc" value = "Zwróć"> </td>
                </tr>
            </table> <br>
            
            </form>';
                    
        echo '</div>';

        if(isset($_POST['wyszukaj']))
        {
            if($_POST["wyszukiwarka"] == null)
            {
                echo '<div class = "komunikaty"> Wpisz ksiazke ktora chcesz wyszukac </div>';
            }
            else
            {
                wyszukaj_ksiazke($connected);
            }
        }
        else if(@$_POST["wypozycz"] == true) //jesli kliknie przycisk zostaje przekierowany do innej strony
        {
            if($_POST['znacznik'] == null)
            {
                echo '<div class = "komunikaty"> Wybierz ksiazke </div>';
            }
            else
            {
                $ID_ksiazki = $_POST['znacznik'];
                $_SESSION['znacznik'] = $ID_ksiazki;
                przejscie_wypozycz($connected);
            }
        }
        else if(@$_POST["wypozycz2"] == true) //jesli kliknie przycisk zostaje przekierowany do innej strony
        {
            if($_POST['imie_czytelnika'] == null || $_POST['nazwisko_czytelnika'] == null)
            {
                echo '<div class = "komunikaty"> Podaj imie i nazwisko </div>';
            }
            else
            {
                wypozycz($connected);
            }
        }
        else if(@$_POST["zwroc"] == true) //jesli kliknie przycisk zostaje przekierowany do innej strony
        {
            if($_POST['tytul'] == null)
            {
                echo '<div class = "komunikaty"> Podaj tytul ktory chcesz zwrocic </div>';
            }
            else
            {
                zwroc($connected);
            }
        }
    ?>
    
</body>
</html>