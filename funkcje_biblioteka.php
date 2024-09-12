<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>biblioteka</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
</head>
<body>
    <?php

        echo '<div class = "prawa">';

        //ksiazki
        function dodaj_ksiazke($connected, $ID_autor) //! dodaje ksiazke
        {
            $Tytul = $_SESSION['tytul']; 
            $ID_gatunku = $_SESSION['gatunek']; //? pobiera wartosci z sesji

            $kwarenda = "SELECT ID 
            FROM ksiazka 
            WHERE Tytul = '$Tytul'";
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");   
            $sprawdz = mysqli_num_rows($wynik); 

            if($sprawdz == 0) //? sprawdza czy istnieje taka ksiazka 
            {
                $kwarenda2 = "INSERT INTO ksiazka (Tytul, ID_autor, ID_gatunek) 
                VALUES ('$Tytul','$ID_autor','$ID_gatunku')";
                mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");   
            }
            else
            {
                echo '<div class = "komunikaty"> Taka ksiazka juz istnieje </div>';
            }
            wyswietl_ksiazki($connected);
            session_destroy(); //? zamkniecie sesji
        }

        function wyszukaj_autora($connected) //! wyszukuje autorów
        {
            $Do_wyszukania = $_POST['wyszukiwarka']; //? pobiera dane z wyszukiwarki

            $kwarenda = "SELECT osoba.ID, Imie, Nazwisko 
            FROM osoba 
            WHERE Imie LIKE '%$Do_wyszukania%'
            OR Nazwisko LIKE '%$Do_wyszukania%' 
            AND Status = 'Autor'";
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");   
            $sprawdz = mysqli_num_rows($wynik);
            
            if($sprawdz > 0) //? sprawdza czy istnieje taki autor
            {
                echo '<form method = "post" action = "http://localhost/iza/biblioteka/ksiazki.php"> <table>
                <h3> Wybierz autora 
                    <button type = "submit" class = "wyszukaj" name = "powrot">
                        <i class="fas fa-times"> </i>
                    </button>
                </h3>'; //? przycisk lupa
                while($wypisz = mysqli_fetch_row($wynik))
                {    
                    echo '<tr> <td>'.$wypisz[1].' '.$wypisz[2].' <td>
                        <td> 
                            <input type = "radio" id = "znacznik_'.$wypisz[0].'" name = "znacznik" value = "'.$wypisz[0].'" />
                            <label for = "znacznik_'.$wypisz[0].'">
                                <i class = "fas fa-check"></i>
                            </label> 
                        </td>'; //? przycisk ptaszek
                } 
                echo '</tr> 
                <tr> 
                    <td> <input class = "btn" type = "submit" name = "dodaj" value = "Dodaj"> </td>
                </tr> </table> </form>';
            }
            else
            {
                echo '<div class = "komunikaty"> Nie znaleziono zadnych wyników </div>';
            }
        }

        function wyswietl_ksiazki($connected) //! wyswietla wszystkie ksiazki
        {
            $kwarenda = "SELECT Tytul, Gatunek, Imie, Nazwisko, ksiazka.ID 
            FROM osoba 
            JOIN ksiazka ON osoba.ID = ksiazka.ID_autor 
            JOIN gatunki ON ksiazka.ID_gatunek = gatunki.ID";
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");   

            echo '<form method = "post" action = "http://localhost/iza/biblioteka/ksiazki.php"> <h3> Książki </h3> 
            <table>
                <th> Tytuł </th>
                <th> Gatunek </th>
                <th> Autor </th>
                <th>
                    <button type = "submit" class = "wyszukaj" name = "powrot">
                        <i class="fas fa-times"> </i>
                    </button>
                </th>'; //? przycisk x
            while($wypisz = mysqli_fetch_row($wynik))
            {
                echo '<tr> 
                    <td>'.$wypisz[0].'</td>
                    <td>'.$wypisz[1].'</td>
                    <td>'.$wypisz[2].' '.$wypisz[3].'</td> 
                    <td> 
                        <button type = "submit" class = "wyszukaj" name = "usun"  value = '.$wypisz[4].'>
                            <i class = "fas fa-trash"> </i>
                        </button>
                    </td>'; //? przycisk smietnik
                    echo '<td>
                        <button type = "submit" class = "wyszukaj" name = "przejdz_do_edycji"  value = '.$wypisz[4].'>
                            <i class="fas fa-pen"></i>
                        </button>
                    </td>
                    
                </tr>'; //? przycisk długopis
            } 
            echo '</table> </form>';
        }

        function usun_ksiazke($connected) //! usuwa ksiazke
        {
            $ID_ksiazki = $_POST['usun'];

            $kwarenda2 = "DELETE 
            FROM ksiazka 
            WHERE ID = '$ID_ksiazki'";
            mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");  

            echo '<div class = "komunikaty"> Usunieto ksiazkę </div>';
            wyswietl_ksiazki($connected); 
        }

        function przejscie_do_edycji_ksiazka($connected) //! formularz pobierajacy nowy tytul
        {
            echo '<form method = "post" action = "http://localhost/iza/biblioteka/ksiazki.php">
                Nowy tytuł: <input class = "wprowadz" name = "nowy_tytul">
                <button type = "submit" class = "wyszukaj" name = "edytuj">
                    <i class="fas fa-pen"></i>
                </button> '; //? przycisk dlugopis
                echo '<button type = "submit" class = "wyszukaj" name = "powrot">
                    <i class="fas fa-times"> </i>
                </button> </form>';   //? przycisk x
        }

        function edytuj_ksiazke($connected) //! edytuje ksiazke
        {
            $nowy_tytul = $_POST['nowy_tytul'];
            $ID_ksiazki = $_SESSION['przejdz_do_edycji'];

            $kwarenda = "SELECT ID 
            FROM ksiazka 
            WHERE Tytul = '$nowy_tytul'";
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");  
            $sprawdz = mysqli_num_rows($wynik);

            if($sprawdz == 0) //? sprawdza czy jest juz ksiazka o takim tytule
            {
                $kwarenda2 = "UPDATE ksiazka 
                SET Tytul = '$nowy_tytul' 
                WHERE ID = '$ID_ksiazki'";
                mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");  

                echo "<div class = 'komunikaty'> Zmieniono tytuł na: ".$nowy_tytul."</div>";
            }
            else
            {
                echo '<div class = "komunikaty"> Taka ksiazka juz istnieje </div>';
            }
            wyswietl_ksiazki($connected);
        }

        function dodaj_gatunek($connected) //! dodawanie gatunkow
        {
            $Gatunek = $_POST['nowy_gatunek']; 

            $kwarenda = "SELECT ID 
            FROM gatunki 
            WHERE Gatunek = '$Gatunek'";
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");   
            $sprawdz = mysqli_num_rows($wynik); 

            if($sprawdz == 0)
            {
                $kwarenda2 = "INSERT INTO gatunki (Gatunek) VALUES  ('$Gatunek')";
                mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");

                echo '<div class = "komunikaty"> 
                <form method = "post" action = "http://localhost/iza/biblioteka/ksiazki.php">
                    Dodano gatunek: '.$Gatunek.'
                    <button type = "submit" class = "wyszukaj" name = "powrot">
                        <i class="fas fa-times">  </i>
                    </button> 
                </form> </div>'; //? przycisk x
            }
            else
            {
                echo '<div class = "komunikaty"> 
                <form method = "post" action = "http://localhost/iza/biblioteka/ksiazki.php">
                    Taki gatunek juz istnieje
                    <button type = "submit" class = "wyszukaj" name = "powrot">
                        <i class="fas fa-times">  </i>
                    </button> 
                </form> </div>'; //? przycisk x
            }

        }

        //autorzy 
        function dodaj_autora($connected) //! dodaje autorow
        {
            $Imie = $_POST['imie']; 
            $Nazwisko = $_POST['nazwisko']; 

            $kwarenda = "SELECT ID 
            FROM osoba 
            WHERE Imie = '$Imie' 
            AND Nazwisko = '$Nazwisko' 
            AND Status = 'Autor'";
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");   
            $sprawdz = mysqli_num_rows($wynik); 

            if($sprawdz == 0) //? sprawdza czy jest juz taki autor
            {
                $kwarenda2 = "INSERT 
                INTO osoba (Imie, Nazwisko, Status) 
                VALUES ('$Imie','$Nazwisko','Autor')";
                mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");   
            }
            else
            {
                echo '<div class = "komunikaty"> Taki autor juz istnieje </div>';
            }
            wyswietl_autorow($connected);
        }

        function wyswietl_autorow($connected) //! wyswietla autorow
        {
            $kwarenda = "SELECT Imie, Nazwisko, ID 
            FROM osoba 
            WHERE Status = 'Autor'";
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");   

            echo '<form method = "post" action = "http://localhost/iza/biblioteka/autorzy.php"> 
            <h3> Autorzy 
                <button type = "submit" class = "wyszukaj" name = "powrot">
                    <i class="fas fa-times"> </i>
                </button>
            </h3> 
            <table>'; //? przycisk x

            while($wypisz = mysqli_fetch_row($wynik))
            {
                echo '<tr> 
                    <td>'.$wypisz[0].' '.$wypisz[1].'</td> 
                    <td> 
                        <button type = "submit" class = "wyszukaj" name = "usun"  value = '.$wypisz[2].'>
                            <i class = "fas fa-trash"> </i>
                        </button>'; //? przycisk smietnik
                    echo '</td> 
                    <td>
                        <button type = "submit" class = "wyszukaj" name = "przejdz_do_edycji"  value = '.$wypisz[2].'>
                            <i class="fas fa-pen"></i>
                        </button>'; //? przycisk dlugopis
                    echo' </td>
                </tr>';
            } 
            echo '</table> </form>';
        }

        function usun_autora($connected) //! usuwa autorow
        {
            $ID_osoby = $_POST['usun'];

            $kwarenda2 = "DELETE 
            FROM osoba 
            WHERE ID = '$ID_osoby'";
            mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");  

            echo '<div class = "komunikaty"> Usunieto autora </div>';
            wyswietl_autorow($connected); 
        }

        function przejscie_do_edycji_autor($connected) //! formularz pobierajacy nowe imie i nazwisko 
        {
            echo '<form method = "post" action = "http://localhost/iza/biblioteka/autorzy.php">
                Nowe imie: <input class = "wprowadz" name = "nowe_imie">
                Nowe nazwisko: <input class = "wprowadz" name = "nowe_nazwisko">
                <button type = "submit" class = "wyszukaj" name = "edytuj">
                    <i class="fas fa-pen"> </i>
                </button>'; //? przycisk dlugopis
                echo '<button type = "submit" class = "wyszukaj" name = "powrot">
                    <i class="fas fa-times"> </i>
                </button> </form>'; //? przycisk x
        }

        function edytuj_autora($connected) //! edytuje autora
        {
            $nowe_imie = $_POST['nowe_imie'];
            $nowe_nazwisko = $_POST['nowe_nazwisko'];
            $ID_osoby = $_SESSION['przejdz_do_edycji'];

            $kwarenda = "SELECT ID 
            FROM osoba 
            WHERE Imie = '$nowe_imie' 
            AND Nazwisko = '$nowe_nazwisko' 
            AND Status = 'Autor'";
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");  
            $sprawdz = mysqli_num_rows($wynik);

            if($sprawdz == 0) //? sprawdza czy jest juz taki autor
            {
                $kwarenda2 = "UPDATE osoba 
                SET Imie = '$nowe_imie', Nazwisko = '$nowe_nazwisko' 
                WHERE ID = '$ID_osoby'";
                mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");  

                echo "<div class = 'komunikaty'> Zmieniono imie i nazwisko na: ".$nowe_imie." ".$nowe_nazwisko."</div>";
            }
            else
            {
                echo '<div class = "komunikaty"> Taki autor juz istnieje </div>';
            }
            wyswietl_autorow($connected);
        }


        //czytelnicy

        function dodaj_czytelnika($connected) //! dodawanie czytelnikow
        {
            $Imie = $_POST['imie']; 
            $Nazwisko = $_POST['nazwisko']; 

            $kwarenda = "SELECT ID 
            FROM osoba 
            WHERE Imie = '$Imie' 
            AND Nazwisko = '$Nazwisko' 
            AND Status = 'Czytelnik'";
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");   
            $sprawdz = mysqli_num_rows($wynik); 

            if($sprawdz == 0) //? sprawdza czy jest juz taki czytelnik
            {
                $kwarenda2 = "INSERT INTO osoba (Imie, Nazwisko, Status)
                VALUES ('$Imie','$Nazwisko','Czytelnik')";
                mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");   
            }
            else
            {
                echo '<div class = "komunikaty"> Taki czytelnik juz istnieje </div>';
            }
            wyswietl_czytelnikow($connected);
        }

        function wyswietl_czytelnikow($connected) //! wyswietla czytelnikow
        {
            $kwarenda = "SELECT Imie, Nazwisko, ID FROM osoba WHERE Status = 'Czytelnik'";
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");   

            echo '<form method = "post" action = "http://localhost/iza/biblioteka/czytelnicy.php"> 
            <h3> Czytelnicy 
                <button type = "submit" class = "wyszukaj" name = "powrot">
                    <i class="fas fa-times"> </i>
                </button>
            </h3> 
            <table>'; //? przycisk x
            while($wypisz = mysqli_fetch_row($wynik))
            {
                echo '<tr> 
                    <td>'.$wypisz[0].' '.$wypisz[1].'</td> 
                    <td> 
                        <button type = "submit" class = "wyszukaj" name = "usun"  value = '.$wypisz[2].'>
                            <i class = "fas fa-trash"> </i>
                        </button>'; //? przycisk smietnik
                    echo '</td> 
                    <td>
                        <button type = "submit" class = "wyszukaj" name = "przejdz_do_edycji"  value = '.$wypisz[2].'>
                            <i class="fas fa-pen"></i>
                        </button>
                    </td>
                </tr>'; //? przycisk dlugopis
            } 
            echo '</table> </form>';
        }

        function usun_czytelnika($connected) //! usuwa czytelnika
        {
            $ID_osoby = $_POST['usun'];

            $kwarenda2 = "DELETE 
            FROM osoba 
            WHERE ID = '$ID_osoby'";
            mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");  

            echo '<div class = "komunikaty"> Usunieto czytelnika </div>';
            wyswietl_czytelnikow($connected); 
        }

        function przejscie_do_edycji_czytelnik($connected) //! formularz pobierajacy nowy imie i nazwisko
        {
            echo '<form method = "post" action = "http://localhost/iza/biblioteka/czytelnicy.php">
                Nowe imie: <input class = "wprowadz" name = "nowe_imie">
                Nowe nazwisko: <input class = "wprowadz" name = "nowe_nazwisko">
                <button type = "submit" class = "wyszukaj" name = "edytuj">
                    <i class="fas fa-pen"> </i>
                </button>'; //? przycisk dlugopis
                echo '<button type = "submit" class = "wyszukaj" name = "powrot">
                    <i class="fas fa-times"> </i>
                </button> </form>'; //? przycisk x
        }

        function edytuj_czytelnika($connected) //! edytuje czytelnika
        {
            $nowe_imie = $_POST['nowe_imie'];
            $nowe_nazwisko = $_POST['nowe_nazwisko'];
            $ID_osoby = $_SESSION['przejdz_do_edycji'];

            $kwarenda = "SELECT ID 
            FROM osoba 
            WHERE Imie = '$nowe_imie' 
            AND Nazwisko = '$nowe_nazwisko'
            AND Status = 'Czytelnik'";
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");  
            $sprawdz = mysqli_num_rows($wynik);

            if($sprawdz == 0) //? sprawdza czy jest juz taki czytelnik
            {
                $kwarenda2 = "UPDATE osoba 
                SET Imie = '$nowe_imie', Nazwisko = '$nowe_nazwisko' 
                WHERE ID = '$ID_osoby'";
                mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");  

                echo "<div class = 'komunikaty'> Zmieniono imie i nazwisko na: ".$nowe_imie." ".$nowe_nazwisko."</div>";
            }
            else
            {
                echo '<div class = "komunikaty"> Taki Czytelnik juz istnieje </div>';
            }
            wyswietl_czytelnikow($connected);
        }

        function wyswietl_wypozyczone_ksiazki($connected) //! wyświetla książki wypożyczone przez czytelnika
        {
            $Imie = $_POST['imie']; 
            $Nazwisko = $_POST['nazwisko']; 

            $kwarenda = "SELECT Tytul FROM osoba JOIN wypozyczone ON osoba.ID = wypozyczone.ID_czytelnika JOIN ksiazka ON ksiazka.ID = wypozyczone.ID_ksiazki WHERE Status = 'Czytelnik' AND Imie = '$Imie' AND Nazwisko = '$Nazwisko'";
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");   

            echo '<form method = "post" action = "http://localhost/iza/biblioteka/czytelnicy.php"> 
                <h3> Ksiażki czytelnika '.$Imie.' '.$Nazwisko.'
                    <button type = "submit" class = "wyszukaj" name = "powrot">
                        <i class="fas fa-times"> </i>
                    </button>
                </h3> <table>';
            while($wypisz = mysqli_fetch_row($wynik))
            {
                echo '<tr> 
                    <td>'.$wypisz[0].'</td> 
                </tr>';
            }
            echo '</table> </form>';
        }

        //biblioteka

        function wyszukaj_ksiazke($connected) //! wyszukuje ksiazek
        {
            $Do_wyszukania = $_POST['wyszukiwarka'];

            $kwarenda = "SELECT ksiazka.ID, Tytul, Imie, Nazwisko 
            FROM osoba 
            JOIN ksiazka ON ksiazka.ID_autor = osoba.ID 
            WHERE Tytul LIKE '%$Do_wyszukania%'";
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");   
            $sprawdz = mysqli_num_rows($wynik);
            
            if($sprawdz > 0) //? sprawdza czy jest taka ksiazka
            {
                echo '<form method = "post" action = "http://localhost/iza/biblioteka/biblioteka.php"> <table>
                <h3> Wybierz autora 
                    <button type = "submit" class = "wyszukaj" name = "powrot">
                        <i class="fas fa-times"> </i>
                    </button>'; //? przycisk x
                echo '</h3>
                <tr>
                    <th> Tytul </th>
                    <th> Autor </th>
                </tr>';
                while($wypisz = mysqli_fetch_row($wynik))
                {    
                    $kwarenda2 = "SELECT ksiazka.ID, Tytul, Imie, Nazwisko 
                    FROM osoba 
                    JOIN ksiazka ON ksiazka.ID_autor = osoba.ID 
                    JOIN wypozyczone ON ksiazka.ID = wypozyczone.ID_ksiazki
                    WHERE ksiazka.ID = '$wypisz[0]'";
                    $wynik2 = mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");  
                    $sprawdz2 = mysqli_num_rows($wynik2); 

                    if($sprawdz2 > 0)  //? sprawdza czy ta ksiazka jest wypozyczona
                    {
                        while($wypisz2 = mysqli_fetch_row($wynik2))
                        { 
                            echo '<tr style = "color: red"> 
                            <td>'.$wypisz2[1].'</td>
                            <td>'.$wypisz2[2].' '.$wypisz2[3].' </td>
                        </tr>';
                        } //? jesli jest wypozyczona jest podswietlana na czerwono
                    }
                    else
                    {
                        echo '<tr> 
                            <td>'.$wypisz[1].'</td>
                            <td>'.$wypisz[2].' '.$wypisz[3].' </td>
                            <td> 
                                <input type = "radio" id = "znacznik_'.$wypisz[0].'" name = "znacznik" value = "'.$wypisz[0].'" />
                                <label for = "znacznik_'.$wypisz[0].'">
                                    <i class = "fas fa-check"></i>
                                </label>'; //? przycisk ptaszek
                            echo '</td> 
                        </tr>';
                    }
                    
                } 
                echo '
                <tr> 
                    <td> <input class = "btn" type = "submit" name = "wypozycz" value = "Wypożycz"> </td>
                </tr> </table> </form>';
            }
            else
            {
                echo '<div class = "komunikaty"> Nie znaleziono zadnych wyników </div>';
            }
        }

        function przejscie_wypozycz($connected) //! formularz pobierajacy imie i nazwisko czytelnika
        {
            echo '<form method = "post" action = "http://localhost/iza/biblioteka/biblioteka.php"> 
                <h3> Czytelnik </h3>
                Imie <input class = "wprowadz" name = "imie_czytelnika">
                Nazwisko <input class = "wprowadz" name = "nazwisko_czytelnika">
                <input class = "btn" type = "submit" name = "wypozycz2" value = "Wypożycz"> 
                </form>';       
        }

        function wypozycz($connected) //! wypozyczenie ksiazki
        {
            $Imie = $_POST['imie_czytelnika'];
            $Nazwisko = $_POST['nazwisko_czytelnika'];
            $ID_ksiazki = $_SESSION['znacznik'];

            $kwarenda = "SELECT ID 
            FROM osoba 
            WHERE Imie = '$Imie' 
            AND Nazwisko = '$Nazwisko' 
            AND Status = 'Czytelnik'";
            $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");   
            $sprawdz = mysqli_num_rows($wynik);
            $wypisz = mysqli_fetch_row($wynik);
            $ID_czytelnika = $wypisz[0];

            if($sprawdz == 1) //? sprawdza czy jest taki czytelnik
            {
                $data_wypozyczenia = date('Y-m-d'); //? pobiera obecna date 
                $data_zwrotu = date('Y-m-d', strtotime($data_wypozyczenia . ' +30 days')); //? dodaje do obcenej daty 30 dni
                $dzien_tygodnia = date('N', strtotime($data_zwrotu)); //? sprawdza dzien tygodnia nowej daty

                if($dzien_tygodnia == 6) //? jesli dzien tygodnia to sobota
                {
                    $data_zwrotu = date('Y-m-d', strtotime($data_zwrotu . ' -1 days'));
                }
                if($dzien_tygodnia == 7) //? jesli dzien tygodnia to niedziela
                {
                    $data_zwrotu = date('Y-m-d', strtotime($data_zwrotu . ' -2 days'));
                }

                $kwarenda2 = "INSERT INTO wypozyczone (ID_ksiazki, ID_czytelnika, Data_wypozyczenia, Data_zwrotu, Zalega) 
                VALUES ('$ID_ksiazki','$ID_czytelnika','$data_wypozyczenia','$data_zwrotu','0')";
                mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");   
            }
            else
            {
                echo '<div class = "komunikaty"> Nie ma takiego czytelnika </div>';
            }
        }

        function zwroc($connected) //! zwrot ksiazki
        {
            $Tytul = $_POST['tytul'];

            $kwarenda = "SELECT wypozyczone.ID 
            FROM ksiazka 
            JOIN wypozyczone ON ksiazka.ID = wypozyczone.ID_ksiazki 
            WHERE Tytul = '$Tytul'";
            $wynik = mysqli_query($connected, $kwarenda);
            $sprawdz = mysqli_num_rows($wynik);

            if($sprawdz == 1) //? sprawdza czy ta ksiazka jest wypozyczona
            {
                $wypisz = mysqli_fetch_row($wynik);
                $ID_wypozyczenia = $wypisz[0];

                $kwarenda = "SELECT Data_zwrotu 
                FROM wypozyczone 
                JOIN ksiazka ON wypozyczone.ID_ksiazki = ksiazka.ID 
                WHERE Tytul = '$Tytul'";
                $wynik = mysqli_query($connected, $kwarenda) or die("Problemy z odczytem danych!");   
                $wypisz = mysqli_fetch_row($wynik);

                $data_zwrotu = DateTime::createFromFormat('Y-m-d', $wypisz[0]); //? pobiera date zwrotu z tabeli i konwertuje ja na dataTime
                $obecna_data = new DateTime(); //? pobiera obecna date

                if($data_zwrotu < $obecna_data) //? porownuje daty
                {
                    $interval = $data_zwrotu->diff($obecna_data);
                    $roznica_dni = $interval->days; //? oblicza roznice dni
                    $do_zaplaty = $roznica_dni * 10; //? liczy ile trzeba zaplacic za zwłoke

                    echo "Do zapłaty ".$do_zaplaty." zł";
                }
                else 
                {
                    $kwarenda2 = "DELETE 
                    FROM wypozyczone 
                    WHERE ID = '$ID_wypozyczenia'";
                    mysqli_query($connected, $kwarenda2) or die("Problemy z odczytem danych!");  
                    
                    echo '<div class = "komunikaty"> Zwróciłes ksiazke: '.$Tytul.'</div>';
                }
             
            }
            else
            {
                echo '<div class = "komunikaty"> Taka ksiazka nie istnieje lub nie jest wypozyczona </div>';
            }
        }

        echo '</div>';
    ?>
    
</body>
</html>