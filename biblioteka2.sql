-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 16 Wrz 2023, 23:55
-- Wersja serwera: 10.4.27-MariaDB
-- Wersja PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `biblioteka2`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `gatunki`
--

CREATE TABLE `gatunki` (
  `ID` int(11) NOT NULL,
  `Gatunek` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `gatunki`
--

INSERT INTO `gatunki` (`ID`, `Gatunek`) VALUES
(1, 'Powieść'),
(2, 'Dramat');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ksiazka`
--

CREATE TABLE `ksiazka` (
  `ID` int(11) NOT NULL,
  `Tytul` varchar(30) NOT NULL,
  `ID_autor` int(11) NOT NULL,
  `ID_gatunek` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `ksiazka`
--

INSERT INTO `ksiazka` (`ID`, `Tytul`, `ID_autor`, `ID_gatunek`) VALUES
(1, 'Harry Potter', 1, 1),
(6, 'tytul', 2, 2),
(12, '321', 1, 1),
(13, 'xyz', 4, 2),
(14, 'qwe', 3, 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `osoba`
--

CREATE TABLE `osoba` (
  `ID` int(11) NOT NULL,
  `Imie` varchar(30) NOT NULL,
  `Nazwisko` varchar(30) NOT NULL,
  `Status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `osoba`
--

INSERT INTO `osoba` (`ID`, `Imie`, `Nazwisko`, `Status`) VALUES
(1, 'Iza', 'Mazur2', 'Autor'),
(2, 'Marek', 'Kowalski', 'Autor'),
(3, 'Izabela', 'Mazur', 'Autor'),
(8, '123', '123', 'Czytelnik');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wypozyczone`
--

CREATE TABLE `wypozyczone` (
  `ID` int(11) NOT NULL,
  `ID_ksiazki` int(11) NOT NULL,
  `ID_czytelnika` int(11) NOT NULL,
  `Data_wypozyczenia` date NOT NULL,
  `Data_zwrotu` date NOT NULL,
  `Zalega` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `wypozyczone`
--

INSERT INTO `wypozyczone` (`ID`, `ID_ksiazki`, `ID_czytelnika`, `Data_wypozyczenia`, `Data_zwrotu`, `Zalega`) VALUES
(7, 1, 8, '2023-06-14', '2023-07-14', 0),
(8, 6, 8, '2023-06-14', '2023-07-14', 0);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `gatunki`
--
ALTER TABLE `gatunki`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `ksiazka`
--
ALTER TABLE `ksiazka`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `osoba`
--
ALTER TABLE `osoba`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `wypozyczone`
--
ALTER TABLE `wypozyczone`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `gatunki`
--
ALTER TABLE `gatunki`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `ksiazka`
--
ALTER TABLE `ksiazka`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT dla tabeli `osoba`
--
ALTER TABLE `osoba`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT dla tabeli `wypozyczone`
--
ALTER TABLE `wypozyczone`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
