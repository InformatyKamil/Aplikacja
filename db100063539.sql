-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 193.218.152.241
-- Czas generowania: 21 Mar 2022, 11:53
-- Wersja serwera: 5.7.31-34-log
-- Wersja PHP: 7.1.33-17+0~20200807.39+debian9~1.gbp032d47

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `db100063539`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pracownicy`
--

CREATE TABLE `pracownicy` (
  `id_pracownik` int(11) NOT NULL,
  `nazwisko` varchar(100) DEFAULT NULL,
  `imie` varchar(100) DEFAULT NULL,
  `stanowisko` varchar(100) DEFAULT NULL,
  `login` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `pracownicy`
--

INSERT INTO `pracownicy` (`id_pracownik`, `nazwisko`, `imie`, `stanowisko`, `login`, `password`) VALUES
(1, 'Zawadzki', 'Mateusz', 'kierownik', 'zawadzki', 'zawadzki'),
(2, 'Puszkowy', 'Adam', 'tester', 'puszkowy', 'puszkowy'),
(3, 'Tom', 'Tom', 'tester', 'tom', 'tomeczek'),
(4, 'Robert', 'Robert', 'kierownik', 'robert', 'robert');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `terminarz`
--

CREATE TABLE `terminarz` (
  `terminarz` int(11) NOT NULL,
  `id_terminarz` int(11) NOT NULL,
  `id_test` int(11) DEFAULT NULL,
  `id_pracownik` int(11) DEFAULT NULL,
  `terminarz_datadodania` date DEFAULT NULL,
  `terminarz_data_uruchomienia` date DEFAULT NULL,
  `terminarz_data_zakonczenia` date DEFAULT NULL,
  `terminarz_statustestu` varchar(50) DEFAULT NULL,
  `uwagi` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `terminarz`
--

INSERT INTO `terminarz` (`terminarz`, `id_terminarz`, `id_test`, `id_pracownik`, `terminarz_datadodania`, `terminarz_data_uruchomienia`, `terminarz_data_zakonczenia`, `terminarz_statustestu`, `uwagi`) VALUES
(0, 7, 24, 2, '2022-03-17', '2022-03-18', '2022-03-25', 'W TRAKCIE', ''),
(0, 8, 24, 2, '2022-03-17', '2022-03-26', '2022-03-30', 'WYKONANE', 'awdawdawdasdasdasdasdasdasd'),
(0, 9, 24, 3, '2022-03-17', '2022-03-18', '2022-03-26', 'W TRAKCIE', ''),
(0, 10, 24, 3, '2022-03-17', '2022-03-19', '2022-03-26', 'OCZEKUJACY', ''),
(0, 11, 24, 2, '2022-03-17', '2022-03-18', '2022-03-25', 'WYKONANE', 'dupa');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `testy`
--

CREATE TABLE `testy` (
  `id_test` int(11) NOT NULL,
  `test_nazwa` varchar(100) DEFAULT NULL,
  `test_opis` text,
  `test_instrukcja` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `testy`
--

INSERT INTO `testy` (`id_test`, `test_nazwa`, `test_opis`, `test_instrukcja`) VALUES
(24, 'Sprawdzenie bibliotek', 'Sprawdz biblioteki javy', 'NaleĹźy zweryfikowaÄ wersjÄ javy 5.0');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `pracownicy`
--
ALTER TABLE `pracownicy`
  ADD PRIMARY KEY (`id_pracownik`);

--
-- Indeksy dla tabeli `terminarz`
--
ALTER TABLE `terminarz`
  ADD PRIMARY KEY (`id_terminarz`);

--
-- Indeksy dla tabeli `testy`
--
ALTER TABLE `testy`
  ADD PRIMARY KEY (`id_test`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `pracownicy`
--
ALTER TABLE `pracownicy`
  MODIFY `id_pracownik` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `terminarz`
--
ALTER TABLE `terminarz`
  MODIFY `id_terminarz` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT dla tabeli `testy`
--
ALTER TABLE `testy`
  MODIFY `id_test` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
