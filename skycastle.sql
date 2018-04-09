-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2018. Ápr 09. 14:30
-- Kiszolgáló verziója: 10.1.21-MariaDB
-- PHP verzió: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `skycastle`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `abilities`
--

CREATE TABLE `abilities` (
  `kulcs` int(11) NOT NULL,
  `abilityname` varchar(100) DEFAULT NULL,
  `abilitytype` varchar(100) DEFAULT NULL,
  `aktivkor` varchar(100) DEFAULT NULL,
  `aktivkorinc` varchar(100) DEFAULT NULL,
  `ertek` varchar(100) DEFAULT NULL,
  `ertekinc` varchar(100) DEFAULT NULL,
  `reload` varchar(100) DEFAULT NULL,
  `reloadinc` varchar(100) DEFAULT NULL,
  `manausage` varchar(100) DEFAULT NULL,
  `maxlevel` varchar(100) DEFAULT NULL,
  `ownerkaszt` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `abilities`
--

INSERT INTO `abilities` (`kulcs`, `abilityname`, `abilitytype`, `aktivkor`, `aktivkorinc`, `ertek`, `ertekinc`, `reload`, `reloadinc`, `manausage`, `maxlevel`, `ownerkaszt`) VALUES
(3, 'autotarget', 'passziv', '1', '0', '100', '100', '0', '0', '0', '10', 'warrior'),
(4, 'disability', 'ability0', '1', '1', '0', '0', '20', '1', '300', '5', 'warrior'),
(5, 'shieldleech', 'ability1', '1', '1', '10', '10', '20', '1', '200', '5', 'warrior'),
(6, 'firstattack', 'passziv', '1', '0', '500', '100', '0', '0', '0', '5', 'mage'),
(7, 'dodge', 'ability0', '1', '1', '100', '100', '20', '1', '200', '5', 'mage'),
(8, 'droptarget', 'ability1', '1', '0', '0', '0', '20', '1', '100', '10', 'mage'),
(9, 'dmgreduction', 'passziv', '1', '1', '5', '5', '0', '0', '0', '5', 'paladin'),
(10, 'invulnerable', 'ability0', '1', '1', '30', '5', '20', '1', '300', '5', 'paladin'),
(11, 'gettarget', 'ability1', '1', '0', '0', '0', '25', '1', '500', '10', 'paladin'),
(12, 'manaregen', 'passziv', '1', '0', '10', '10', '0', '0', '0', '10', 'healer'),
(13, 'pajzstolt', 'ability0', '1', '0', '20000', '20000', '15', '1', '400', '10', 'healer'),
(14, 'hptolt', 'ability1', '1', '0', '10000', '10000', '15', '1', '400', '10', 'healer');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `battle`
--

CREATE TABLE `battle` (
  `kulcs` int(11) NOT NULL,
  `karakterazonosito` varchar(100) NOT NULL,
  `target` varchar(100) DEFAULT NULL,
  `ammo` varchar(100) DEFAULT NULL,
  `attack` varchar(100) NOT NULL,
  `dmgreceived` varchar(100) NOT NULL,
  `attacked` varchar(100) NOT NULL,
  `lastattack` varchar(100) NOT NULL,
  `disability` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `costs`
--

CREATE TABLE `costs` (
  `kulcs` int(11) NOT NULL,
  `item` varchar(100) DEFAULT NULL,
  `itemtype` varchar(100) DEFAULT NULL,
  `cost` varchar(100) DEFAULT NULL,
  `karaktermegf` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `costs`
--

INSERT INTO `costs` (`kulcs`, `item`, `itemtype`, `cost`, `karaktermegf`) VALUES
(1, 'pajzs', 'felszereles', '200000', 'pajzs'),
(2, 'darda', 'felszereles', '75000', 'darda'),
(3, 'pancel', 'felszereles', '75000', 'pancel'),
(4, 'kotszer', 'felszereles', '100000', 'kotszer'),
(5, 'phalanx', 'felszereles', '100000', 'phalanx'),
(6, 'kard', 'felszereles', '100000', 'kard'),
(7, 'manakristaly', 'felszereles', '150000', 'manakristaly'),
(8, 'hppot', 'potion', '2500', 'hppotnum'),
(9, 'shieldpot', 'potion', '2500', 'shieldpotnum'),
(10, 'manapot', 'potion', '2500', 'manapotnum'),
(11, 'x2', 'ammo', '100', 'x2num'),
(12, 'x3', 'ammo', '200', 'x3num'),
(13, 'x4', 'ammo', '500', 'x4num'),
(14, 'emp', 'extra', '2500', 'empnum'),
(15, 'ish', 'extra', '2500', 'ishnum'),
(16, 'pld', 'extra', '1000', 'pldnum'),
(17, 'cloak', 'extra', '1000', 'cloaknum'),
(18, 'hpbooster', 'booster', '20000', 'hpboosterrounds'),
(19, 'shieldbooster', 'booster', '20000', 'shieldboosterrounds'),
(20, 'dmgbooster', 'booster', '20000', 'dmgboosterrounds'),
(21, 'manabooster', 'booster', '20000', 'manaboosterrounds'),
(22, 'attackbooster', 'booster', '20000', 'attackboosterrounds'),
(23, 'accurbooster', 'booster', '20000', 'accurboosterrounds'),
(24, 'autotarget', 'ability', '15000', 'autotarget'),
(25, 'disability', 'ability', '25000', 'disability'),
(26, 'shieldleech', 'ability', '30000', 'shieldleech'),
(27, 'firstattack', 'ability', '10000', 'firstattack'),
(28, 'dodge', 'ability', '20000', 'dodge'),
(29, 'droptarget', 'ability', '30000', 'droptarget'),
(30, 'dmgreduction', 'ability', '20000', 'dmgreduction'),
(31, 'invulnerable', 'ability', '30000', 'invulnerable'),
(32, 'gettarget', 'ability', '15000', 'gettarget'),
(33, 'manaregen', 'ability', '20000', 'manaregen'),
(34, 'hptolt', 'ability', '30000', 'hptolt'),
(35, 'pajzstolt', 'ability', '30000', 'pajzstolt'),
(36, 'szinthp', 'stat', '25000', 'szinthp'),
(37, 'szintshield', 'stat', '25000', 'szintshield'),
(38, 'szintpenet', 'stat', '25000', 'szintpenet'),
(39, 'szintdmg', 'stat', '25000', 'szintdmg'),
(40, 'szintmana', 'stat', '25000', 'szintmana');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `events`
--

CREATE TABLE `events` (
  `kulcs` int(11) NOT NULL,
  `eentname` varchar(100) DEFAULT NULL,
  `eventtype` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `events`
--

INSERT INTO `events` (`kulcs`, `eentname`, `eventtype`) VALUES
(1, 'ammobox', 'bonusbox'),
(2, 'boostbox', 'bonusbox'),
(3, 'extrabox', 'bonusbox'),
(4, 'moneybox', 'bonusbox'),
(5, 'defensesone', 'station'),
(6, 'hpstation', 'station'),
(7, 'manastation', 'station'),
(8, 'shieldstation', 'station'),
(9, 'store', 'store');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `felszereles`
--

CREATE TABLE `felszereles` (
  `kulcs` int(11) NOT NULL,
  `felszerelesnev` varchar(100) DEFAULT NULL,
  `hpbonus` varchar(100) DEFAULT NULL,
  `shieldbonus` varchar(100) DEFAULT NULL,
  `penetbonus` varchar(100) DEFAULT NULL,
  `dmgbonus` varchar(100) DEFAULT NULL,
  `roundshield` varchar(100) DEFAULT NULL,
  `manabonus` varchar(100) DEFAULT NULL,
  `priority` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `felszereles`
--

INSERT INTO `felszereles` (`kulcs`, `felszerelesnev`, `hpbonus`, `shieldbonus`, `penetbonus`, `dmgbonus`, `roundshield`, `manabonus`, `priority`) VALUES
(1, 'pajzs', '100', '200', '0', '70', '0', '100', '1'),
(3, 'kotszer', '70', '100', '0', '100', '5', '100', '6'),
(4, 'phalanx', '100', '100', '20', '80', '0', '100', '5'),
(5, 'kard', '100', '80', '0', '120', '0', '100', '4\r\n'),
(6, 'manakristaly', '100', '100', '0', '70', '0', '150', '2'),
(7, 'alap', '100', '100', '0', '100', '0', '100', '7'),
(8, 'pancel', '120', '110', '0', '95', '0', '100', '3');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `kasztok`
--

CREATE TABLE `kasztok` (
  `kulcs` int(11) NOT NULL,
  `kasztnev` varchar(100) DEFAULT NULL,
  `basehp` varchar(100) DEFAULT NULL,
  `hpinc` varchar(100) DEFAULT NULL,
  `baseshield` varchar(100) DEFAULT NULL,
  `shieldinc` varchar(100) DEFAULT NULL,
  `basepenet` varchar(100) DEFAULT NULL,
  `penetinc` varchar(100) DEFAULT NULL,
  `basedmg` varchar(100) DEFAULT NULL,
  `dmginc` varchar(100) DEFAULT NULL,
  `damagemultiplier` varchar(100) DEFAULT NULL,
  `basemana` varchar(100) DEFAULT NULL,
  `manainc` varchar(100) DEFAULT NULL,
  `passziv` varchar(100) DEFAULT NULL,
  `ability0` varchar(100) DEFAULT NULL,
  `ability1` varchar(100) DEFAULT NULL,
  `maxpenet` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `kasztok`
--

INSERT INTO `kasztok` (`kulcs`, `kasztnev`, `basehp`, `hpinc`, `baseshield`, `shieldinc`, `basepenet`, `penetinc`, `basedmg`, `dmginc`, `damagemultiplier`, `basemana`, `manainc`, `passziv`, `ability0`, `ability1`, `maxpenet`) VALUES
(1, 'warrior', '50000', '10000', '100000', '20000', '70', '5', '5000', '1000', '2', '500', '50', 'autotarget', 'disability', 'shieldleech', '4'),
(2, 'mage', '25000', '5000', '50000', '10000', '70', '5', '5000', '1000', '3', '1500', '150', 'firstattack', 'dodge', 'droptarget', '4'),
(3, 'paladin', '75000', '15000', '150000', '25000', '70', '5', '3000', '600', '2', '1000', '100', 'dmgreduction', 'invulnerable', 'gettarget', '4'),
(4, 'healer', '50000', '10000', '100000', '20000', '70', '5', '4000', '800', '2', '1000', '100', 'manaregen', 'hptolt', 'pajzstolt', '4');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `mobs`
--

CREATE TABLE `mobs` (
  `kulcs` int(11) NOT NULL,
  `mobname` varchar(100) DEFAULT NULL,
  `agressive` varchar(100) DEFAULT NULL,
  `basichp` varchar(100) DEFAULT NULL,
  `hpinc` varchar(100) DEFAULT NULL,
  `basicshield` varchar(100) DEFAULT NULL,
  `shieldinc` varchar(100) DEFAULT NULL,
  `ertekpenet` varchar(100) DEFAULT NULL,
  `basicdmg` varchar(100) DEFAULT NULL,
  `dmginc` varchar(100) DEFAULT NULL,
  `dmgmultiplierlevel` varchar(100) DEFAULT NULL,
  `basicreward` varchar(100) DEFAULT NULL,
  `rewardinc` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `mobs`
--

INSERT INTO `mobs` (`kulcs`, `mobname`, `agressive`, `basichp`, `hpinc`, `basicshield`, `shieldinc`, `ertekpenet`, `basicdmg`, `dmginc`, `dmgmultiplierlevel`, `basicreward`, `rewardinc`) VALUES
(1, 'Biloxass', '1', '5000', '1000', '10000', '2000', '70', '1000', '200', '2', '500', '100'),
(2, 'Epuregon', '1', '10000', '2000', '20000', '4000', '75', '1500', '300', '2', '1000', '150'),
(3, 'Gordanir', '1', '20000', '4000', '40000', '8000', '80', '2500', '400', '2', '2000', '250'),
(4, 'Naman', '1', '35000', '6000', '80000', '15000', '85', '4000', '600', '2', '3500', '400'),
(5, 'Nazer', '1', '55000', '10000', '130000', '20000', '90', '6000', '900', '2', '5500', '600'),
(6, 'Nuban', '1', '80000', '15000', '200000', '30000', '90', '8500', '1400', '2', '8000', '850'),
(7, 'Suder', '0', '120000', '20000', '350000', '50000', '90', '11500', '2000', '2', '11000', '1150'),
(8, 'Talsub', '0', '165000', '30000', '500000', '75000', '90', '16000', '2800', '2', '14500', '1400'),
(9, 'Upidynyx', '0', '215000', '40000', '800000', '110000', '90', '21000', '3800', '2', '19500', '1800'),
(10, 'Zumber', '1', '270000', '50000', '1000000', '150000', '90', '30000', '50000', '2', '25000', '2500');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `npcs`
--

CREATE TABLE `npcs` (
  `kulcs` int(11) NOT NULL,
  `karakterazonosito` varchar(100) DEFAULT NULL,
  `bot` varchar(100) DEFAULT NULL,
  `kaszt` varchar(100) DEFAULT NULL,
  `karakterszint` varchar(100) DEFAULT NULL,
  `enemy` varchar(100) DEFAULT NULL,
  `actualmoney` varchar(100) DEFAULT NULL,
  `szinthp` varchar(100) DEFAULT NULL,
  `basichp` varchar(100) DEFAULT NULL,
  `hpboosterrounds` varchar(100) DEFAULT NULL,
  `hpfelszbonus` varchar(100) DEFAULT NULL,
  `maxhp` varchar(100) DEFAULT NULL,
  `actualhp` varchar(100) DEFAULT NULL,
  `szintshield` varchar(100) DEFAULT NULL,
  `basicshield` varchar(100) DEFAULT NULL,
  `shieldboosterrounds` varchar(100) DEFAULT NULL,
  `shieldfelszbonus` varchar(100) DEFAULT NULL,
  `maxshield` varchar(100) DEFAULT NULL,
  `actualshield` varchar(100) DEFAULT NULL,
  `szintpenet` varchar(100) DEFAULT NULL,
  `penetfelszbonus` varchar(100) DEFAULT NULL,
  `ertekpenet` varchar(100) DEFAULT NULL,
  `szintmana` varchar(100) DEFAULT NULL,
  `basicmana` varchar(100) DEFAULT NULL,
  `manaboosterrounds` varchar(100) DEFAULT NULL,
  `manafelszbonus` varchar(100) DEFAULT NULL,
  `maxmana` varchar(100) DEFAULT NULL,
  `actualmana` varchar(100) DEFAULT NULL,
  `szintdmg` varchar(100) DEFAULT NULL,
  `basicdmg` varchar(100) DEFAULT NULL,
  `dmgboosterrounds` varchar(100) DEFAULT NULL,
  `dmgfelszbonus` varchar(100) DEFAULT NULL,
  `dmgmultiplierlevel` varchar(100) DEFAULT NULL,
  `actualdmg` varchar(100) DEFAULT NULL,
  `attackboosterrounds` varchar(100) DEFAULT NULL,
  `accurboosterrounds` varchar(100) DEFAULT NULL,
  `activefelszereles` varchar(100) DEFAULT NULL,
  `szintpassziv` varchar(100) DEFAULT NULL,
  `szintability0` varchar(100) DEFAULT NULL,
  `activeability0` varchar(100) DEFAULT NULL,
  `reloadability0` varchar(100) DEFAULT NULL,
  `szintability1` varchar(100) DEFAULT NULL,
  `activeability1` varchar(100) DEFAULT NULL,
  `reloadability1` varchar(100) DEFAULT NULL,
  `hppotnum` varchar(100) DEFAULT NULL,
  `hppotactive` varchar(100) DEFAULT NULL,
  `hppotreload` varchar(100) DEFAULT NULL,
  `shieldpotnum` varchar(100) DEFAULT NULL,
  `shieldpotactive` varchar(100) DEFAULT NULL,
  `shieldpotreload` varchar(100) DEFAULT NULL,
  `manapotnum` varchar(100) DEFAULT NULL,
  `manapotactive` varchar(100) DEFAULT NULL,
  `manapotreload` varchar(100) DEFAULT NULL,
  `x2num` varchar(100) DEFAULT NULL,
  `x3num` varchar(100) DEFAULT NULL,
  `x4num` varchar(100) DEFAULT NULL,
  `empnum` varchar(100) DEFAULT NULL,
  `empreload` varchar(100) DEFAULT NULL,
  `ishnum` varchar(100) DEFAULT NULL,
  `ishreload` varchar(100) DEFAULT NULL,
  `pldnum` varchar(100) DEFAULT NULL,
  `pldactive` varchar(100) DEFAULT NULL,
  `pldreload` varchar(100) DEFAULT NULL,
  `cloaknum` varchar(100) DEFAULT NULL,
  `cloaked` varchar(100) DEFAULT NULL,
  `pajzs` varchar(100) DEFAULT NULL,
  `pancel` varchar(100) DEFAULT NULL,
  `kotszer` varchar(100) DEFAULT NULL,
  `phalanx` varchar(100) DEFAULT NULL,
  `kard` varchar(100) DEFAULT NULL,
  `manakristaly` varchar(100) DEFAULT NULL,
  `alap` varchar(100) NOT NULL,
  `empactive` varchar(100) NOT NULL,
  `ishactive` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `specials`
--

CREATE TABLE `specials` (
  `kulcs` int(11) NOT NULL,
  `specialname` varchar(100) DEFAULT NULL,
  `specialtype` varchar(100) DEFAULT NULL,
  `specialertek` varchar(100) DEFAULT NULL,
  `manausage` varchar(100) DEFAULT NULL,
  `aktivkor` varchar(100) DEFAULT NULL,
  `reload` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `specials`
--

INSERT INTO `specials` (`kulcs`, `specialname`, `specialtype`, `specialertek`, `manausage`, `aktivkor`, `reload`) VALUES
(1, 'hppot', 'potion', '10', '500', '5', '20'),
(2, 'shieldpot', 'potion', '10', '500', '5', '20'),
(3, 'manapot', 'potion', '10', '0', '5', '20'),
(4, 'x2', 'ammo', '2', '20', '1', '0'),
(5, 'x3', 'ammo', '3', '50', '1', '0'),
(6, 'x4', 'ammo', '4', '100', '1', '0'),
(7, 'emp', 'extra', 'droptarget', '300', '1', '20'),
(8, 'ish', 'extra', '0', '300', '1', '20'),
(9, 'pld', 'extra', '300', '200', '5', '20'),
(10, 'cloak', 'extra', '1', '0', '1', '0'),
(11, 'hpbooster', 'booster', '125', '0', '500', '0'),
(12, 'shieldbooster', 'booster', '125', '0', '500', '0'),
(13, 'dmgbooster', 'booster', '125', '0', '500', '0'),
(14, 'manabooster', 'booster', '125', '0', '500', '0'),
(15, 'attackbooster', 'booster', '250', '0', '500', '0'),
(16, 'accurbooster', 'booster', '-250', '0', '500', '0');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `kulcs` int(11) NOT NULL,
  `karakterazonosito` varchar(100) DEFAULT NULL,
  `bot` varchar(100) DEFAULT NULL,
  `kaszt` varchar(100) DEFAULT NULL,
  `karakterszint` varchar(100) DEFAULT NULL,
  `enemy` varchar(100) DEFAULT NULL,
  `actualmoney` varchar(100) DEFAULT NULL,
  `szinthp` varchar(100) DEFAULT NULL,
  `basichp` varchar(100) DEFAULT NULL,
  `hpboosterrounds` varchar(100) DEFAULT NULL,
  `hpfelszbonus` varchar(100) DEFAULT NULL,
  `maxhp` varchar(100) DEFAULT NULL,
  `actualhp` varchar(100) DEFAULT NULL,
  `szintshield` varchar(100) DEFAULT NULL,
  `basicshield` varchar(100) DEFAULT NULL,
  `shieldboosterrounds` varchar(100) DEFAULT NULL,
  `shieldfelszbonus` varchar(100) DEFAULT NULL,
  `maxshield` varchar(100) DEFAULT NULL,
  `actualshield` varchar(100) DEFAULT NULL,
  `szintpenet` varchar(100) DEFAULT NULL,
  `penetfelszbonus` varchar(100) DEFAULT NULL,
  `ertekpenet` varchar(100) DEFAULT NULL,
  `szintmana` varchar(100) DEFAULT NULL,
  `basicmana` varchar(100) DEFAULT NULL,
  `manaboosterrounds` varchar(100) DEFAULT NULL,
  `manafelszbonus` varchar(100) DEFAULT NULL,
  `maxmana` varchar(100) DEFAULT NULL,
  `actualmana` varchar(100) DEFAULT NULL,
  `szintdmg` varchar(100) DEFAULT NULL,
  `basicdmg` varchar(100) DEFAULT NULL,
  `dmgboosterrounds` varchar(100) DEFAULT NULL,
  `dmgfelszbonus` varchar(100) DEFAULT NULL,
  `dmgmultiplierlevel` varchar(100) DEFAULT NULL,
  `actualdmg` varchar(100) DEFAULT NULL,
  `attackboosterrounds` varchar(100) DEFAULT NULL,
  `accurboosterrounds` varchar(100) DEFAULT NULL,
  `activefelszereles` varchar(100) DEFAULT NULL,
  `szintpassziv` varchar(100) DEFAULT NULL,
  `szintability0` varchar(100) DEFAULT NULL,
  `activeability0` varchar(100) DEFAULT NULL,
  `reloadability0` varchar(100) DEFAULT NULL,
  `szintability1` varchar(100) DEFAULT NULL,
  `activeability1` varchar(100) DEFAULT NULL,
  `reloadability1` varchar(100) DEFAULT NULL,
  `hppotnum` varchar(100) DEFAULT NULL,
  `hppotactive` varchar(100) DEFAULT NULL,
  `hppotreload` varchar(100) DEFAULT NULL,
  `shieldpotnum` varchar(100) DEFAULT NULL,
  `shieldpotactive` varchar(100) DEFAULT NULL,
  `shieldpotreload` varchar(100) DEFAULT NULL,
  `manapotnum` varchar(100) DEFAULT NULL,
  `manapotactive` varchar(100) DEFAULT NULL,
  `manapotreload` varchar(100) DEFAULT NULL,
  `x2num` varchar(100) DEFAULT NULL,
  `x3num` varchar(100) DEFAULT NULL,
  `x4num` varchar(100) DEFAULT NULL,
  `empnum` varchar(100) DEFAULT NULL,
  `empreload` varchar(100) DEFAULT NULL,
  `ishnum` varchar(100) DEFAULT NULL,
  `ishreload` varchar(100) DEFAULT NULL,
  `pldnum` varchar(100) DEFAULT NULL,
  `pldactive` varchar(100) DEFAULT NULL,
  `pldreload` varchar(100) DEFAULT NULL,
  `cloaknum` varchar(100) DEFAULT NULL,
  `cloaked` varchar(100) DEFAULT NULL,
  `pajzs` varchar(100) DEFAULT NULL,
  `pancel` varchar(100) DEFAULT NULL,
  `kotszer` varchar(100) DEFAULT NULL,
  `phalanx` varchar(100) DEFAULT NULL,
  `kard` varchar(100) DEFAULT NULL,
  `manakristaly` varchar(100) DEFAULT NULL,
  `alap` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `empactive` varchar(100) NOT NULL,
  `ishactive` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `abilities`
--
ALTER TABLE `abilities`
  ADD PRIMARY KEY (`kulcs`);

--
-- A tábla indexei `battle`
--
ALTER TABLE `battle`
  ADD PRIMARY KEY (`kulcs`);

--
-- A tábla indexei `costs`
--
ALTER TABLE `costs`
  ADD PRIMARY KEY (`kulcs`);

--
-- A tábla indexei `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`kulcs`);

--
-- A tábla indexei `felszereles`
--
ALTER TABLE `felszereles`
  ADD PRIMARY KEY (`kulcs`);

--
-- A tábla indexei `kasztok`
--
ALTER TABLE `kasztok`
  ADD PRIMARY KEY (`kulcs`);

--
-- A tábla indexei `mobs`
--
ALTER TABLE `mobs`
  ADD PRIMARY KEY (`kulcs`);

--
-- A tábla indexei `npcs`
--
ALTER TABLE `npcs`
  ADD PRIMARY KEY (`kulcs`);

--
-- A tábla indexei `specials`
--
ALTER TABLE `specials`
  ADD PRIMARY KEY (`kulcs`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`kulcs`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `abilities`
--
ALTER TABLE `abilities`
  MODIFY `kulcs` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT a táblához `battle`
--
ALTER TABLE `battle`
  MODIFY `kulcs` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT a táblához `costs`
--
ALTER TABLE `costs`
  MODIFY `kulcs` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT a táblához `events`
--
ALTER TABLE `events`
  MODIFY `kulcs` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT a táblához `felszereles`
--
ALTER TABLE `felszereles`
  MODIFY `kulcs` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT a táblához `kasztok`
--
ALTER TABLE `kasztok`
  MODIFY `kulcs` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT a táblához `mobs`
--
ALTER TABLE `mobs`
  MODIFY `kulcs` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT a táblához `npcs`
--
ALTER TABLE `npcs`
  MODIFY `kulcs` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT a táblához `specials`
--
ALTER TABLE `specials`
  MODIFY `kulcs` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `kulcs` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
